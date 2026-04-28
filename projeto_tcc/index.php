<?php
require_once("conexao/conexao_banco.php");
date_default_timezone_set('America/Sao_Paulo');
session_start();

$erro = "";
if (isset($_SESSION['erro_login'])) {
    $erro = $_SESSION['erro_login'];
    unset($_SESSION['erro_login']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Agricolo — Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a1a0e;
            font-family: 'DM Sans', sans-serif;
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .bg-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(34,85,34,0.18) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(20,60,20,0.22) 0%, transparent 50%),
                radial-gradient(circle at 60% 10%, rgba(80,140,40,0.08) 0%, transparent 40%);
            pointer-events: none;
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(100,180,60,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100,180,60,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .card-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #2a6b2a, #4a9a1a);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 0 0 1px rgba(100,200,60,0.2), 0 8px 24px rgba(0,0,0,0.4);
        }

        .nome {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #e8f5e0;
            letter-spacing: 0.02em;
        }

        .sub_nome {
            font-size: 12px;
            color: #6a9a5a;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .card {
            background: rgba(12, 28, 14, 0.85);
            border: 1px solid rgba(100,180,60,0.15);
            border-radius: 20px;
            padding: 2.5rem;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 64px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.04);
        }

        .titulo {
            font-size: 13px;
            font-weight: 500;
            color: #6a9a5a;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1.8rem;
        }

        .field { margin-bottom: 1.25rem; }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #7aaa6a;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .field-wrap { position: relative; }

        .field-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            opacity: 0.5;
        }

        .field input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(100,180,60,0.15);
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            color: #d0e8c0;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .field input::placeholder { color: rgba(150,180,130,0.3); }

        .field input:focus {
            border-color: rgba(100,200,60,0.45);
            background: rgba(255,255,255,0.06);
        }

        .divider {
            height: 1px;
            background: rgba(100,180,60,0.1);
            margin: 1.8rem 0;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2d7a1a, #4aaa25);
            border: none;
            border-radius: 10px;
            color: #e8f5d8;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(60,160,30,0.25);
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-login:hover { opacity: 0.92; }
        .btn-login:active { transform: scale(0.99); }

        .erro_login {
            background: rgba(220,60,40,0.1);
            border: 1px solid rgba(220,80,60,0.3);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #f08070;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 11px;
            color: #3a5a30;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="grid-lines"></div>

    <div class="card-wrap">
        <div class="brand">
            <div class="brand-icon">
                <svg width="34" height="34" viewBox="0 0 34 34" fill="none">
                    <path d="M17 4C17 4 10 10 10 18C10 22.4 13.1 26 17 26C20.9 26 24 22.4 24 18C24 10 17 4 17 4Z" fill="rgba(200,240,140,0.9)"/>
                    <path d="M17 26V31" stroke="rgba(200,240,140,0.7)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M10 20C7 18 5 14 6 10" stroke="rgba(160,220,100,0.5)" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M24 20C27 18 29 14 28 10" stroke="rgba(160,220,100,0.5)" stroke-width="1.2" stroke-linecap="round"/>
                    <circle cx="17" cy="18" r="2.5" fill="rgba(80,160,30,0.9)"/>
                </svg>
            </div>
            <p class="nome"></p>
            <p class="sub_nome">Automação Agrícola</p>
        </div>

        <div class="card">
            <p class="titulo">Acesso ao sistema</p>
            
            <!-- IDENTIFICA SE TEM ERRO AO LOGAR - VITOR/28/04/2025 -->
            <?php 
                if ($erro != ""){ 
            ?>
                <div class="erro_login">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <circle cx="7" cy="7" r="6" stroke="#f08070" stroke-width="1.2"/>
                        <path d="M7 4v4M7 9.5v.5" stroke="#f08070" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <?php echo $erro ?>
                </div>
            <?php 
                } 
            ?>

            <form method="POST" action="valida_login.php">
                <div class="field">
                    <label>E-mail</label>
                    <div class="field-wrap">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="3" width="14" height="10" rx="2" stroke="#7aaa6a" stroke-width="1.2"/>
                            <path d="M1 5l7 5 7-5" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                        <input type="email" name="email" placeholder="seu@email.com.br" required>
                    </div>
                </div>

                <div class="field">
                    <label>Senha</label>
                    <div class="field-wrap">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="3" y="7" width="10" height="7" rx="1.5" stroke="#7aaa6a" stroke-width="1.2"/>
                            <path d="M5 7V5a3 3 0 016 0v2" stroke="#7aaa6a" stroke-width="1.2" stroke-linecap="round"/>
                            <circle cx="8" cy="10.5" r="1" fill="#7aaa6a"/>
                        </svg>
                        <input type="password" name="senha" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="divider"></div>

                <button type="submit" class="btn-login">Entrar no sistema</button>
            </form>
        </div>

        <p class="footer-note">Sistema Agricolo v1.0 · Safra 2025/26</p>
    </div>
</body>
</html>