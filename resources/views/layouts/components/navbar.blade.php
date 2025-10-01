@php
$setting = \App\Helpers\Helper::getSetting();
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Navbar Background - Matching Dashboard Theme */
    .top-app-bar.navbar {
        background: var(--glass-bg, rgba(255, 255, 255, 0.1)) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid var(--glass-border, rgba(255, 255, 255, 0.2)) !important;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    }

    /* Dark theme support for navbar */
    [data-theme="dark"] .top-app-bar.navbar {
        background: var(--glass-bg, rgba(255, 255, 255, 0.08)) !important;
        border-bottom-color: var(--glass-border, rgba(255, 255, 255, 0.15)) !important;
    }

    /* Override default topbar styles */
    .topbar-nav .navbar {
        background-color: transparent !important;
    }

    .glassmorphism-toggle-drawer {
        background: var(--glass-bg, rgba(255, 255, 255, 0.1)) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.2)) !important;
        border-radius: 12px !important;
        padding: 8px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .glassmorphism-toggle-drawer::before {
        content: '' !important;
        position: absolute !important;
        top: 0 !important;
        left: -100% !important;
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent) !important;
        transition: left 0.5s ease !important;
    }

    .glassmorphism-toggle-drawer:hover {
        background: var(--glass-bg, rgba(255, 255, 255, 0.15)) !important;
        border-color: var(--glass-border, rgba(255, 255, 255, 0.3)) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .glassmorphism-toggle-drawer:hover::before {
        left: 100% !important;
    }

    .glassmorphism-toggle-drawer .toggle-icon-container {
        position: relative !important;
        z-index: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .glassmorphism-toggle-drawer .toggle-icon {
        color: var(--text-primary, #333) !important;
        transition: all 0.3s ease !important;
    }

    .glassmorphism-toggle-drawer:hover .toggle-icon {
        transform: scale(1.1) !important;
        filter: brightness(1.2) !important;
    }

    .glassmorphism-toggle-drawer .toggle-line {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform-origin: center !important;
    }

    .glassmorphism-toggle-drawer:hover .toggle-line-1 {
        transform: translateY(1px) rotate(3deg) !important;
    }

    .glassmorphism-toggle-drawer:hover .toggle-line-2 {
        transform: scaleX(0.8) !important;
        opacity: 0.7 !important;
    }

    .glassmorphism-toggle-drawer:hover .toggle-line-3 {
        transform: translateY(-1px) rotate(-3deg) !important;
    }

    /* Dark theme support */
    [data-theme="dark"] .glassmorphism-toggle-drawer {
        background: var(--glass-bg, rgba(0, 0, 0, 0.2)) !important;
        border-color: var(--glass-border, rgba(255, 255, 255, 0.1)) !important;
    }

    [data-theme="dark"] .glassmorphism-toggle-drawer:hover {
        background: var(--glass-bg, rgba(0, 0, 0, 0.3)) !important;
        border-color: var(--glass-border, rgba(255, 255, 255, 0.2)) !important;
    }

    [data-theme="dark"] .glassmorphism-toggle-drawer .toggle-icon {
        color: var(--text-primary, #fff) !important;
    }

    /* Theme toggle button styles */
    #themeToggleBtn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    #themeToggleBtn:hover {
        transform: scale(1.1) !important;
    }

    #themeIcon {
        transition: all 0.3s ease !important;
    }

    /* Avatar Button Styles - SwiftPay */
    button.icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle#dropdownMenuProfile,
    .icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle,
    button.icon-navbar {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        padding: 4px !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
        width: auto !important;
        height: auto !important;
        min-width: 40px !important;
        min-height: 40px !important;
        box-shadow: none !important;
    }

    button.icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle#dropdownMenuProfile:hover,
    .icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle:hover,
    button.icon-navbar:hover {
        transform: scale(1.05) !important;
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    button.icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle#dropdownMenuProfile:focus,
    .icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle:focus,
    button.icon-navbar:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3) !important;
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
    }

    button.icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle#dropdownMenuProfile:active,
    .icon-navbar.btn.btn-lg.btn-icon.dropdown-toggle:active,
    button.icon-navbar:active {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    button.icon-navbar img,
    .icon-navbar img,
    #dropdownMenuProfile img {
        display: block !important;
        border: 2px solid rgba(255, 255, 255, 0.2) !important;
        transition: border-color 0.2s ease !important;
        object-fit: cover !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 100px !important;
    }

    button.icon-navbar:hover img,
    .icon-navbar:hover img,
    #dropdownMenuProfile:hover img {
        border-color: rgba(255, 255, 255, 0.5) !important;
    }

    /* Garantir que o dropdown toggle funcione */
    #dropdownMenuProfile {
        background: transparent !important;
        border: none !important;
    }

    #dropdownMenuProfile::after {
        display: none !important;
    }
</style>

