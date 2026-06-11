<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema Agricola')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Depois você pode mover seu CSS antigo para public/css/app.css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-text">
                <div class="nome">Sistema Agrícola</div>
                <div class="sub_nome">TCC UNIP</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('clientes.index') }}">Clientes</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sair</button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                Usuário: {{ session('usuario_nome') }}
            </div>

            <div>
                Tipo: {{ session('tipo_login') }}
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>
    </main>

</body>
</html>