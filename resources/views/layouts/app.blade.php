<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Sistema Agrícola')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    {{-- CSS específico da página --}}
    @yield('styles')

    <style>

        /* =========================================================
           BOTÃO MOBILE
        ========================================================= */

        .mobile-menu-button {
            display: none;

            width: 42px;
            height: 42px;

            align-items: center;
            justify-content: center;

            padding: 0;

            background: #16202d;

            border: 1px solid #29384b;
            border-radius: 8px;

            color: #dce7f3;

            font-size: 18px;

            cursor: pointer;

            transition: .2s;
        }


        .mobile-menu-button:hover {
            background: #1d2a38;

            border-color: #38506a;

            color: #ffffff;
        }


        /* =========================================================
           BOTÃO FECHAR
        ========================================================= */

        .sidebar-close-button {
            display: none;
        }


        /* =========================================================
           OVERLAY
        ========================================================= */

        .sidebar-overlay {
            display: none;
        }


        /* =========================================================
           DESKTOP
        ========================================================= */

        @media (min-width: 769px) {

            .sidebar-map {
                position: relative;
            }

        }


        /* =========================================================
           MOBILE / TABLET
        ========================================================= */

        @media (max-width: 768px) {

            html,
            body {
                width: 100%;
                max-width: 100%;

                overflow-x: hidden;
            }


            body.dashboard-body {
                overflow-x: hidden;
            }


            .agro-dashboard {
                display: block;

                width: 100%;
                min-height: 100vh;
            }


            /* =====================================================
               SIDEBAR
            ===================================================== */

            .sidebar-map {
                position: fixed;

                top: 0;
                left: 0;

                width: 280px;
                max-width: 85vw;

                height: 100dvh;

                z-index: 3000;

                overflow-y: auto;

                background: #081018;

                transform: translateX(-100%);

                transition: transform .25s ease;

                box-shadow: 8px 0 25px rgba(0, 0, 0, .4);
            }


            .sidebar-map.mobile-open {
                transform: translateX(0);
            }


            /* =====================================================
               BOTÃO FECHAR
            ===================================================== */

            .sidebar-close-button {
                position: absolute;

                top: 14px;
                right: 14px;

                width: 36px;
                height: 36px;

                display: flex;

                align-items: center;
                justify-content: center;

                padding: 0;

                background: #16202d;

                border: 1px solid #29384b;
                border-radius: 8px;

                color: #dce7f3;

                font-size: 17px;

                cursor: pointer;

                z-index: 20;

                transition: .2s;
            }


            .sidebar-close-button:hover {
                background: #1d2a38;

                border-color: #38506a;

                color: #ffffff;
            }


            /* =====================================================
               OVERLAY
            ===================================================== */

            .sidebar-overlay {
                position: fixed;

                inset: 0;

                display: block;

                background: rgba(0, 0, 0, .62);

                z-index: 2900;

                opacity: 0;
                visibility: hidden;

                pointer-events: none;

                transition:
                    opacity .25s ease,
                    visibility .25s ease;
            }


            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;

                pointer-events: auto;
            }


            /* =====================================================
               MAIN
            ===================================================== */

            .main-map {
                width: 100% !important;

                min-width: 0 !important;

                margin-left: 0 !important;
            }


            /* =====================================================
               TOPBAR
            ===================================================== */

            .top-mapbar {
                position: relative !important;

                width: 100% !important;

                min-height: 64px;

                display: flex !important;

                align-items: center !important;

                justify-content: flex-end !important;

                padding: 9px 14px !important;

                box-sizing: border-box;
            }


            /* =====================================================
               HAMBURGUER FIXO NO CANTO ESQUERDO
            ===================================================== */

            .mobile-menu-button {
                position: absolute !important;

                top: 50% !important;

                left: 14px !important;
                right: auto !important;

                transform: translateY(-50%) !important;

                display: flex !important;

                width: 42px !important;
                height: 42px !important;

                min-width: 42px;

                margin: 0 !important;
                padding: 0 !important;

                align-items: center;
                justify-content: center;

                background: #16202d;

                border: 1px solid #29384b;
                border-radius: 8px;

                color: #dce7f3;

                font-size: 18px;

                cursor: pointer;

                z-index: 100;
            }


            /* =====================================================
               USUÁRIO
            ===================================================== */

            .user-info {
                position: relative;

                display: flex !important;

                flex-direction: column;

                align-items: flex-end;

                gap: 2px;

                margin: 0 !important;

                font-size: 10px;

                line-height: 1.3;

                white-space: nowrap;
            }


            /* =====================================================
               CONTEÚDO
            ===================================================== */

            .content-map {
                width: 100%;

                box-sizing: border-box;
            }


            /* =====================================================
               HEADER SIDEBAR
            ===================================================== */

            .sidebar-header {
                min-height: 74px;

                padding: 18px 58px 18px 16px;

                gap: 12px;

                box-sizing: border-box;
            }


            .sidebar-header h1 {
                font-size: 18px;
            }


            .sidebar-header span {
                font-size: 10px;
            }


            .brand-mark {
                width: 42px;
                height: 42px;

                min-width: 42px;
            }


            /* =====================================================
               MENU
            ===================================================== */

            .sidebar-menu {
                padding: 10px 14px;
            }


            .menu-item {
                min-height: 44px;

                padding: 10px 12px;

                margin-bottom: 8px;

                font-size: 13px;
            }


            .menu-icon {
                width: 25px;

                font-size: 15px;
            }


            /* =====================================================
               FOOTER
            ===================================================== */

            .sidebar-footer {
                padding: 16px 14px;
            }


            .logout-form button {
                width: 100%;

                min-height: 42px;

                font-size: 12px;
            }

        }


        /* =========================================================
           CELULAR
        ========================================================= */

        @media (max-width: 480px) {

            .sidebar-map {
                width: 270px;
            }


            .top-mapbar {
                min-height: 60px !important;

                padding: 8px 10px !important;
            }


            .mobile-menu-button {
                left: 10px !important;

                width: 40px !important;
                height: 40px !important;

                min-width: 40px;

                font-size: 16px;
            }


            .user-info {
                font-size: 9px;
            }


            .sidebar-header {
                padding: 16px 54px 16px 14px;
            }


            .sidebar-header h1 {
                font-size: 17px;
            }


            .sidebar-menu {
                padding: 8px 12px;
            }


            .menu-item {
                min-height: 42px;

                padding: 9px 11px;

                font-size: 12px;
            }


            .sidebar-close-button {
                top: 12px;
                right: 12px;

                width: 34px;
                height: 34px;

                font-size: 16px;
            }

        }


        /* =========================================================
           CELULAR PEQUENO
        ========================================================= */

        @media (max-width: 360px) {

            .sidebar-map {
                width: 245px;
            }


            .user-info span:first-child {
                max-width: 140px;

                overflow: hidden;

                text-overflow: ellipsis;

                white-space: nowrap;
            }

        }

    </style>
