<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema Agrícola')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="dashboard-body">

<div class="agro-dashboard">

    <aside class="sidebar-map">
        <div class="sidebar-header">
            <div class="brand-mark">🌱</div>

            <div>
                <h1>Sistema Agrícola</h1>
                <span>TCC UNIP</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu-icon">🛰️</span>
                <span>Rotas Entregas</span>
            </a>

            <a href="{{ route('carrinhos.index') }}" class="menu-item {{ request()->routeIs('carrinhos.*') ? 'active' : '' }}">
                <span class="menu-icon">🚜</span>
                <span>Carrinhos</span>
            </a>

            <a href="{{ route('clientes.index') }}" class="menu-item {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <span class="menu-icon">👤</span>
                <span>Clientes</span>
            </a>
        </nav>

        <div class="sidebar-section">
            <div class="section-title">Mapa de Miniclima</div>

            <div class="climate-card blue">
                <div class="climate-number">3</div>
                <div class="climate-info">
                    <strong>Setor Norte</strong>
                    <span>Umidade 72%</span>
                </div>
                <div class="battery">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="climate-card red">
                <div class="climate-number">4</div>
                <div class="climate-info">
                    <strong>Setor Leste</strong>
                    <span>Temp. 31°C</span>
                </div>
                <div class="battery">
                    <span></span><span></span><span></span>
                </div>
            </div>

            <div class="climate-card green">
                <div class="climate-number">5</div>
                <div class="climate-info">
                    <strong>Setor Central</strong>
                    <span>Solo 63%</span>
                </div>
                <div class="battery">
                    <span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="climate-card orange">
                <div class="climate-number">1</div>
                <div class="climate-info">
                    <strong>Setor Sul</strong>
                    <span>Vento 12 km/h</span>
                </div>
                <div class="battery">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="total-box">
                <span>🌱 Índice Geral:</span>
                <strong>82%</strong>
            </div>

            <label class="check-line">
                <input type="checkbox">
                Exibir Regiões
            </label>

            <label class="check-line">
                <input type="checkbox">
                Exibir Pontos Operacionais
            </label>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit">Sair</button>
            </form>
        </div>
    </aside>

    <main class="main-map">
        <header class="top-mapbar">
            <div class="search-box">
                <button type="button">+</button>
                <input type="text" placeholder="Endereço / Área agrícola">
                <button type="button">🔍</button>
            </div>

            <div class="user-info">
                <span>Usuário: {{ session('usuario_nome') }}</span>
                <span>Tipo: {{ session('tipo_login') }}</span>
            </div>
        </header>

        <section class="content-map">
            @yield('content')
        </section>
    </main>

</div>

</body>
</html>