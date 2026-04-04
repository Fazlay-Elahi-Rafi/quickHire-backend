<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard </title>
</head>

<body>

    {{ $slot }}


    <!-- JavaScript -->

    <!-- App JS -->
    {{-- <script src="{{ asset('backend/assets/js/app.js') }}"></script> --}}
    @stack('scripts')
</body>

</html>
