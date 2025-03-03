@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ env('APP_NAME') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{-- This is our main message  --}}
    <h1
        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">
        Aslam-o_Alikum...!
        Dear customer!</br>
    </h1>
    <span>
        {{ $msg }}.<br />
    </span><br />
    <span>
        Order ID: {{ $order->order_no }}
    </span><br <span>
    Address: {{ $orderShipping->address }}
    </span><br />
    <span>
        Phone number: {{ $order->phone }}
    </span><br />
    <span>
        Date:{{ date('d M Y') }}
    </span><br />
    <br />
    Regards,<br />
    Attire beauty fragrance Pakistan.
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
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