</head>


<body class="dashboard-body">

<div class="agro-dashboard">


    {{-- =========================================================
         OVERLAY
    ========================================================== --}}

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside
        class="sidebar-map"
        id="sidebarMenu"
    >

        {{-- FECHAR MOBILE --}}

        <button
            type="button"
            class="sidebar-close-button"
            id="sidebarCloseButton"
            aria-label="Fechar menu"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>


        <div class="sidebar-header">

            <div class="brand-mark">
                🌱
            </div>

            <div>

                <h1>
                    Sistema Agrícola
                </h1>

                <span>
                    TCC UNIP
                </span>

            </div>

        </div>


        {{-- =========================================================
             MENU
        ========================================================== --}}

        <nav class="sidebar-menu">


            {{-- HOME --}}

            <a
                href="{{ route('index.informacoes') }}"
                class="menu-item {{ request()->routeIs('index.informacoes') ? 'active' : '' }}"
            >

                <span class="menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    Home
                </span>

            </a>


            {{-- ROTAS --}}

            <a
                href="{{ route('dashboard') }}"
                class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >

                <span class="menu-icon">
                    <i class="fa-solid fa-satellite"></i>
                </span>

                <span>
                    Resumo de Rotas
                </span>

            </a>


            {{-- CARRINHOS --}}

            <a
                href="{{ route('carrinhos.index') }}"
                class="menu-item {{ request()->routeIs('carrinhos.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">
                    <i class="fa-solid fa-tractor"></i>
                </span>

                <span>
                    Veículos
                </span>

            </a>


            {{-- MONITORAMENTO --}}

            <a
                href="{{ route('monitoramento') }}"
                class="menu-item {{ request()->routeIs('monitoramento') ? 'active' : '' }}"
            >

                <span class="menu-icon">
                    <i class="fa-solid fa-robot"></i>
                </span>

                <span>
                    Monitoramento
                </span>

            </a>


            {{-- CLIENTES --}}

            <a
                href="{{ route('clientes.index') }}"
                class="menu-item {{ request()->routeIs('clientes.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">
                    <i class="fa-solid fa-user"></i>
                </span>

                <span>
                    Usuários
                </span>

            </a>

        </nav>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="sidebar-footer">

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="logout-form"
            >

                @csrf

                <button type="submit">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Sair

                </button>

            </form>

        </div>

    </aside>


    {{-- =========================================================
         MAIN
    ========================================================== --}}

    <main class="main-map">


        {{-- =========================================================
             TOPBAR
        ========================================================== --}}

        <header class="top-mapbar">


            {{-- HAMBURGUER --}}

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Abrir menu"
            >

                <i class="fa-solid fa-bars"></i>

            </button>


            {{-- USUÁRIO --}}

            <div class="user-info">

                <span>
                    Usuário: {{ session('usuario_nome') }}
                </span>

                <span>
                    Tipo: {{ session('tipo_login') }}
                </span>

            </div>

        </header>


        {{-- =========================================================
             CONTEÚDO
        ========================================================== --}}

        <section class="content-map">

            @yield('content')

        </section>

    </main>

