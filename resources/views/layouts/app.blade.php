@php
$setting = \App\Helpers\Helper::getSetting();
$color = $setting->gateway_color;
@endphp

@php
// Função para converter HEX para RGBA
function hexToRgba($hex, $opacity = 0.5) {
$hex = str_replace('#', '', $hex);

if (strlen($hex) == 3) {
$r = hexdec(str_repeat(substr($hex, 0, 1), 2));
$g = hexdec(str_repeat(substr($hex, 1, 1), 2));
$b = hexdec(str_repeat(substr($hex, 2, 1), 2));
} else {
$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));
}

return "rgba($r, $g, $b, $opacity)";
}

$opacityColor = Str::contains($color, 'rgba')
? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.8)', $color)
: hexToRgba($color, 0.8);

$opacityColor2 = Str::contains($color, 'rgba')
? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.1)', $color)
: hexToRgba($color, 0.1);
@endphp
@props(['route'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-header-styles="transparent"
    style="" data-menu-styles="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="{{env('APP_NAME')}}">
    <meta name="Author" content="{{env('APP_NAME')}}">
    <meta name="keywords" content="{{env('APP_NAME')}}">
    <link rel="icon" type="image/x-icon" href="{{ asset($setting->gateway_favicon) }}">
    <title>{{ env('APP_NAME') }} - {{ $route }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.components.styles')

    <link href="[REDACTED_BASIC_AUTH_URL]"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <link rel="stylesheet" href="{{ asset('css/tail.css') }}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="nav-fixed bg-light" style="position: relative;">
    @include('layouts.components.navbar')
    <div id="layoutDrawer">
        @include('layouts.components.sidebar')
        <div id="layoutDrawer_content">
            <main class="body-container">
                {{ $slot }}
            </main>
            {{--  @include('layouts.components.footer') --}}
        </div>
    </div>

    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script type="module" src="{{asset('assets-v2/js/material.js')}}"></script>
    <script src="{{asset('assets-v2/js/scripts.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('assets-v2/js/charts/chart-defaults.js')}}"></script>
    <script src="{{asset('assets-v2/js/charts/demos/chart-pie-demo.js')}}"></script>
    <script src="{{asset('assets-v2/js/charts/demos/dashboard-chart-bar-grouped-demo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <script>
    // Função para mostrar notificações
    function showToast(type, message) {
        Swal.fire({
            toast: true,
            icon: type,
            title: message,
            animation: false,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: {
                popup: 'custom-swal-theme'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    // Lógica da Sidebar
    const body = document.body;
    const toggleButton = document.getElementById('drawerToggle');
    const observer = new MutationObserver(() => {
        if (body.classList.contains('drawer-toggled')) {
            toggleButton.classList.add('rotated-right');
            toggleButton.classList.remove('rotated-left');
        } else {
            toggleButton.classList.add('rotated-left');
            toggleButton.classList.remove('rotated-right');
        }
    });
    observer.observe(body, {
        attributes: true,
        attributeFilter: ['class']
    });

    // Lógica para trocar o tema claro/escuro
    function toggleTheme() {
        // Procure um botão com id="theme-toggle-button" na sua navbar/header para este código funcionar
        const themeToggleButton = document.getElementById('theme-toggle-button');

        if (document.body.hasAttribute('data-theme') && document.body.getAttribute('data-theme') === 'dark') {
            document.body.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
        } else {
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Aplica o tema salvo ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
        } else {
            document.body.removeAttribute('data-theme');
        }
    });
    </script>

    @if (session('success'))
    <script>
    showToast('success', "{{ session('success') }}");
    </script>
    @endif

    @if (session('error'))
    <script>
    showToast('danger', "{{ session('error') }}");
    </script>
    @endif

    @livewireScripts
</body>

</html>