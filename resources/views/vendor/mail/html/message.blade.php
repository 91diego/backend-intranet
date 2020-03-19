@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => asset('images/Anuva.jpg')])
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            Para Mayores informes con Beatriz Arellano, Email: <a href="mailto:bat@idex.cc">bat@idex.cc</a><br>
            © {{ date('Y') }} {{ 'IDEX' }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
