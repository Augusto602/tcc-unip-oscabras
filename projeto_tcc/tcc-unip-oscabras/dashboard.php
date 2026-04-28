    <?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$nome_usuario = $_SESSION['usuario_nome'];
$tipo_login   = $_SESSION['tipo_login'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Agricolo — Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --topbar-h: 60px;
            --green-dark: #0a1a0e;
            --green-mid: #1a3520;
            --green-accent: #4aaa25;
            --green-soft: #7aaa6a;
            --green-text: #d0e8c0;
            --green-muted: #6a9a5a;
            --border: rgba(100,180,60,0.15);
            --border-hover: rgba(100,200,60,0.35);
        }

        body {
            min-height: 100vh;
            background: #0a1a0e;
            font-family: 'DM Sans', sans-serif;
            color: var(--green-text);
            display: flex;
            overflow: hidden;
        }

        /* ── BG EFFECTS ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 80% 10%, rgba(34,85,34,0.18) 0%, transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(20,60,20,0.22) 0%, transparent 45%),
                linear-gradient(rgba(100,180,60,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100,180,60,0.03) 1px, transparent 1px);
            background-size: auto, auto, 48px 48px, 48px 48px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: rgba(8, 20, 10, 0.92);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2a6b2a, #4a9a1a);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 0 1px rgba(100,200,60,0.2);
        }

        .brand-text .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #e8f5e0;
            line-height: 1.2;
        }

        .brand-text .brand-sub {
            font-size: 10px;
            color: var(--green-muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(100,150,80,0.5);
            padding: 0.75rem 1.25rem 0.25rem;
        }

        .nav-item {
            position: relative;
        }

        .nav-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 1.25rem;
            cursor: pointer;
            transition: background 0.15s;
            user-select: none;
        }

        .nav-trigger:hover {
            background: rgba(100,200,60,0.06);
        }

        .nav-trigger.active {
            background: rgba(100,200,60,0.1);
        }

        .nav-trigger.active::before {
            content: '';
            position: absolute;
            left: 0; top: 4px; bottom: 4px;
            width: 3px;
            background: var(--green-accent);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            background: rgba(100,180,60,0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-label {
            flex: 1;
            font-size: 13px;
            font-weight: 500;
            color: #a0c890;
        }

        .nav-trigger.active .nav-label { color: #d0e8c0; }

        .nav-arrow {
            font-size: 10px;
            color: var(--green-muted);
            transition: transform 0.2s;
        }

        .nav-arrow.open { transform: rotate(180deg); }

        .submenu {
            display: none;
            background: rgba(0,0,0,0.15);
            border-left: 1px solid var(--border);
            margin-left: 1.25rem;
        }

        .submenu.open { display: block; }

        .submenu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 1rem;
            color: #7a9a6a;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.15s, background 0.15s;
        }

        .submenu a::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(100,180,60,0.4);
            flex-shrink: 0;
        }

        .submenu a:hover {
            color: #d0e8c0;
            background: rgba(100,200,60,0.06);
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: rgba(100,180,60,0.06);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #2a6b2a, #4aaa25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #e8f5d8;
            flex-shrink: 0;
        }

        .user-info .user-name {
            font-size: 13px;
            font-weight: 500;
            color: #c0e0a8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }

        .user-info .user-role {
            font-size: 11px;
            color: var(--green-muted);
        }

        .logout-btn {
            margin-left: auto;
            width: 28px;
            height: 28px;
            background: rgba(220,60,40,0.12);
            border: 1px solid rgba(220,80,60,0.25);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background 0.15s;
            flex-shrink: 0;
        }

        .logout-btn:hover { background: rgba(220,60,40,0.25); }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-h);
            background: rgba(8,20,10,0.7);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            color: #e0f0d0;
            flex: 1;
        }

        .topbar-badge {
            font-size: 11px;
            font-weight: 500;
            color: var(--green-accent);
            background: rgba(74,170,37,0.12);
            border: 1px solid rgba(74,170,37,0.25);
            border-radius: 20px;
            padding: 3px 10px;
            letter-spacing: 0.04em;
        }

        .topbar-date {
            font-size: 12px;
            color: var(--green-muted);
        }

        /* ── CONTENT ── */
        .content {
            flex: 1;
            padding: 1.75rem;
            overflow-y: auto;
        }

        /* ── STATS GRID ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: rgba(12,28,14,0.7);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .stat-card:hover { border-color: var(--border-hover); }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 60px; height: 60px;
            border-radius: 50%;
            background: rgba(100,200,60,0.04);
            transform: translate(20px,-20px);
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-icon.green  { background: rgba(74,170,37,0.15); }
        .stat-icon.amber  { background: rgba(200,130,30,0.15); }
        .stat-icon.blue   { background: rgba(40,120,200,0.15); }
        .stat-icon.red    { background: rgba(200,60,40,0.15); }

        .stat-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--green-muted);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 600;
            color: #d8eeC8;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-sub {
            font-size: 11px;
            color: rgba(100,160,80,0.6);
        }

        /* ── GRID 2 COL ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 1.75rem;
        }

        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

        /* ── PANEL ── */
        .panel {
            background: rgba(12,28,14,0.7);
            border: 1px solid var(--border);
            border-radius: 14px;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            font-size: 13px;
            font-weight: 600;
            color: #b0d898;
            letter-spacing: 0.03em;
        }

        .panel-action {
            font-size: 11px;
            color: var(--green-accent);
            text-decoration: none;
            letter-spacing: 0.04em;
        }

        .panel-body { padding: 1rem 1.25rem; }

        /* ── ACTIVITY LIST ── */
        .activity-list { list-style: none; }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 9px 0;
            border-bottom: 1px solid rgba(100,180,60,0.07);
        }

        .activity-item:last-child { border-bottom: none; }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .dot-green  { background: #4aaa25; }
        .dot-amber  { background: #c8821e; }
        .dot-blue   { background: #2878c8; }
        .dot-red    { background: #c83c28; }

        .activity-text {
            flex: 1;
            font-size: 13px;
            color: #90b880;
            line-height: 1.4;
        }

        .activity-text strong { color: #c0e0a8; font-weight: 500; }

        .activity-time {
            font-size: 11px;
            color: rgba(100,150,80,0.5);
            flex-shrink: 0;
        }

        /* ── QUICK ACTIONS ── */
        .quick-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 14px 10px;
            background: rgba(100,180,60,0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.15s, border-color 0.15s;
            cursor: pointer;
        }

        .quick-btn:hover {
            background: rgba(100,200,60,0.1);
            border-color: var(--border-hover);
        }

        .quick-btn-icon {
            width: 36px;
            height: 36px;
            background: rgba(74,170,37,0.15);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quick-btn-label {
            font-size: 12px;
            font-weight: 500;
            color: #90b880;
            text-align: center;
            line-height: 1.3;
        }

        /* ── FOOTER ── */
        .footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: rgba(80,120,60,0.5);
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>

    <!-- ══════════════ SIDEBAR ══════════════ -->
    <aside class="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg width="22" height="22" viewBox="0 0 34 34" fill="none">
                    <path d="M17 4C17 4 10 10 10 18C10 22.4 13.1 26 17 26C20.9 26 24 22.4 24 18C24 10 17 4 17 4Z" fill="rgba(200,240,140,0.9)"/>
                    <path d="M17 26V31" stroke="rgba(200,240,140,0.7)" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="17" cy="18" r="2.5" fill="rgba(80,160,30,0.9)"/>
                </svg>
            </div>
            <div class="brand-text">
                <div class="brand-name">AgroField</div>
                <div class="brand-sub">Automação Agrícola</div>
            </div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section-label">Principal</div>

            <div class="nav-item">
                <div class="nav-trigger active" onclick="toggleMenu('menu-dash')">
                    <div class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1" width="6" height="6" rx="1.5" fill="#4aaa25"/>
                            <rect x="9" y="1" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                            <rect x="1" y="9" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                            <rect x="9" y="9" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                        </svg>
                    </div>
                    <span class="nav-label">Dashboard</span>
                </div>
            </div>

            <?php if ($tipo_login == "CEO"): ?>
            <div class="nav-section-label">Gestão</div>

            <div class="nav-item">
                <div class="nav-trigger" onclick="toggleMenu('menu-cad')">
                    <div class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="2" width="12" height="12" rx="2" stroke="#7aaa6a" stroke-width="1.2"/>
                            <path d="M5 8h6M8 5v6" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="nav-label">Cadastros</span>
                    <span class="nav-arrow" id="arrow-menu-cad">▾</span>
                </div>
                <div class="submenu" id="menu-cad">
                    <a href="cad_clientes.php">Clientes</a>
                    <a href="#">Usuários</a>
                    <a href="#">Produtos</a>
                </div>
            </div>
            <?php endif; ?>

            <div class="nav-section-label">Operações</div>

            <div class="nav-item">
                <div class="nav-trigger" onclick="toggleMenu('menu-rel')">
                    <div class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M2 12l3-4 3 2 3-5 3 3" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="nav-label">Relatórios</span>
                    <span class="nav-arrow" id="arrow-menu-rel">▾</span>
                </div>
                <div class="submenu" id="menu-rel">
                    <a href="#">Vendas</a>
                    <a href="#">Financeiro</a>
                    <a href="#">Estoque</a>
                </div>
            </div>

            <div class="nav-item">
                <div class="nav-trigger" onclick="toggleMenu('menu-cfg')">
                    <div class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="2" stroke="#7aaa6a" stroke-width="1.2"/>
                            <path d="M8 2v1M8 13v1M2 8h1M13 8h1M3.5 3.5l.7.7M11.8 11.8l.7.7M3.5 12.5l.7-.7M11.8 4.2l.7-.7" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="nav-label">Configurações</span>
                    <span class="nav-arrow" id="arrow-menu-cfg">▾</span>
                </div>
                <div class="submenu" id="menu-cfg">
                    <a href="#">Sistema</a>
                    <a href="#">Perfil</a>
                </div>
            </div>

        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($nome_usuario, 0, 2)); ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo $nome_usuario; ?></div>
                    <div class="user-role"><?php echo $tipo_login; ?></div>
                </div>
                <a class="logout-btn" href="logout.php" title="Sair">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M9 2h2a1 1 0 011 1v8a1 1 0 01-1 1H9" stroke="#f08070" stroke-width="1.2" stroke-linecap="round"/>
                        <path d="M6 10l3-3-3-3M9 7H2" stroke="#f08070" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>

    </aside>

    <!-- ══════════════ MAIN ══════════════ -->
    <div class="main">

        <header class="topbar">
            <span class="topbar-title">Dashboard</span>
            <span class="topbar-badge">Safra 2025/26</span>
            <span class="topbar-date" id="topbar-date"></span>
        </header>

        <div class="content">

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M9 2C9 2 5 6 5 10a4 4 0 008 0C13 6 9 2 9 2z" fill="rgba(74,170,37,0.8)"/>
                            <circle cx="9" cy="10" r="1.5" fill="#4aaa25"/>
                        </svg>
                    </div>
                    <div class="stat-label">Área Cultivada</div>
                    <div class="stat-value">2.480</div>
                    <div class="stat-sub">hectares ativos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <rect x="2" y="10" width="3" height="6" rx="1" fill="rgba(200,130,30,0.8)"/>
                            <rect x="7" y="7" width="3" height="9" rx="1" fill="rgba(200,130,30,0.8)"/>
                            <rect x="12" y="4" width="3" height="12" rx="1" fill="rgba(200,130,30,0.8)"/>
                        </svg>
                    </div>
                    <div class="stat-label">Produção Est.</div>
                    <div class="stat-value">14.260</div>
                    <div class="stat-sub">toneladas / safra</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="6" stroke="rgba(40,120,200,0.8)" stroke-width="1.4"/>
                            <path d="M9 6v4l2.5 1.5" stroke="rgba(40,120,200,0.8)" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="stat-label">Clientes Ativos</div>
                    <div class="stat-value">138</div>
                    <div class="stat-sub">+12 este mês</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M3 14l5-6 3 3 4-7" stroke="rgba(200,60,40,0.8)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="stat-label">Receita Mensal</div>
                    <div class="stat-value">R$ 84k</div>
                    <div class="stat-sub">↑ 8.3% vs. mês ant.</div>
                </div>
            </div>

            <!-- PANELS -->
            <div class="grid-2">

                <!-- Atividade Recente -->
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Atividade Recente</span>
                        <a href="#" class="panel-action">Ver todas →</a>
                    </div>
                    <div class="panel-body">
                        <ul class="activity-list">
                            <li class="activity-item">
                                <span class="activity-dot dot-green"></span>
                                <span class="activity-text"><strong>Pedido #4821</strong> confirmado — Fazenda São Luís</span>
                                <span class="activity-time">5 min</span>
                            </li>
                            <li class="activity-item">
                                <span class="activity-dot dot-amber"></span>
                                <span class="activity-text">Estoque de <strong>Herbicida X</strong> abaixo do mínimo</span>
                                <span class="activity-time">42 min</span>
                            </li>
                            <li class="activity-item">
                                <span class="activity-dot dot-blue"></span>
                                <span class="activity-text">Novo cliente <strong>Agropecuária Rios</strong> cadastrado</span>
                                <span class="activity-time">2 h</span>
                            </li>
                            <li class="activity-item">
                                <span class="activity-dot dot-green"></span>
                                <span class="activity-text">Relatório financeiro de <strong>Abril</strong> gerado</span>
                                <span class="activity-time">3 h</span>
                            </li>
                            <li class="activity-item">
                                <span class="activity-dot dot-red"></span>
                                <span class="activity-text">Sensor de irrigação <strong>Setor C4</strong> com falha</span>
                                <span class="activity-time">5 h</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Ações Rápidas</span>
                    </div>
                    <div class="panel-body">
                        <div class="quick-grid">
                            <a href="#" class="quick-btn">
                                <div class="quick-btn-icon">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <rect x="2" y="2" width="14" height="14" rx="2.5" stroke="#4aaa25" stroke-width="1.2"/>
                                        <path d="M9 5v8M5 9h8" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <span class="quick-btn-label">Novo Pedido</span>
                            </a>
                            
                            <?php
                                if($tipo_login == "CEO"){
                            ?>
                                <a href="cad_clientes.php" class="quick-btn">
                                    <div class="quick-btn-icon">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                            <circle cx="9" cy="6" r="3" stroke="#4aaa25" stroke-width="1.2"/>
                                            <path d="M3 15c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <span class="quick-btn-label">Cad. Cliente</span>
                                </a>
                            <?php
                                }
                            ?>

                            <a href="#" class="quick-btn">
                                <div class="quick-btn-icon">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path d="M3 14l4-5 3 3 4-6" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="quick-btn-label">Relatório</span>
                            </a>
                            <a href="#" class="quick-btn">
                                <div class="quick-btn-icon">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <circle cx="9" cy="9" r="3" stroke="#4aaa25" stroke-width="1.2"/>
                                        <path d="M9 2v2M9 14v2M2 9h2M14 9h2M3.9 3.9l1.4 1.4M12.7 12.7l1.4 1.4M3.9 14.1l1.4-1.4M12.7 5.3l1.4-1.4" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <span class="quick-btn-label">Configurar</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <footer class="footer">
            Sistema Agricolo v1.0 &nbsp;·&nbsp; Safra 2025/26 &nbsp;·&nbsp; Sistema de Automação Agrícola
        </footer>

    </div>

    <script>
        function toggleMenu(id) {
            var submenu = document.getElementById(id);
            var arrow   = document.getElementById('arrow-' + id);
            var trigger = submenu ? submenu.previousElementSibling : null;

            if (!submenu) return;

            var isOpen = submenu.classList.contains('open');
            submenu.classList.toggle('open', !isOpen);
            if (arrow)   arrow.classList.toggle('open', !isOpen);
            if (trigger) trigger.classList.toggle('active', !isOpen);
        }

        (function setDate() {
            var el = document.getElementById('topbar-date');
            if (!el) return;
            el.textContent = new Date().toLocaleDateString('pt-BR', {
                weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
            });
        })();
    </script>

</body>
</html>