<script>
    // Theme Toggle Functionality
    function toggleTheme() {
        const html = document.querySelector('html');
        const themeIcon = document.getElementById('themeIcon');
        const currentTheme = html.getAttribute('data-theme') || html.getAttribute('data-bs-theme');
        
        if (currentTheme === 'dark') {
            html.removeAttribute('data-theme');
            html.removeAttribute('data-bs-theme');
            html.setAttribute('data-theme-mode', 'light');
            themeIcon.className = 'bi bi-sun-fill';
            localStorage.setItem('theme', 'light');
        } else {
            html.setAttribute('data-theme', 'dark');
            html.setAttribute('data-bs-theme', 'dark');
            html.setAttribute('data-theme-mode', 'dark');
            themeIcon.className = 'bi bi-moon-fill';
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        const html = document.querySelector('html');
        const themeIcon = document.getElementById('themeIcon');
        
        if (savedTheme === 'dark') {
            html.setAttribute('data-theme', 'dark');
            html.setAttribute('data-bs-theme', 'dark');
            html.setAttribute('data-theme-mode', 'dark');
            themeIcon.className = 'bi bi-moon-fill';
        } else {
            html.removeAttribute('data-theme');
            html.removeAttribute('data-bs-theme');
            html.setAttribute('data-theme-mode', 'light');
            themeIcon.className = 'bi bi-sun-fill';
        }
    });
</script>

<nav class="top-app-bar navbar navbar-expand">
    <div class="px-4 container-fluid">
        <div class="text-uppercase font-monospace logo h-100">
            <div class="margin-logo"></div>
            <img src="{{ $setting->gateway_logo }}" height="auto" width="160">
           {{--  {{ $setting->gateway_name }} --}}
        </div>
        <!-- Drawer toggle button-->
        <button class="order-1 icon-navbar btn btn-lg btn-icon order-lg-0 glassmorphism-toggle-drawer" id="drawerToggle" href="javascript:void(0);">
            <div class="toggle-icon-container">
                <svg class="toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="toggle-line toggle-line-1" d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path class="toggle-line toggle-line-2" d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path class="toggle-line toggle-line-3" d="M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </button>
        <!-- Navbar brand-->
        <a class="icon-navbar navbar-brand me-auto" href="/dashboard">
            <div class="text-uppercase font-monospace">

            </div>
        </a>
        <!-- Navbar items-->
        <div class="mx-3 d-flex align-items-center me-lg-0">
            <!-- Navbar-->
           {{--  <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item"><a class="nav-link" href="index.html">Overview</a></li>
                <li class="nav-item"><a class="nav-link" href="https://docs.startbootstrap.com/material-admin-pro" target="_blank">Documentation</a></li>
            </ul> --}}
            <!-- Navbar buttons-->
            <div class="d-flex">
                <!-- Messages dropdown-->
                {{-- <div class="dropdown dropdown-notifications d-none d-sm-block">
                    <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuMessages" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">mail_outline</i></button>
                    <ul class="py-0 mt-3 overflow-hidden dropdown-menu dropdown-menu-end me-3" aria-labelledby="dropdownMenuMessages">
                        <li><h6 class="py-3 icon-navbar dropdown-header bg-primary fw-500">Notificações</h6></li>
                        <li><hr class="my-0 dropdown-divider" /></li>
                        <li>
                            <a class="dropdown-item unread" href="#!">
                                <div class="dropdown-item-content">
                                    <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">{{ $setting->gateway_name }} Informa:</div></div>
                                    <div class="dropdown-item-content-subtext">Seja bem vindo Sr(a) {{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }} a {{ $setting->gateway_name }}.</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div> --}}
                <!-- Theme toggle button-->
                <button class="icon-navbar btn btn-lg btn-icon me-3" onclick="toggleTheme()" aria-label="Toggle theme" id="themeToggleBtn">
                    <i class="bi bi-sun-fill" id="themeIcon"></i>
                </button>
                <!-- Notifications and alerts dropdown-->
                <div class="dropdown dropdown-notifications d-sm-block">
                    <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuNotifications" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">notifications</i></button>
                    <ul class="py-0 mt-3 overflow-hidden dropdown-menu dropdown-menu-end me-3" aria-labelledby="dropdownMenuNotifications">
                        <li><h6 class="py-3 icon-navbar dropdown-header fw-500" style="color:white!important;background: {{ $setting->gateway_color }}">Notificações</h6></li>
                        <li><hr class="my-0 dropdown-divider" /></li>
                        <li>
                            <a class="dropdown-item unread" href="#!">
                                <div class="dropdown-item-content">
                                    <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">{{ $setting->gateway_name }} Informa:</div></div>
                                    <div class="dropdown-item-content-subtext">Seja bem vindo Sr(a) {{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }} a {{ $setting->gateway_name }}.</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- User profile dropdown-->
                <div class="dropdown">
                    <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle" id="dropdownMenuProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 100px"><img src="{{auth()->user()->avatar}}" style="width:32px;height:32px;border-radius:100px"></button>
                    <ul class="mt-3 dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuProfile">
                        <li class="nav-item-email">
                                {{ auth()->user()->email }}
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('my.profile.index')}}">
                                <i class="material-icons leading-icon color-gateway">person</i>
                                <div class="me-3">Perfil</div>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#!">
                                <i class="material-icons leading-icon color-gateway">help</i>
                                <div class="me-3">Suporte</div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                            <button type="submit" class="dropdown-item btn-link">
                                <i class="material-icons leading-icon color-gateway">logout</i>
                                <div class="me-3">Sair</div>
                            </button>
                        </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
