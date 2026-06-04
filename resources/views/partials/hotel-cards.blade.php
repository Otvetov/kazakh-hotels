@foreach($hotels as $hotel)
    @include('partials.hotel-card', ['hotel' => $hotel])
@endforeach
