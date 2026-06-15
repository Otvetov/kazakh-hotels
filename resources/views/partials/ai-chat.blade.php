{{-- Плавающий чат ИИ-помощника (доступен на всех страницах, закреплён справа снизу) --}}
<div id="aiChat" class="fixed bottom-5 right-5 z-[90] flex flex-col items-end">

    {{-- Окно чата --}}
    <div id="aiChatPanel"
         class="hidden mb-3 w-[calc(100vw-2.5rem)] max-w-[380px] h-[70vh] max-h-[560px] flex flex-col otl-surface overflow-hidden shadow-2xl rounded-3xl">

        {{-- Шапка --}}
        <div class="flex items-center gap-3 px-4 py-3 border-b border-white/10 bg-[#141516]">
            <div class="w-9 h-9 rounded-full bg-[#c084fc]/15 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#c084fc]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.9 5.6L19.5 9l-4.5 3.3L16.8 18 12 14.7 7.2 18l1.8-5.7L4.5 9l5.6-1.4L12 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-white font-semibold text-sm leading-tight">{{ __('messages.ai_widget_title') }}</div>
                <div class="text-[#7e8488] text-xs truncate">{{ __('messages.ai_widget_subtitle') }}</div>
            </div>
            <button type="button" onclick="closeAiChat()" aria-label="{{ __('messages.ai_widget_close') }}"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Лента сообщений --}}
        <div id="aiChatMessages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3"></div>

        {{-- Поле ввода --}}
        <form id="aiChatForm" class="border-t border-white/10 p-3 flex items-end gap-2 bg-[#141516]">
            <textarea id="aiChatInput" rows="1"
                      placeholder="{{ __('messages.ai_widget_placeholder') }}"
                      class="flex-1 resize-none max-h-28 bg-[#1b1c1d] border border-white/10 rounded-2xl px-4 py-2.5 text-sm text-white placeholder:text-[#7e8488] focus:outline-none focus:border-[#c084fc]"></textarea>
            <button type="submit" id="aiChatSend" aria-label="{{ __('messages.ai_widget_send') }}"
                    class="flex-shrink-0 w-10 h-10 rounded-full bg-[#c084fc] text-[#0a0a0a] flex items-center justify-center hover:bg-[#a855f7] transition disabled:opacity-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"></path>
                </svg>
            </button>
        </form>
    </div>

    {{-- Кнопка-триггер --}}
    <button type="button" id="aiChatLauncher" onclick="toggleAiChat()" aria-label="{{ __('messages.ai_widget_open') }}"
            class="w-14 h-14 rounded-full bg-[#c084fc] text-[#0a0a0a] shadow-xl flex items-center justify-center hover:bg-[#a855f7] transition">
        <svg id="aiLauncherIcon" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z M3 20l1.5-3"></path>
        </svg>
    </button>
</div>

<script>
const AI_T = {
    greeting: @json(__('messages.ai_widget_greeting')),
    error: @json(__('messages.ai_widget_error')),
};
const AI_CHAT_URL = @json(route('assistant.chat'));
let aiHistory = [];
let aiBusy = false;
let aiStarted = false;

function aiEl(id) { return document.getElementById(id); }

function toggleAiChat() {
    const panel = aiEl('aiChatPanel');
    if (panel.classList.contains('hidden')) {
        openAiChat();
    } else {
        closeAiChat();
    }
}

function openAiChat() {
    const panel = aiEl('aiChatPanel');
    panel.classList.remove('hidden');
    if (!aiStarted) {
        aiStarted = true;
        aiAddMessage('assistant', AI_T.greeting);
    }
    setTimeout(() => aiEl('aiChatInput')?.focus(), 50);
}

function closeAiChat() {
    aiEl('aiChatPanel').classList.add('hidden');
}

// Добавить сообщение в ленту
function aiAddMessage(role, text) {
    const wrap = document.createElement('div');
    wrap.className = 'flex ' + (role === 'user' ? 'justify-end' : 'justify-start');

    const bubble = document.createElement('div');
    bubble.className = 'max-w-[85%] rounded-2xl px-3.5 py-2.5 text-sm whitespace-pre-wrap break-words ' +
        (role === 'user'
            ? 'bg-[#c084fc] text-[#0a0a0a]'
            : 'bg-[#1b1c1d] border border-white/10 text-gray-100');
    bubble.textContent = text;

    wrap.appendChild(bubble);
    const box = aiEl('aiChatMessages');
    box.appendChild(wrap);
    box.scrollTop = box.scrollHeight;
    return bubble;
}

// Индикатор «печатает…»
function aiAddTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'flex justify-start';
    wrap.id = 'aiTyping';
    wrap.innerHTML = '<div class="bg-[#1b1c1d] border border-white/10 rounded-2xl px-4 py-3">' +
        '<div class="flex gap-1">' +
        '<span class="w-2 h-2 bg-[#7e8488] rounded-full animate-bounce"></span>' +
        '<span class="w-2 h-2 bg-[#7e8488] rounded-full animate-bounce" style="animation-delay:.15s"></span>' +
        '<span class="w-2 h-2 bg-[#7e8488] rounded-full animate-bounce" style="animation-delay:.3s"></span>' +
        '</div></div>';
    const box = aiEl('aiChatMessages');
    box.appendChild(wrap);
    box.scrollTop = box.scrollHeight;
}
function aiRemoveTyping() { aiEl('aiTyping')?.remove(); }

// Отправить сообщение помощнику
async function aiSend(text) {
    text = (text || '').trim();
    if (!text || aiBusy) return;

    openAiChat();
    aiBusy = true;
    aiEl('aiChatSend').disabled = true;

    aiAddMessage('user', text);
    aiHistory.push({ role: 'user', content: text });
    aiAddTyping();

    try {
        const res = await fetch(AI_CHAT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                messages: aiHistory.slice(-20),
                context: window.AI_PAGE_CONTEXT || {},
            }),
        });

        aiRemoveTyping();

        if (!res.ok) throw new Error('http ' + res.status);

        const data = await res.json();
        const reply = data.reply || AI_T.error;
        aiAddMessage('assistant', reply);
        aiHistory.push({ role: 'assistant', content: reply });
    } catch (e) {
        aiRemoveTyping();
        aiAddMessage('assistant', AI_T.error);
    } finally {
        aiBusy = false;
        aiEl('aiChatSend').disabled = false;
        aiEl('aiChatInput')?.focus();
    }
}

// Вызывается с других страниц (например, плитки вопросов на странице отеля)
function aiAsk(question) {
    aiSend(question);
}

document.addEventListener('DOMContentLoaded', function () {
    const form = aiEl('aiChatForm');
    const input = aiEl('aiChatInput');

    form?.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value;
        input.value = '';
        input.style.height = 'auto';
        aiSend(text);
    });

    // Enter — отправить, Shift+Enter — перенос строки
    input?.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    });

    // Авто-высота поля ввода
    input?.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 112) + 'px';
    });
});
</script>