</div>


{{-- =========================================================
     MENU MOBILE
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const menuButton =
        document.getElementById('mobileMenuButton');

    const closeButton =
        document.getElementById('sidebarCloseButton');

    const sidebar =
        document.getElementById('sidebarMenu');

    const overlay =
        document.getElementById('sidebarOverlay');


    if (!menuButton || !sidebar || !overlay) {
        return;
    }


    /* =========================================================
       ABRIR
    ========================================================= */

    function abrirMenu() {

        sidebar.classList.add('mobile-open');

        overlay.classList.add('active');

        document.body.style.overflow = 'hidden';

    }


    /* =========================================================
       FECHAR
    ========================================================= */

    function fecharMenu() {

        sidebar.classList.remove('mobile-open');

        overlay.classList.remove('active');

        document.body.style.overflow = '';

    }


    /* =========================================================
       BOTÃO HAMBURGUER
    ========================================================= */

    menuButton.addEventListener('click', function (event) {

        event.preventDefault();

        abrirMenu();

    });


    /* =========================================================
       BOTÃO X
    ========================================================= */

    if (closeButton) {

        closeButton.addEventListener('click', function (event) {

            event.preventDefault();

            fecharMenu();

        });

    }


    /* =========================================================
       CLICOU FORA
    ========================================================= */

    overlay.addEventListener('click', function () {

        fecharMenu();

    });


    /* =========================================================
       ESC
    ========================================================= */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            fecharMenu();

        }

    });


    /* =========================================================
       CLICOU EM UMA OPÇÃO
    ========================================================= */

    document.querySelectorAll('.sidebar-menu .menu-item')
        .forEach(function (item) {

            item.addEventListener('click', function () {

                if (window.innerWidth <= 768) {

                    fecharMenu();

                }

            });

        });


    /* =========================================================
       VOLTOU PARA DESKTOP
    ========================================================= */

    window.addEventListener('resize', function () {

        if (window.innerWidth > 768) {

            fecharMenu();

        }

    });

});

</script>


{{-- JS específico da página --}}
@yield('scripts')

</body>
</html>