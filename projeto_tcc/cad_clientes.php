<?php
session_start();
require_once("conexao/conexao_banco.php");

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
    <title>Sistema Agricolo — Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --topbar-h: 60px;
            --green-dark: #0a1a0e;
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
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #2a6b2a, #4a9a1a);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 0 1px rgba(100,200,60,0.2);
        }

        .brand-text .nome {
            font-family: 'Playfair Display', serif;
            font-size: 16px; color: #e8f5e0; line-height: 1.2;
        }

        .brand-text .sub_nome {
            font-size: 10px; color: var(--green-muted);
            letter-spacing: 0.1em; text-transform: uppercase;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0; }

        .nav-section-label {
            font-size: 10px; font-weight: 500;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: rgba(100,150,80,0.5);
            padding: 0.75rem 1.25rem 0.25rem;
        }

        .nav-item { position: relative; }

        .nav-trigger {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 1.25rem; cursor: pointer;
            transition: background 0.15s; user-select: none;
        }

        .nav-trigger:hover { background: rgba(100,200,60,0.06); }

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
            width: 32px; height: 32px;
            background: rgba(100,180,60,0.08);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .nav-label { flex: 1; font-size: 13px; font-weight: 500; color: #a0c890; }
        .nav-trigger.active .nav-label { color: #d0e8c0; }

        .nav-arrow { font-size: 10px; color: var(--green-muted); transition: transform 0.2s; }
        .nav-arrow.open { transform: rotate(180deg); }

        .submenu {
            display: none;
            background: rgba(0,0,0,0.15);
            border-left: 1px solid var(--border);
            margin-left: 1.25rem;
        }

        .submenu.open { display: block; }

        .submenu a {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 1rem; color: #7a9a6a;
            text-decoration: none; font-size: 13px;
            transition: color 0.15s, background 0.15s;
        }

        .submenu a::before {
            content: ''; width: 4px; height: 4px;
            border-radius: 50%; background: rgba(100,180,60,0.4); flex-shrink: 0;
        }

        .submenu a:hover { color: #d0e8c0; background: rgba(100,200,60,0.06); }
        .submenu a.current { color: #d0e8c0; }
        .submenu a.current::before { background: var(--green-accent); }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px;
            background: rgba(100,180,60,0.06);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2a6b2a, #4aaa25);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600; color: #e8f5d8; flex-shrink: 0;
        }

        .user-info .user-name {
            font-size: 13px; font-weight: 500; color: #c0e0a8;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;
        }

        .user-info .user-role { font-size: 11px; color: var(--green-muted); }

        .logout-btn {
            margin-left: auto; width: 28px; height: 28px;
            background: rgba(220,60,40,0.12);
            border: 1px solid rgba(220,80,60,0.25);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: background 0.15s; flex-shrink: 0;
        }

        .logout-btn:hover { background: rgba(220,60,40,0.25); }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            min-height: 100vh; position: relative; z-index: 1;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-h);
            background: rgba(8,20,10,0.7);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(8px);
            display: flex; align-items: center;
            padding: 0 1.75rem; gap: 12px;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-breadcrumb {
            display: flex; align-items: center; gap: 6px;
            flex: 1; font-size: 13px;
        }

        .topbar-breadcrumb .crumb-home {
            color: var(--green-muted); text-decoration: none;
            transition: color 0.15s;
        }

        .topbar-breadcrumb .crumb-home:hover { color: var(--green-text); }

        .topbar-breadcrumb .crumb-sep { color: rgba(100,150,80,0.4); font-size: 11px; }

        .topbar-breadcrumb .crumb-current {
            font-family: 'Playfair Display', serif;
            font-size: 16px; color: #e0f0d0;
        }

        .topbar-badge {
            font-size: 11px; font-weight: 500; color: var(--green-accent);
            background: rgba(74,170,37,0.12);
            border: 1px solid rgba(74,170,37,0.25);
            border-radius: 20px; padding: 3px 10px; letter-spacing: 0.04em;
        }

        /* ── CONTENT ── */
        .content {
            flex: 1; padding: 1.75rem;
            overflow-y: auto;
            display: flex; flex-direction: column; gap: 1.25rem;
        }

        /* ── PANEL ── */
        .panel {
            background: rgba(12,28,14,0.7);
            border: 1px solid var(--border);
            border-radius: 14px;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .panel-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }

        .panel-header-icon {
            width: 30px; height: 30px;
            background: rgba(74,170,37,0.12);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        .panel-title {
            font-size: 13px; font-weight: 600;
            color: #b0d898; letter-spacing: 0.03em; flex: 1;
        }

        .panel-body { padding: 1.5rem; }

        /* ── FORM ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 800px) { .form-grid { grid-template-columns: 1fr; } }

        .field { display: flex; flex-direction: column; gap: 6px; }

        .field label {
            font-size: 11px; font-weight: 500;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--green-soft);
        }

        .field-wrap { position: relative; }

        .field-wrap svg {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            pointer-events: none; opacity: 0.5;
        }

        .field input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px 12px 10px 38px;
            font-size: 13px; color: #d0e8c0;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .field input::placeholder { color: rgba(150,180,130,0.3); }

        .field input:focus {
            border-color: rgba(100,200,60,0.45);
            background: rgba(255,255,255,0.06);
        }

        .form-footer {
            margin-top: 1.25rem;
            display: flex; align-items: center; gap: 10px;
        }

        .btn-primary {
            padding: 10px 22px;
            background: linear-gradient(135deg, #2d7a1a, #4aaa25);
            border: none; border-radius: 9px;
            color: #e8f5d8;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 600;
            letter-spacing: 0.05em; text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(60,160,30,0.2);
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-primary:hover { opacity: 0.9; }
        .btn-primary:active { transform: scale(0.98); }

        .btn-secondary {
            padding: 10px 16px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--green-muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-secondary:hover { border-color: var(--border-hover); color: var(--green-text); }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%; border-collapse: collapse;
            font-size: 13px;
        }

        thead tr {
            border-bottom: 1px solid rgba(100,180,60,0.2);
        }

        thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px; font-weight: 500;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--green-muted);
        }

        tbody tr {
            border-bottom: 1px solid rgba(100,180,60,0.07);
            transition: background 0.15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(100,200,60,0.04); }

        tbody td {
            padding: 11px 14px;
            color: #90b880;
        }

        tbody td:first-child { color: #c0e0a8; font-weight: 500; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px; font-weight: 500;
            letter-spacing: 0.04em;
        }

        .badge-ceo {
            background: rgba(74,170,37,0.15);
            color: #70c040;
            border: 1px solid rgba(74,170,37,0.25);
        }

        .badge-default {
            background: rgba(100,150,80,0.1);
            color: var(--green-muted);
            border: 1px solid var(--border);
        }

        .table-empty {
            padding: 2rem; text-align: center;
            color: rgba(100,150,80,0.4); font-size: 13px;
        }

        /* ── FOOTER ── */
        .footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--border);
            font-size: 11px; color: rgba(80,120,60,0.5); letter-spacing: 0.04em;
        }

        .field select {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px 12px 10px 38px;
            font-size: 13px;
            color: #d0e8c0;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            appearance: none; /* remove estilo padrão do navegador */
        }

        .field select:focus {
            border-color: rgba(100,200,60,0.45);
            background: rgba(255,255,255,0.06);
        }

        .field-wrap::after {
            content: '▾';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--green-muted);
            pointer-events: none;
            font-size: 12px;
        }

        .field select option {
            background: #0a1a0e;
            color: #d0e8c0;
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
                <div class="nome">AgroField</div>
                <div class="sub_nome">Automação Agrícola</div>
            </div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section-label">Principal</div>

            <div class="nav-item">
                <div class="nav-trigger" onclick="toggleMenu('menu-dash')">
                    <div class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                            <rect x="9" y="1" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                            <rect x="1" y="9" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                            <rect x="9" y="9" width="6" height="6" rx="1.5" fill="rgba(74,170,37,0.4)"/>
                        </svg>
                    </div>
                    <span class="nav-label">Dashboard</span>
                </div>
            </div>

            <?php 
                if ($tipo_login == "CEO"){ 
            ?>
                <div class="nav-section-label">Gestão</div>

                <div class="nav-item">
                    <div class="nav-trigger active" onclick="toggleMenu('menu-cad')">
                        <div class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="2" y="2" width="12" height="12" rx="2" stroke="#4aaa25" stroke-width="1.2"/>
                                <path d="M5 8h6M8 5v6" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span class="nav-label">Cadastros</span>
                        <span class="nav-arrow open" id="arrow-menu-cad">▾</span>
                    </div>
                    <div class="submenu open" id="menu-cad">
                        <a href="cad_clientes.php" class="current">Clientes</a>
                        <a href="#">Usuários</a>
                        <a href="#">Produtos</a>
                    </div>
                </div>
            <?php 
                } 
            ?>

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

    <!-- DASHBOARD NO LADO ESQUERDO - VITOR 28/04/2026 -->
    <div class="main">

        <header class="topbar">
            <div class="topbar-breadcrumb">
                <a href="dashboard.php" class="crumb-home">Dashboard</a>
                <span class="crumb-sep">›</span>
                <span class="crumb-sep">Gestão</span>
                <span class="crumb-sep">›</span>
                <span class="crumb-current">Clientes</span>
            </div>
            <span class="topbar-badge">Safra 2025/26</span>
        </header>

        <div class="content">

            <!-- CADASTRO CLIENTES - VITOR 28/04/2026 -->
            <div class="panel">

                <div class="panel-header">
                    <div class="panel-header-icon">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="2" width="12" height="12" rx="2" stroke="#4aaa25" stroke-width="1.2"/>
                            <path d="M5 8h6M8 5v6" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <span class="panel-title">Novo Cliente</span>

                </div>

                <div class="panel-body">
                    <form autocomplete="off" class="form-valide" action="" method="post" novalidate="novalidate" id="formPrincipal" enctype="multipart/form-data">

                        <div class="form-grid">

                            <div class="field">

                                <label>Nome</label>

                                <div class="field-wrap">
                                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                                        <circle cx="8" cy="5.5" r="2.5" stroke="#7aaa6a" stroke-width="1.2"/>
                                        <path d="M2 13c0-3 2.7-5 6-5s6 2 6 5" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                    <input type="text" name="nome" id="nome" placeholder="Nome completo" required>
                                </div>

                            </div>

                            <div class="field">

                                <label>E-mail</label>

                                <div class="field-wrap">
                                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                                        <rect x="1" y="3" width="14" height="10" rx="2" stroke="#7aaa6a" stroke-width="1.2"/>
                                        <path d="M1 5l7 5 7-5" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                    <input type="email" name="email" id="email" placeholder="email@exemplo.com">
                                </div>

                            </div>

                            <div class="field">

                                <label>Senha</label>

                                <div class="field-wrap">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                                        <rect x="5" y="11" width="14" height="9" rx="2" stroke="#7aaa6a" stroke-width="1.5"/>
                                        <path d="M8 11V8a4 4 0 1 1 8 0v3" stroke="#7aaa6a" stroke-width="1.5"/>
                                    </svg>
                                    <input type="text" name="senha" id="senha" required>
                                </div>

                            </div>

                        </div>

                        <div class="form-grid" style="margin-top: 10px;">

                            <div class="field">

                                <label>Tipo de Login</label>

                                <div class="field-wrap">
                                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                                        <circle cx="8" cy="5.5" r="2.5" stroke="#7aaa6a" stroke-width="1.2"/>
                                        <path d="M2 13c0-3 2.7-5 6-5s6 2 6 5" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                    <select name="tipo_login" id="tipo_login" required>
                                        <option value="">Selecione...</option>
                                        <option value="CEO">CEO</option>
                                        <option value="Cliente">Cliente</option>
                                        <option value="Gerente">Gerente</option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="form-footer">
                            <button type="button" class="btn-primary" onclick="salvarCliente()">Salvar cliente</button>
                            <button type="reset" class="btn-secondary">Limpar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- LISTA DE CLIENTES CADASTRADOS - VITOR 28/04/2026-->
            <div class="panel">

                <div class="panel-header">
                    <div class="panel-header-icon">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                            <path d="M2 4h12M2 8h12M2 12h8" stroke="#4aaa25" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <span class="panel-title">Clientes Cadastrados</span>

                </div>

                <div class="panel-body" style="padding: 0;">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Tipo de acesso</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                                $sql   = "SELECT * FROM cad_usuarios WHERE cad_usuario_ativo_sn = 'S' ORDER BY cad_usuario_id ASC";
                                $query = mysqli_query($_SG['link'], $sql);

                                if (mysqli_num_rows($query) === 0):
                            ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="table-empty">Nenhum cliente cadastrado ainda.</div>
                                    </td>
                                </tr>
                            <?php
                                else:
                                while ($cliente = mysqli_fetch_assoc($query)):
                                    $tipo = htmlspecialchars($cliente['cad_usuario_tipo_login']);
                                    $badgeClass = ($tipo === 'CEO') ? 'badge-ceo' : 'badge-default';
                            ?>
                                <tr>
                                    <td><?php echo $cliente['cad_usuario_nome']; ?></td>
                                    <td><?php echo $cliente['cad_usuario_email']; ?></td>
                                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $tipo; ?></span></td>
                                </tr>
                            <?php
                                endwhile;
                                endif;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <footer class="footer">
            Sistema Agrigolo v1.0 &nbsp;·&nbsp; Safra 2025/26 &nbsp;·&nbsp; Sistema de Automação Agrícola
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

        function salvarCliente(){
            
            var nome = document.getElementById("nome").value;
            var email = document.getElementById("email").value;
            var senha = document.getElementById("senha").value;
            var tipo_login = document.getElementById("tipo_login").value;

            if(nome == ""){

            alert("preencher o campo (nome)");
            
            }else if(email == ""){
                
                alert("preencher o campo (email)");

            }else if(senha == ""){
                
                alert("preencher o campo (senha)");

            }else if(tipo_login == ""){
                
                alert("preencher o campo (tipo_login)");
                
            }else{

                  var form = document.getElementById("formPrincipal");
                  form.action = "grava_cad.php";
                  form.submit();
            }


                                

        }
    </script>

</body>
</html>