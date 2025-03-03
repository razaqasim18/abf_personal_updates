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
        Aslam-o_Alikum...!!</br/>
        Dear Member.
    </h1>
    <span>
        Thanks For E-wallet Request.<br />
        @if($status == '1')
             Your Payment Is Received And Approved By Admin.<br />
             Amount Will Be Added In Your E-wallet Account Shortly <br />
        @else
            Your Transaction Cancelled by ABF Cosmetics<br />
            Transection ID: {{ $balancerequest->transectionid }}<br />
            Amount Deposit: {{ $balancerequest->amount }}<br />
            Transection Date: {{ $balancerequest->transectiondate }}<br />
        @endif
        <!--{{ $status == '1' ? 'approved' : 'denied' }}.<br />-->
        <!--{{ $status == '1' ? 'Amount is add to your wallet' : 'Please contact our customer service to get further information.' }}-->
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
