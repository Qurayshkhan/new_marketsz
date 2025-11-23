<!DOCTYPE html data-theme="light">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/image/apple-touch-icon.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/registration.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead


    <script>
        window.addEventListener("load", () => {
            LiveChatWidget.call("hide_button", false);
        });
    </script>
    <!-- LiveChat -->
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 12524322; // <-- your license

        (function() {
            var lc = document.createElement('script');
            lc.async = true;
            lc.type = 'text/javascript';
            lc.src = 'https://cdn.livechatinc.com/tracking.js';

            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(lc, s);
        })();
    </script>

    <noscript>
        <a href="https://www.livechat.com/chat-with/12524322/" rel="nofollow">Chat with us</a>
        powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a>
    </noscript>
</head>

@php
    $overFlow = in_array(request()->route()->getName(), ['login', 'register'])
        ? 'overflow-y-scroll'
        : 'overflow-hidden';
@endphp

<body class="font-sans antialiased bg-gray-100 flex flex-col h-screen {{ $overFlow }} text-black">
    @inertia
</body>

</html>
