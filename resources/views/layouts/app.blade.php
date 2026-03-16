<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.style-global')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    @stack('styles')
</head>

<body>
<div class="container-scroller">

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    <div class="container-fluid page-body-wrapper">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- MAIN PANEL --}}
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>

            {{-- FOOTER --}}
            @include('layouts.footer')
        </div>

    </div>
</div>

@include('layouts.js-global')

@stack('scripts')

</body>
</html>