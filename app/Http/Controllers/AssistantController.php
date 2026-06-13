<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AssistantController extends Controller
{
    /**
     * Принять сообщение пользователя и вернуть ответ помощника.
     */
    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:30'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:2000'],
            'context' => ['nullable', 'array'],
            'context.hotel' => ['nullable', 'string', 'max:120'],
            'context.city' => ['nullable', 'string', 'max:120'],
            'context.page' => ['nullable', 'string', 'max:300'],
        ]);

        $locale = app()->getLocale();
        $messages = array_map(fn ($m) => [
            'role' => $m['role'],
            'content' => $m['content'],
        ], $data['messages']);

        $reply = $this->ask($messages, $data['context'] ?? [], $locale);

        return response()->json(['reply' => $reply]);
    }

    /**
     * Сформировать запрос к модели и получить ответ.
     */
    private function ask(array $messages, array $context, string $locale): string
    {
        $apiKey = config('services.anthropic.key');

        if (empty($apiKey)) {
            return $this->fallbackReply($locale);
        }

        $system = $this->systemPrompt($locale);

        if (!empty($context['hotel']) || !empty($context['city'])) {
            $system .= "\n\nПользователь сейчас смотрит страницу отеля: "
                . trim(($context['hotel'] ?? '') . ' — ' . ($context['city'] ?? ''), ' —')
                . '. Если вопрос касается этого отеля, отвечай прежде всего о нём.';
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(45)->post('https://api.anthropic.com/v1/messages', [
                'model' => config('services.anthropic.model', 'claude-opus-4-8'),
                'max_tokens' => 1024,
                'system' => [
                    [
                        'type' => 'text',
                        'text' => $system,
                        'cache_control' => ['type' => 'ephemeral'],
                    ],
                ],
                'messages' => $messages,
            ]);

            if (!$response->successful()) {
                Log::warning('Assistant API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $this->errorReply($locale);
            }

            $text = '';
            foreach ($response->json('content', []) as $block) {
                if (($block['type'] ?? null) === 'text') {
                    $text .= $block['text'];
                }
            }

            $text = trim($text);

            return $text !== '' ? $text : $this->errorReply($locale);
        } catch (\Throwable $e) {
            Log::warning('Assistant request failed', ['message' => $e->getMessage()]);

            return $this->errorReply($locale);
        }
    }

    /**
     * Системный промпт с описанием сайта и актуальными данными по отелям.
     */
    private function systemPrompt(string $locale): string
    {
        $langName = match ($locale) {
            'kk' => 'казахском',
            'en' => 'английском',
            default => 'русском',
        };

        $intro = <<<TXT
        Ты — встроенный помощник сайта бронирования отелей по Казахстану «Kazakh Hotels».
        Помогаешь гостям выбрать отель, понять условия проживания и оформить бронирование.

        Возможности сайта, о которых ты можешь рассказать:
        - Поиск отелей по городу, датам заезда/выезда и числу гостей и номеров.
        - Каталог отелей с фильтрами по цене за ночь и рейтингу, сортировкой по популярности, цене и рейтингу.
        - Страница отеля: фотогалерея, описание, список номеров с ценой за ночь и вместимостью, отзывы гостей.
        - Бронирование номера на выбранные даты (нужно войти в аккаунт), просмотр своих броней и их отмена.
        - Избранные отели, отзывы с оценкой, личный профиль.
        - Интерфейс на трёх языках (русский, английский, казахский) и тёмная/светлая тема.

        Как оформить бронирование: выбрать отель → выбрать номер → указать даты заезда и выезда → нажать «Забронировать». Если на эти даты номер занят, система покажет, до какой даты он недоступен.

        Правила ответа:
        - Отвечай на {$langName} языке, кратко и по делу, дружелюбно.
        - Используй данные об отелях ниже, когда спрашивают про конкретные отели, города или цены.
        - Если точных данных нет (например, есть ли в отеле бассейн или завтрак — таких полей на сайте нет), честно скажи об этом и предложи уточнить детали при бронировании или у самого отеля.
        - Отвечай только по теме сайта, отелей и путешествий по Казахстану. На посторонние вопросы вежливо возвращай к теме.
        - Не придумывай отели, цены и условия, которых нет в данных.
        - Выдавай только финальный ответ для пользователя, без описания своих рассуждений.
        TXT;

        $snapshot = Cache::remember('assistant_hotels_snapshot', now()->addMinutes(15), function () {
            $hotels = Hotel::with('rooms')->orderByDesc('rating')->get();

            if ($hotels->isEmpty()) {
                return 'Данные об отелях временно недоступны.';
            }

            $cities = $hotels->pluck('city')->unique()->filter()->values()->implode(', ');

            $lines = $hotels->map(function (Hotel $hotel) {
                $min = (int) $hotel->rooms->min('price_per_night');
                $rooms = $hotel->rooms
                    ->map(fn ($r) => $r->name . ' (' . (int) $r->price_per_night . '₸/ночь, до ' . (int) $r->capacity . ' гостей)')
                    ->implode('; ');

                $price = $min > 0 ? "от {$min}₸ за ночь" : 'цена уточняется';

                return sprintf(
                    '- %s (%s), рейтинг %s, %s. Номера: %s',
                    $hotel->name,
                    $hotel->city,
                    rtrim(rtrim((string) $hotel->rating, '0'), '.') ?: $hotel->rating,
                    $price,
                    $rooms !== '' ? $rooms : 'нет данных'
                );
            })->implode("\n");

            return "Города с отелями: {$cities}.\n\nСписок отелей:\n{$lines}";
        });

        return $intro . "\n\nАктуальные данные об отелях:\n" . $snapshot;
    }

    private function fallbackReply(string $locale): string
    {
        return match ($locale) {
            'kk' => 'Қазір ИИ-көмекшісі қосылмаған. Қонақ үйді басты беттегі іздеу арқылы таңдай аласыз: қаланы, күндерді және қонақтар санын көрсетіңіз. Сұрағыңыз болса — қонақ үй парақшасындағы бөлмелер мен пікірлерден ақпарат табасыз.',
            'en' => 'The AI assistant is not connected yet. You can still find a hotel using the search on the home page — pick a city, dates and number of guests. Each hotel page shows rooms, prices and guest reviews.',
            default => 'ИИ-помощник пока не подключён. Подобрать отель можно через поиск на главной: укажите город, даты и число гостей. На странице отеля есть номера, цены и отзывы гостей.',
        };
    }

    private function errorReply(string $locale): string
    {
        return match ($locale) {
            'kk' => 'Кешіріңіз, жауап алу мүмкін болмады. Сәл уақыттан кейін қайталап көріңіз.',
            'en' => 'Sorry, I could not get a response. Please try again in a moment.',
            default => 'Извините, не удалось получить ответ. Попробуйте ещё раз через пару секунд.',
        };
    }
}
