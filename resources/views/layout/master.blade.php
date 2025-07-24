<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="description"
        content="MyMalls gives you a personal shopping address in the US and Europe with express forwarding to your home or business in . Sign up now for free!">
    <link rel="stylesheet" href="{{ asset('assets/css/tw.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous">
    </script>
    <title>@yield('title')</title>
</head>

<body>
    <div>
        <div class="flex flex-col min-h-screen">
            <div class="data"></div>
            @include('layout.header')
            <div>
                @yield('content')
            </div>
            @include('layout.footer')
        </div>
    </div>
    <script src="{{ asset('assets/js/whale-animate.js') }}"></script>
    @yield('script')
</body>

</html>
