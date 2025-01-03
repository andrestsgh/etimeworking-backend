<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Time Working')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>        
    <!-- Spinner para las cargas -->
    <div id="loadingSpinner" class="position-fixed top-0 start-0 w-100 h-100 d-none">
        <div class="d-flex justify-content-center align-items-center h-100 bg-dark bg-opacity-50">
            <div class="spinner-border text-light" role="status"></div>
        </div>
    </div>

    <!-- Sólo muestra el header y footer si el usuario está autenticado -->
    @if(auth()->check())
        <header class="fixed-top">@include('partials.header')</header>
    @endif

    <main class="pt-5 pb-5">
        @yield('content')
    </main>

    @if(auth()->check())
        <footer class="fixed-bottom">@include('partials.footer')</footer>
        
    @endif
</body>
<!-- Script para activar el spinner -->
<script>
    function showLoadingSpinner() {
        document.getElementById('loadingSpinner').classList.remove('d-none');
    }
</script>
</html>
