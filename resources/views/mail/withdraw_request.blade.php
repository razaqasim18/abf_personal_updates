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
        Dear Member!<br/>
    </h1>
    <span>
        This email notify you that your withdraw request on ABF Cosmetics  has been processed with following details:<br/>

        Transection ID: {{ $balancerequest->transectionid }}<br />
        Withdaraw Amount: {{ $balancerequest->cashout_amount }}<br />
        Transection Date: {{ $balancerequest->transectiondate }}<br />

        {{ $withdrawrequest->requested_amount }} on
        {{date('d M Y h:i A', strtotime($withdrawrequest->created_at)) }} has been
        {{ $status == '1' ? 'approved' : 'denied' }}.<br />
    </span><br />
    For Any Issues Please Contact Our Customer Service. Please Send  Mail on Our e-mail {{ env('MAIL_FROM_ADDRESS') }}<br />
    Best Regards,<br />
    Attire Beauty Fragrance Pakistan.
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
