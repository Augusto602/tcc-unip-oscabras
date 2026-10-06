<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Sistema Agrícola')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- CSS específico da página --}}
    @yield('styles')
</head>

<body class="dashboard-body">

<div class="agro-dashboard">

    <aside class="sidebar-map">

        <div class="sidebar-header">

            <div class="brand-mark">
                🌱
            </div>

            <div>
                <h1>Sistema Agrícola</h1>
                <span>TCC UNIP</span>
            </div>

        </div>


        <nav class="sidebar-menu">

            {{-- HOME --}}
            <a href="{{ route('index.informacoes') }}"
            class="menu-item {{ request()->routeIs('index.informacoes') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>Home</span>

            </a>

            {{-- ROTAS DE ENTREGAS --}}
            <a href="{{ route('dashboard') }}"
            class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-satellite"></i>
                </span>

                <span>Resumo de Rotas</span>

            </a>


            {{-- CARRINHOS --}}
            <a href="{{ route('carrinhos.index') }}"
            class="menu-item {{ request()->routeIs('carrinhos.*') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-tractor"></i>
                </span>

                <span>Carrinhos</span>

            </a>


            {{-- MONITORAMENTO DO ROBÔ --}}
            <a href="{{ route('monitoramento') }}"
            class="menu-item {{ request()->routeIs('monitoramento') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-robot"></i>
                </span>

                <span>Monitoramento</span>

            </a>


            {{-- CLIENTES --}}
            <a href="{{ route('clientes.index') }}"
            class="menu-item {{ request()->routeIs('clientes.*') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-user"></i>
                </span>

                <span>Clientes</span>

            </a>

        </nav>




        <div class="sidebar-footer">

              


            <form method="POST"
                  action="{{ route('logout') }}"
                  class="logout-form">

                @csrf

                <button type="submit">
                    Sair
                </button>

            </form>

        </div>

    </aside>


    <main class="main-map">

        <header class="top-mapbar">

            <div class="search-box">


             

            </div>


            <div class="user-info">

                <span>
                    Usuário: {{ session('usuario_nome') }}
                </span>

                <span>
                    Tipo: {{ session('tipo_login') }}
                </span>

            </div>

        </header>


        <section class="content-map">

            @yield('content')

        </section>

    </main>

</div>


{{-- JS específico da página --}}
@yield('scripts')

</body>
</html>