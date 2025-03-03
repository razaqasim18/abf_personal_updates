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
        Aslam-o_Alikum...!<br />
        Dear user!
    </h1>
    <span>
        @if($epinrequest->transectionid)
            Your RG-code transaction {{ $epinrequest->transectionid }} is approved.<br />
        @else
            You RG-code has been purchased.<br />
        @endif
        Please register your account with RG-code & E-mail address.<br />
        E-mail: {{ $epinrequest->email }} <br />
        RG-code: {{ $uuid }} <br />
    </span><br />
    <div style="text-align:center">
        <a href="{{ config('app.url') }}/register" class="button button-primary" target="_blank" rel="noopener"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748; margin:15px 0px;">Register</a>
    </div>
    Thank you for using our application.<br />
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
