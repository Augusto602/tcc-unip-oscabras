@extends('layouts.app')

@section('title', 'Monitoramento do Robô')


@section('content')

<div class="robo-dashboard">

    {{--
        PORTA DE ENTRADA DA ROTA
        Contrato esperado do endpoint (ajuste os caminhos na configuração JS):
        GET  /monitoramento/rota
          { "rota_ativa": { "codigo": "mov-...", "veiculo_nome": "..." } | null,
            "veiculos_disponiveis": [{ "id": 1, "nome": "Veículo 1" }] }
        POST /monitoramento/rota  { "veiculo_id": 1 }
          { "rota": { "codigo": "mov-...", "veiculo_nome": "..." } }
    --}}
    <div class="route-gate" id="routeGate" role="dialog" aria-modal="true" aria-labelledby="routeGateTitle" hidden>
        <div class="route-gate-card">
            <div class="route-gate-icon"><i class="fa-solid fa-route"></i></div>
            <span class="route-gate-kicker">MONITORAMENTO</span>
            <h2 id="routeGateTitle">Nenhuma rota em execução</h2>
            <p id="routeGateMessage">Selecione o veículo para iniciar o monitoramento.</p>

            <form id="formNovaRota" hidden>
                <label for="selectVeiculo">Selecione o veículo</label>
                <select id="selectVeiculo" name="veiculo_id" required>
                    <option value="">Carregando veículos...</option>
                </select>
                <button type="submit" class="route-gate-primary" id="btnConfirmarRota">
                    <i class="fa-solid fa-route"></i> Iniciar rota
                </button>
            </form>

            <button type="button" class="route-gate-retry" id="btnTentarNovamente" hidden>
                Tentar novamente
            </button>

            <a class="route-gate-exit" href="{{ route('dashboard') }}">
                Voltar ao painel
            </a>
        </div>
    </div>

    {{-- Modal de encerramento da execução (independente das medições) --}}
    <div class="parada-modal" id="modalParada" role="dialog" aria-modal="true" aria-labelledby="modalParadaTitulo" aria-describedby="modalParadaDescricao" hidden>
        <div class="parada-modal-card" role="document">
            <button type="button" class="parada-modal-fechar" id="modalParadaFechar" aria-label="Fechar janela"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            <div class="parada-modal-icone" id="modalParadaIcone"><i class="fa-solid fa-stop" aria-hidden="true"></i></div>
            <span class="parada-modal-etiqueta" id="modalParadaEtiqueta">CONTROLE DA EXECUÇÃO</span>
            <h2 id="modalParadaTitulo">Encerrar rota?</h2>
            <p id="modalParadaDescricao">Deseja realmente abortar esta execução? O registro será marcado como abortado no sistema.</p>
            <p class="parada-modal-observacao" id="modalParadaObservacao">Isso não confirma a parada física dos motores do robô.</p>
            <div class="parada-modal-acoes">
                <button type="button" class="parada-modal-cancelar" id="modalParadaCancelar">Cancelar</button>
                <button type="button" class="parada-modal-confirmar" id="modalParadaConfirmar"><i class="fa-solid fa-stop" aria-hidden="true"></i> Encerrar rota</button>
                <button type="button" class="parada-modal-ok" id="modalParadaOk" hidden>Entendi</button>
            </div>
        </div>
    </div>

    {{-- =========================================================
         CABEÇALHO
    ========================================================== --}}

    <div class="robo-header">

        <div class="robo-header-left">

            <div class="robo-title-icon">
                <i class="fa-solid fa-robot"></i>
            </div>

            <div>
                <h2>Monitoramento do Robô</h2>

                <p>
                    Acompanhamento da operação no campo agrícola
                </p>
            </div>

        </div>


        <div class="robo-header-right">

            <div class="robo-controls">

                <button type="button" class="robo-btn start" id="btnIniciar" title="Reproduzir trajeto no mapa (não inicia o robô físico)">
                    <i class="fa-solid fa-play"></i>
                    <span>Iniciar</span>
                </button>

                <button type="button" class="robo-btn stop" id="btnParar" title="Abortar a execução no banco (não confirma parada física)">
                    <i class="fa-solid fa-stop"></i>
                    <span>Parar</span>
                </button>

                <button type="button" class="robo-btn" id="btnVelocidade" title="Velocidade da reprodução: 1x. Clique para acelerar." aria-label="Velocidade da reprodução: 1x. Clique para acelerar."><i class="fa-solid fa-forward" aria-hidden="true"></i> <b id="valorVelocidade">1x</b></button>

                <button type="button" class="robo-btn reset" id="btnReiniciar" title="Reiniciar a reprodução no mapa">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reiniciar</span>
                </button>

            </div>


            <div class="robo-online">

                <span class="online-dot"></span>

                ROBÔ ONLINE

            </div>

        </div>

    </div>


    {{-- =========================================================
         INDICADORES
    ========================================================== --}}

    <div class="robo-stats">

        {{-- POSIÇÃO X --}}
        <div class="robo-stat">

            <div class="stat-icon green">
                <i class="fa-solid fa-arrows-left-right"></i>
            </div>

            <div class="stat-info">

                <span>Posição X</span>

                <strong id="posicaoX">0.000000</strong>

                <small>coordenada local estimada</small>

            </div>

        </div>


        {{-- POSIÇÃO Y --}}
        <div class="robo-stat">

            <div class="stat-icon blue">
                <i class="fa-solid fa-arrows-up-down"></i>
            </div>

            <div class="stat-info">

                <span>Posição Y</span>

                <strong id="posicaoY">0.000000</strong>

                <small>coordenada local estimada</small>

            </div>

        </div>


        {{-- DISTÂNCIA --}}
        <div class="robo-stat">

            <div class="stat-icon orange">
                <i class="fa-solid fa-route"></i>
            </div>

            <div class="stat-info">

                <span>Distância</span>

                <strong id="distancia">0.0</strong>

                <small>unidades X/Y (estimativa)</small>

            </div>

        </div>


        {{-- DIREÇÃO --}}
        <div class="robo-stat">

            <div class="stat-icon purple">
                <i class="fa-solid fa-compass"></i>
            </div>

            <div class="stat-info">

                <span>Direção</span>

                <strong id="direcao">90°</strong>

                <small>orientação</small>

            </div>

        </div>


        {{-- VELOCIDADE --}}
        <div class="robo-stat">

            <div class="stat-icon cyan">
                <i class="fa-solid fa-gauge-high"></i>
            </div>

            <div class="stat-info">

                <span>Velocidade</span>

                <strong id="velocidade">3.8</strong>

                <small>km/h</small>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ÁREA PRINCIPAL
    ========================================================== --}}

    <div id="avisoMedicoes" role="status" aria-live="polite" hidden style="margin: 0 0 12px; padding: 12px 15px; border: 1px solid #7a5c32; border-radius: 9px; background: #342719; color: #ffca8b; font-size: 13px;"></div>

    <div class="robo-content">


        {{-- =====================================================
             MAPA
        ====================================================== --}}

        <div class="map-panel">

            <div class="panel-header">

                <div>

                    <h3>

                        <i class="fa-solid fa-map-location-dot"></i>

                        Área de operação

                    </h3>

                    <span>
                        Trajeto do robô e objetos identificados
                    </span>

                </div>


                <div class="map-status">

                    <span class="gps-dot"></span>

                    POSIÇÃO ESTIMADA 

                </div>

            </div>


            <div class="map-wrapper">

                <canvas id="mapaRobo"></canvas>


                {{-- LEGENDA --}}

                <div class="map-legend">

                    <div>
                        <span class="legend robot"></span>
                        Robô
                    </div>

                    <div>
                        <span class="legend route"></span>
                        Trajeto
                    </div>

                    <div>
                        <span class="legend obstacle"></span>
                        Objeto
                    </div>

                    <div>
                        <span class="legend start"></span>
                        ORIGEM
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PAINEL LATERAL
        ====================================================== --}}

        <div class="side-panel">


            {{-- =================================================
                 CLIMA
            ================================================== --}}

            <div class="info-panel">

                <div class="info-panel-title">

                    <i class="fa-solid fa-cloud-sun"></i>

                    <span>Condições ambientais</span>

                </div>


                <div class="environment-grid">


                    <div class="environment-item">

                        <i class="fa-solid fa-temperature-half"></i>

                        <span>Temperatura</span>

                        <strong id="temperatura">
                            24.7°C
                        </strong>

                    </div>


                    <div class="environment-item">

                        <i class="fa-solid fa-droplet"></i>

                        <span>Umidade</span>

                        <strong id="umidade">
                            --
                        </strong>

                    </div>

                    

                </div>

            </div>


            {{-- =================================================
                 OBJETOS DETECTADOS
            ================================================== --}}

            <div class="info-panel">

                <div class="info-panel-title">

                    <i class="fa-solid fa-eye"></i>

                    <span>Objetos detectados</span>

                    <span
                        class="detection-count"
                        id="quantidadeObjetos"
                    >
                        3
                    </span>

                </div>


                <div class="detections" id="listaDeteccoes" aria-live="polite">
                    <div class="detection"><div><strong>Aguardando detecções</strong><span>Os objetos serão carregados da execução selecionada.</span></div></div>
                </div>
            </div>


            {{-- =================================================
                 SISTEMA
            ================================================== --}}

            <div class="info-panel">

                <div class="info-panel-title">

                    <i class="fa-solid fa-microchip"></i>

                    <span>Status do sistema</span>

                </div>


                <div class="system-row">

                    <span>

                        <i class="fa-solid fa-wifi"></i>

                        Comunicação

                    </span>

                    <strong class="status-ok">
                        Estável
                    </strong>

                </div>


                <div class="system-row">

                    <span>

                        <i class="fa-solid fa-location-crosshairs"></i>

                        Localização

                    </span>

                    <strong class="status-ok">
                        Estimadas (sem GPS)
                    </strong>

                </div>


                <div class="system-row">

                    <span>

                        <i class="fa-solid fa-clock"></i>

                        Atualização

                    </span>

                    <strong id="ultimaAtualizacao">
                        22:40:32
                    </strong>

                </div>


                <div class="system-row">

                    <span>

                        <i class="fa-solid fa-circle-play"></i>

                        Estado do robô

                    </span>

                    <strong id="estadoRobo" class="status-ok">
                        Parado
                    </strong>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection



{{-- =============================================================
     CSS
============================================================= --}}

@section('styles')

<style>

/* =========================================================
   PÁGINA / SCROLL
========================================================= */

html,
body {
    min-height: 100%;
    height: auto;
}

body {
    overflow-y: auto !important;
}

.content-map {
    overflow: visible !important;
    height: auto !important;
    min-height: 0 !important;
}


/* =========================================================
   DASHBOARD
========================================================= */

/* Modal de confirmação da parada: tema do painel, sem alert/confirm do navegador. */
.parada-modal { position: fixed; inset: 0; z-index: 11000; display: flex; align-items: center; justify-content: center; padding: 18px; background: rgba(5, 12, 9, .82); backdrop-filter: blur(6px); }
.parada-modal[hidden], .parada-modal [hidden] { display: none !important; }
.parada-modal-card { position: relative; width: min(100%, 430px); box-sizing: border-box; padding: 32px 28px 26px; text-align: center; color: #eaf3ed; background: linear-gradient(155deg, #1d2a25, #121d18); border: 1px solid #385243; border-radius: 17px; box-shadow: 0 24px 70px rgba(0,0,0,.55); }
.parada-modal-fechar { position: absolute; top: 14px; right: 14px; padding: 7px 10px; background: transparent; color: #9daea4; border: 0; border-radius: 7px; cursor: pointer; font-size: 15px; }
.parada-modal-fechar:hover { background: #2c3b32; color: #fff; }
.parada-modal-icone { width: 64px; height: 64px; margin: 0 auto 18px; display: grid; place-items: center; font-size: 23px; color: #ffb17e; background: #422b21; border: 1px solid #79513c; border-radius: 17px; }
.parada-modal-icone.sucesso { color: #79dd90; background: #1c3b29; border-color: #346c49; }
.parada-modal-icone.erro { color: #ff988e; background: #432523; border-color: #834743; }
.parada-modal-etiqueta { color: #91b49f; font-size: 10px; font-weight: 800; letter-spacing: 1.5px; }
.parada-modal-card h2 { margin: 10px 0 10px; font-size: 23px; font-weight: 750; color: #f4faf6; }
.parada-modal-card p { margin: 0; color: #b3c2b7; font-size: 13px; line-height: 1.65; overflow-wrap: anywhere; }
.parada-modal-card .parada-modal-observacao { margin-top: 12px; color: #e9b583; font-size: 11px; }
.parada-modal-acoes { display: flex; gap: 10px; margin-top: 25px; }
.parada-modal-acoes button { min-height: 45px; flex: 1; border-radius: 9px; border: 1px solid transparent; padding: 10px 14px; font-weight: 750; font-size: 12px; cursor: pointer; }
.parada-modal-cancelar { color: #d5e0d8; background: #25352d; border-color: #3b5142 !important; }
.parada-modal-confirmar { color: #fff1e8; background: #a74e35; border-color: #c56847 !important; }
.parada-modal-ok { color: #112218; background: #78d58c; }
.parada-modal-acoes button:hover:not(:disabled) { filter: brightness(1.1); }
.parada-modal-acoes button:disabled { opacity: .6; cursor: wait; }
@media (max-width: 480px) { .parada-modal-card { padding: 29px 19px 21px; } .parada-modal-acoes { flex-direction: column-reverse; } }

.robo-dashboard {
    width: 100%;
    min-height: auto;
    padding: 22px 25px 60px;
    box-sizing: border-box;
    color: #e6eee9;
    overflow: visible;
}

.route-gate {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(7, 14, 11, .88);
    backdrop-filter: blur(7px);
}

.route-gate[hidden] { display: none; }

.route-gate-card {
    width: min(100%, 440px);
    padding: 32px;
    text-align: center;
    background: linear-gradient(160deg, #1b2925, #121c19);
    border: 1px solid #315b46;
    border-radius: 16px;
    box-shadow: 0 20px 70px rgba(0,0,0,.45);
}

.route-gate-icon {
    width: 58px;
    height: 58px;
    display: grid;
    place-items: center;
    margin: 0 auto 16px;
    border-radius: 15px;
    color: #77d782;
    background: #193629;
    font-size: 23px;
}

.route-gate-kicker {
    color: #77d782;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.route-gate-card h2 { margin: 9px 0; color: #eef5f1; font-size: 22px; }
.route-gate-card p { margin: 0 0 22px; color: #9aada4; font-size: 13px; line-height: 1.6; }
.route-gate-card form { margin-top: 20px; text-align: left; }
.route-gate-card label { display: block; margin-bottom: 8px; color: #cbd8d1; font-size: 12px; font-weight: 700; }
.route-gate-card select {
    width: 100%;
    padding: 12px;
    color: #eef5f1;
    background: #101a17;
    border: 1px solid #3a594b;
    border-radius: 8px;
    margin-bottom: 12px;
}
.route-gate-card select:focus { outline: 2px solid #69c174; outline-offset: 2px; }
.route-gate-primary, .route-gate-retry {
    min-height: 43px;
    padding: 11px 18px;
    color: #0e1a12;
    background: #72d17c;
    border: 0;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
}
.route-gate-card > .route-gate-primary { width: 100%; }
.route-gate-card form .route-gate-primary { width: 100%; }
.route-gate-primary:disabled { opacity: .55; cursor: wait; }
.route-gate-retry { margin-top: 12px; color: #dce9e1; background: #253a30; }
.route-gate-exit {
    display: inline-block;
    margin-top: 16px;
    color: #aebfb6;
    font-size: 12px;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.route-gate-exit:hover { color: #ffffff; }

@media (max-width: 520px) {
    .route-gate-card { padding: 25px 20px; }
    .route-gate-card h2 { font-size: 19px; }
}

.main-map {
    height: auto !important;
    min-height: 100vh;
    overflow: visible !important;
}

.agro-dashboard {
    height: auto !important;
    min-height: 100vh;
    overflow: visible !important;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.robo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 22px;
}

.robo-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.robo-header-right {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.robo-title-icon {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #1d392d;
    border: 1px solid #315b46;
    border-radius: 11px;

    color: #69c174;
    font-size: 21px;

    box-shadow: 0 4px 15px rgba(0,0,0,.18);
}

.robo-header-left h2 {
    margin: 0;

    color: #eef5f1;

    font-size: 23px;
    font-weight: 600;
}

.robo-header-left p {
    margin: 5px 0 0;

    color: #8fa39a;

    font-size: 13px;
}


/* =========================================================
   CONTROLES DO ROBÔ (Iniciar / Parar / Reiniciar)
========================================================= */

.robo-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.robo-btn {
    display: flex;
    align-items: center;
    gap: 7px;

    padding: 10px 16px;

    border-radius: 9px;
    border: 1px solid transparent;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;

    transition: .2s;

    box-shadow: 0 4px 15px rgba(0,0,0,.15);
}

.robo-btn i {
    font-size: 12px;
}

.robo-btn.start {
    background: #17351f;
    border-color: #2f6b3d;
    color: #6de07c;
}

.robo-btn.start:hover:not(:disabled) {
    background: #1e4527;
    transform: translateY(-1px);
}

.robo-btn.stop {
    background: #3a2216;
    border-color: #78432a;
    color: #ffab63;
}

.robo-btn.stop:hover:not(:disabled) {
    background: #4a2c1c;
    transform: translateY(-1px);
}

.robo-btn.reset {
    background: #201c38;
    border-color: #453d78;
    color: #b7a9ff;
}

.robo-btn.reset:hover:not(:disabled) {
    background: #2a2448;
    transform: translateY(-1px);
}

.robo-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
    transform: none !important;
}


/* =========================================================
   STATUS ONLINE
========================================================= */

.robo-online {
    display: flex;
    align-items: center;
    gap: 8px;

    padding: 9px 14px;

    background: #183125;
    border: 1px solid #28533a;

    border-radius: 20px;

    color: #71d17d;

    font-size: 10px;
    font-weight: 700;

    box-shadow: 0 4px 15px rgba(0,0,0,.15);
}

.online-dot {
    width: 8px;
    height: 8px;

    background: #55c965;

    border-radius: 50%;

    box-shadow: 0 0 10px rgba(85, 201, 101, .8);
}


/* =========================================================
   INDICADORES (DESTACADOS)
========================================================= */

.robo-stats {
    display: grid;

    grid-template-columns:
        repeat(5, minmax(130px, 1fr));

    gap: 14px;

    margin-bottom: 20px;
}

.robo-stat {
    min-height: 100px;

    display: flex;
    align-items: center;

    gap: 13px;

    padding: 16px;

    background: linear-gradient(160deg, #1b2925, #15211d);

    border: 1px solid #315b46;

    border-radius: 11px;

    box-sizing: border-box;

    transition: .2s;

    box-shadow: 0 4px 15px rgba(0,0,0,.15), inset 0 0 0 1px rgba(105,193,116,.03);
}

.robo-stat:hover {
    border-color: #69c174;

    transform: translateY(-2px);

    box-shadow: 0 7px 22px rgba(0,0,0,.22), 0 0 0 1px rgba(105,193,116,.15);
}


/* =========================================================
   ÍCONES DOS INDICADORES
========================================================= */

.stat-icon {
    width: 45px;
    height: 45px;

    min-width: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 18px;

    box-shadow: 0 0 0 1px rgba(255,255,255,.04) inset;
}


/* Verde */

.stat-icon.green {
    background: #193629;
    color: #7bdc86;
}


/* Azul */

.stat-icon.blue {
    background: #182e38;
    color: #6ccbef;
}


/* Laranja */

.stat-icon.orange {
    background: #38291b;
    color: #f2b46e;
}


/* Roxo */

.stat-icon.purple {
    background: #2d2338;
    color: #c79cf2;
}


/* Ciano */

.stat-icon.cyan {
    background: #173239;
    color: #74dbea;
}


/* Bateria */

    .stat-icon.battery {
        background: #24331f;
        color: #a4e07d;
    }


/* =========================================================
   TEXTO DOS INDICADORES (MAIS DESTACADO)
========================================================= */

    .stat-info {
        min-width: 0;
    }

    .stat-info span {
        display: block;

        color: #9db3aa;

        font-size: 11px;

        font-weight: 600;

        letter-spacing: .3px;

        text-transform: uppercase;

        margin-bottom: 6px;

        white-space: nowrap;
    }

    .stat-info strong {
        display: inline-block;

        color: #ffffff;

        font-size: 25px;

        font-weight: 800;

        text-shadow: 0 0 18px rgba(105,193,116,.25);

        letter-spacing: .3px;
    }

    #posicaoX,
    #posicaoY {
        font-size: 18px;
        letter-spacing: 0;
    }

    .stat-info small {
        color: #86988f;

        font-size: 10px;

        font-weight: 600;

        margin-left: 4px;
    }


/* =========================================================
   DISTÂNCIA
========================================================= */

#distancia {
    color: #ffffff;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

@media (max-width: 700px) {

    .robo-stat:nth-child(3) #distancia {
        font-size: 18px;
    }

}


/* =========================================================
   MAPA + PAINEL LATERAL
========================================================= */

    .robo-content {
        display: grid;

        grid-template-columns:
            minmax(0, 3fr)
            minmax(280px, 1fr);

        gap: 18px;

        width: 100%;
    }


/* =========================================================
   PAINEL DO MAPA
========================================================= */

    .map-panel {
        min-width: 0;
        /* Nao esticar o painel para acompanhar a coluna lateral. */
        align-self: start;

        background: #17221f;

        border: 1px solid #2c423a;

        border-radius: 11px;

        overflow: hidden;

        box-shadow: 0 5px 20px rgba(0,0,0,.15);
    }


/* =========================================================
   CABEÇALHO DO MAPA
========================================================= */

    .panel-header {
        min-height: 62px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        padding: 12px 17px;

        background: #192622;

        border-bottom: 1px solid #293c35;
    }

    .panel-header h3 {
        margin: 0;

        color: #e9f1ed;

        font-size: 15px;

        font-weight: 600;
    }

    .panel-header h3 i {
        margin-right: 7px;

        color: #69c174;
    }

    .panel-header span {
        display: block;

        margin-top: 5px;

        color: #7e9389;

        font-size: 10px;
    }


/* =========================================================
   STATUS GPS
========================================================= */

    .map-status {
        display: flex;
        align-items: center;
        gap: 6px;

        padding: 6px 10px;

        background: #172e24;

        border: 1px solid #285239;

        border-radius: 15px;

        color: #6bc978;

        font-size: 9px;

        font-weight: 700;
    }

    .gps-dot {
        width: 7px;
        height: 7px;

        background: #55c965;

        border-radius: 50%;

        box-shadow: 0 0 7px rgba(85,201,101,.7);
    }


/* =========================================================
   ÁREA DO MAPA
========================================================= */

    .map-wrapper {
        position: relative;

        width: 100%;

        height: 555px;

        background: #101a17;

        overflow: hidden;
    }

    #mapaRobo {
        display: block;

        width: 100%;
        height: 100%;
    }


/* =========================================================
   LEGENDA
========================================================= */

    .map-legend {
        position: absolute;

        left: 15px;
        bottom: 15px;

        display: flex;
        flex-wrap: wrap;

        gap: 10px;

        padding: 9px 12px;

        background: rgba(16, 26, 23, .96);

        border: 1px solid #30443c;

        border-radius: 8px;

        color: #aabbb3;

        font-size: 9px;

        box-shadow: 0 4px 15px rgba(0,0,0,.25);
    }

    .map-legend > div {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .legend {
        width: 8px;
        height: 8px;

        display: inline-block;

        border-radius: 50%;
    }

    .legend.robot {
        background: #5bd36b;
    }

    .legend.route {
        width: 15px;
        height: 3px;

        background: #54a96a;

        border-radius: 0;
    }

    .legend.obstacle {
        background: #e05d5d;
    }

    .legend.start {
        background: #8d9893;
    }


/* =========================================================
   PAINEL DIREITO
========================================================= */

.side-panel {
    display: flex;

    flex-direction: column;

    gap: 14px;

    min-width: 0;
}


/* =========================================================
   PAINÉIS DE INFORMAÇÃO
========================================================= */

.info-panel {
    background: #17221f;

    border: 1px solid #2c423a;

    border-radius: 10px;

    padding: 15px;

    box-shadow: 0 4px 15px rgba(0,0,0,.12);
}


/* =========================================================
   TÍTULO DOS PAINÉIS
========================================================= */

.info-panel-title {
    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 13px;

    color: #dce8e2;

    font-size: 12px;

    font-weight: 600;
}

.info-panel-title i {
    color: #69c174;

    font-size: 13px;
}


/* =========================================================
   CLIMA (DESTACADO)
========================================================= */

.environment-grid {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 10px;
}

.environment-item {
    display: grid;

    grid-template-columns:
        25px 1fr;

    align-items: center;

    padding: 11px;

    background: #121c19;

    border: 1px solid #315b46;

    border-radius: 8px;

    min-height: 50px;
}

.environment-item i {
    grid-row: span 2;

    color: #7fd1de;

    font-size: 14px;
}

.environment-item span {
    color: #8ca097;

    font-size: 9px;

    font-weight: 600;

    text-transform: uppercase;

    margin-bottom: 3px;
}

.environment-item strong {
    color: #ffffff;

    font-size: 15px;

    font-weight: 800;
}


/* =========================================================
   DETECÇÕES
========================================================= */

.detection-count {
    margin-left: auto;

    min-width: 22px;
    height: 22px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #263e32;

    border-radius: 50%;

    color: #72cc7c;

    font-size: 9px;
}

.detections {
    display: flex;

    flex-direction: column;

    gap: 9px;
}

.detection {
    display: flex;

    align-items: center;

    gap: 11px;

    padding: 10px;

    background: #121c19;

    border: 1px solid #2b3d36;

    border-radius: 8px;
}

.detection-icon {
    width: 34px;
    height: 34px;

    min-width: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    font-size: 13px;
}

.detection-icon.tree {
    background: #193628;
    color: #69c174;
}

.detection-icon.warning {
    background: #392b1d;
    color: #e2a357;
}

.detection-icon.water {
    background: #192f39;
    color: #62bcd3;
}

.detection strong {
    display: block;

    color: #eef5f0;

    font-size: 12px;

    font-weight: 700;

    margin-bottom: 3px;
}

.detection span {
    display: block;

    color: #71847b;

    font-size: 9px;
}



/* Rolagem independente para muitos objetos, sem aumentar o painel lateral. */
#listaDeteccoes {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
    overscroll-behavior: contain;
    scrollbar-width: thin;
    scrollbar-color: #456a54 #121c19;
    padding-right: 5px;
    align-content: start;
}
#listaDeteccoes::-webkit-scrollbar { width: 7px; }
#listaDeteccoes::-webkit-scrollbar-track { background: #121c19; border-radius: 8px; }
#listaDeteccoes::-webkit-scrollbar-thumb { background: #456a54; border-radius: 8px; }
#listaDeteccoes::-webkit-scrollbar-thumb:hover { background: #69c174; }

/* Melhoria isolada: apenas os cartões de objetos detectados. */
#listaDeteccoes .detection {
    display: block;
    padding: 13px;
    border-radius: 10px;
    min-width: 0;
}
#listaDeteccoes .detection-header {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    margin-bottom: 11px;
}
#listaDeteccoes .detection-icon { flex-shrink: 0; }
#listaDeteccoes .detection-name {
    flex: 1;
    min-width: 0;
    font-size: 14px;
    line-height: 1.35;
    margin: 0;
    overflow-wrap: anywhere;
}
#listaDeteccoes .detection-confidence {
    display: inline-flex;
    flex-shrink: 0;
    padding: 5px 7px;
    border-radius: 6px;
    border: 1px solid #80551e;
    background: #3b2d1a;
    color: #ffca85;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}
#listaDeteccoes .detection-metadata {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
}
#listaDeteccoes .detection-field {
    min-width: 0;
    padding: 9px 10px;
    background: #172620;
    border: 1px solid #304d3e;
    border-radius: 7px;
}
#listaDeteccoes .detection-field.is-wide { grid-column: 1 / -1; }
#listaDeteccoes .detection-field-label {
    display: block;
    font-size: 11px;
    line-height: 1.4;
    color: #a9c3b5;
    margin-bottom: 4px;
}
#listaDeteccoes .detection-field-value {
    display: block;
    font-size: 13px;
    line-height: 1.45;
    font-weight: 700;
    color: #f0f8f2;
    overflow-wrap: anywhere;
    font-variant-numeric: tabular-nums;
}
@media (max-width: 700px) {
    #listaDeteccoes .detection { padding: 12px; }
    #listaDeteccoes .detection-name { font-size: 14px; }
    #listaDeteccoes .detection-field-label { font-size: 11px; }
    #listaDeteccoes .detection-field-value { font-size: 13px; }
}

/* =========================================================
   SISTEMA
========================================================= */

.system-row {
    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 10px 2px;

    border-bottom: 1px solid #25352f;

    font-size: 10px;
}

.system-row:last-child {
    border-bottom: none;
}

.system-row span {
    color: #879b92;
}

.system-row span i {
    width: 20px;

    color: #628f73;
}

.status-ok {
    color: #69c878;

    font-size: 11px;

    font-weight: 700;
}

.status-paused {
    color: #ffab63;

    font-size: 11px;

    font-weight: 700;
}

#ultimaAtualizacao {
    color: #cfe0d7;

    font-weight: 700;
}


/* =========================================================
   RESPONSIVIDADE
========================================================= */

@media (max-width: 1300px) {

    .robo-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .robo-content {
        grid-template-columns:
            minmax(0, 2.5fr)
            minmax(260px, 1fr);

        gap: 16px;
    }

}


@media (max-width: 1000px) {

    .robo-dashboard {
        padding: 20px 20px 50px;
    }

    .robo-content {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .robo-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .map-wrapper {
        height: 500px;
    }

    .side-panel {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .side-panel .info-panel:last-child {
        grid-column: 1 / -1;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    .robo-dashboard {
        width: 100%;
        padding: 14px 0 36px;
        box-sizing: border-box;
        overflow: hidden;
    }

    /* CABEÇALHO */

    .robo-header {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 14px;
        padding-left: 10px;
        padding-right: 10px;
        box-sizing: border-box;
    }

    .robo-header-left {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .robo-header-left > div:last-child {
        min-width: 0;
    }

    .robo-title-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        font-size: 17px;
        border-radius: 10px;
    }

    .robo-header-left h2 {
        font-size: 18px;
        line-height: 1.25;
    }

    .robo-header-left p {
        margin-top: 3px;
        font-size: 10px;
        line-height: 1.4;
    }

    /* CONTROLES */

    .robo-header-right {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }

    .robo-controls {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 7px;
    }

    .robo-btn {
        width: 100%;
        min-width: 0;
        height: 41px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .robo-btn span {
        display: none;
    }

    .robo-btn i {
        font-size: 12px;
    }

    #btnVelocidade {
        font-size: 11px;
        white-space: nowrap;
    }

    .robo-online {
        align-self: flex-start;
        padding: 6px 10px;
        font-size: 8px;
    }

    .online-dot {
        width: 7px;
        height: 7px;
        min-width: 7px;
    }

    /* INDICADORES */

    .robo-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin: 0 10px 14px;
    }

    .robo-stat {
        min-width: 0;
        min-height: 76px;
        padding: 11px;
        gap: 9px;
        border-radius: 9px;
        overflow: hidden;
    }

    .robo-stat:nth-child(5) {
        grid-column: 1 / -1;
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        font-size: 14px;
    }

    .stat-info {
        min-width: 0;
        overflow: hidden;
    }

    .stat-info span {
        margin-bottom: 3px;
        font-size: 8px;
        white-space: normal;
    }

    .stat-info strong {
        display: block;
        max-width: 100%;
        font-size: 17px;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    #posicaoX,
    #posicaoY {
        font-size: 13px;
        word-break: break-all;
    }

    .stat-info small {
        display: block;
        margin: 3px 0 0;
        font-size: 7px;
        line-height: 1.3;
    }

    /* ÁREA PRINCIPAL */

    .robo-content {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 12px;
    }

    /* MAPA */

    .map-panel {
        width: 100%;
        max-width: none;
        min-width: 0;
        margin: 0;
        border-left: 0;
        border-right: 0;
        border-radius: 0;
        overflow: hidden;
    }

    .panel-header {
        min-height: 54px;
        padding: 10px 12px;
        gap: 8px;
    }

    .panel-header > div:first-child {
        min-width: 0;
    }

    .panel-header h3 {
        font-size: 13px;
        line-height: 1.35;
    }

    .panel-header span {
        margin-top: 3px;
        font-size: 8px;
        line-height: 1.35;
    }

    .map-status {
        flex-shrink: 0;
        padding: 5px 7px;
        font-size: 7px;
    }

    .gps-dot {
        width: 6px;
        height: 6px;
        min-width: 6px;
    }

    .map-wrapper {
        width: 100%;
        height: 365px;
        min-height: 365px;
    }

    #mapaRobo {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    .map-legend {
        left: 8px;
        right: 8px;
        bottom: 8px;
        width: auto;
        display: grid;
        grid-template-columns: repeat(2, auto);
        justify-content: start;
        gap: 5px 10px;
        padding: 7px 9px;
        font-size: 7px;
    }

    /* PAINÉIS ABAIXO */

    .side-panel {
        display: flex;
        flex-direction: column;
        width: auto;
        margin: 0 10px;
        gap: 10px;
    }

    .info-panel {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
    }

    .info-panel-title {
        margin-bottom: 10px;
        font-size: 12px;
    }

    .info-panel-title i {
        font-size: 13px;
    }

    /* AMBIENTE */

    .environment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
    }

    .environment-item {
        min-width: 0;
        min-height: 55px;
        padding: 9px;
        grid-template-columns: 22px minmax(0, 1fr);
    }

    .environment-item i {
        font-size: 12px;
    }

    .environment-item span {
        font-size: 7px;
    }

    .environment-item strong {
        font-size: 14px;
    }

    /* DETECÇÕES */

    .detections {
        gap: 8px;
    }

    .detection {
        padding: 9px;
        min-width: 0;
    }

    .detection-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
    }

    .detection strong {
        font-size: 11px;
    }

    .detection span {
        font-size: 8px;
    }

    /* SISTEMA */

    .system-row {
        gap: 10px;
        padding: 9px 2px;
        font-size: 9px;
    }

    .system-row span {
        min-width: 0;
    }

    .system-row strong {
        font-size: 9px;
        text-align: right;
        white-space: nowrap;
    }

}


/* =========================================================
   CELULAR PEQUENO
========================================================= */

@media (max-width: 480px) {

    .robo-dashboard {
        padding: 12px 0 32px;
    }

    .robo-header-left h2 {
        font-size: 17px;
    }

    .robo-header-left p {
        font-size: 9px;
    }

    .robo-controls {
        gap: 6px;
    }

    .robo-btn {
        height: 40px;
    }

    .robo-stats {
        gap: 7px;
    }

    .robo-stat {
        min-height: 72px;
        padding: 9px;
        gap: 8px;
    }

    .stat-icon {
        width: 33px;
        height: 33px;
        min-width: 33px;
        font-size: 13px;
    }

    .stat-info strong {
        font-size: 16px;
    }

    #posicaoX,
    #posicaoY {
        font-size: 12px;
    }

    .map-wrapper {
        height: 350px;
        min-height: 350px;
    }

    .panel-header {
        padding: 9px 10px;
    }

    .panel-header h3 {
        font-size: 12px;
    }

    .panel-header span {
        font-size: 7px;
    }

    .map-status {
        font-size: 6px;
        padding: 4px 6px;
    }

    .environment-item {
        padding: 8px;
    }

}


/* =========================================================
   CELULAR MUITO PEQUENO
========================================================= */

@media (max-width: 360px) {

    .robo-dashboard {
        padding-left: 0;
        padding-right: 0;
    }

    .robo-header-left h2 {
        font-size: 16px;
    }

    .robo-header-left p {
        font-size: 8px;
    }

    .robo-controls {
        gap: 5px;
    }

    .robo-btn {
        height: 38px;
    }

    .robo-stat {
        padding: 8px;
    }

    .map-wrapper {
        height: 330px;
        min-height: 330px;
    }

    .map-status {
        display: none;
    }

    .panel-header > div:first-child {
        width: 100%;
    }

    .system-row {
        align-items: flex-start;
    }

    .system-row strong {
        max-width: 45%;
        white-space: normal;
    }

}
</style>

@endsection



{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

@section('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | ENDPOINTS DA ROTA
        |--------------------------------------------------------------------------
        | O backend controla execucoes.status_execucao: em_andamento ou abortada.
        | O botao Parar faz POST /monitoramento/rota/parar para abortar o registro.
        | Isso nao e confirmacao de parada fisica do robo.
        */
        const API_ROTA = {
            estado: '/monitoramento/rota',
            iniciar: '/monitoramento/rota',
            parar: '/monitoramento/rota/parar',
            deteccoes: '/monitoramento/deteccoes'
        };


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO DO ROBÔ
        |--------------------------------------------------------------------------
        */

        const robo = {

            x: 0,

            y: 0,

            distancia: 0,

            velocidade: 3.8,

            direcao: 0,

            bateria: 87,

            temperatura: 0,

            umidade: 0,

            vento: 12.3,

            chuva: 0

        };


        /*
        |--------------------------------------------------------------------------
        | CANVAS
        |--------------------------------------------------------------------------
        */

        const canvas = document.getElementById('mapaRobo');


        if (!canvas) {

            console.error('Canvas mapaRobo não encontrado.');
            return;

        }


        const ctx = canvas.getContext('2d');


        if (!ctx) {

            console.error('Canvas 2D não disponível.');
            return;

        }


        /*
        |--------------------------------------------------------------------------
        | BOTÕES DE CONTROLE
        |--------------------------------------------------------------------------
        */

        const btnIniciar   = document.getElementById('btnIniciar');
        const btnParar     = document.getElementById('btnParar');
        const btnReiniciar = document.getElementById('btnReiniciar');
        const btnVelocidade = document.getElementById('btnVelocidade');
        const velocidadesReproducao = [1, 2, 4, 8]; let indiceVelocidade = 0; let velocidadeReproducao = 1;
        const estadoRoboEl = document.getElementById('estadoRobo');
        const routeGate = document.getElementById('routeGate');
        const routeGateTitle = document.getElementById('routeGateTitle');
        const routeGateMessage = document.getElementById('routeGateMessage');
        const formNovaRota = document.getElementById('formNovaRota');
        const selectVeiculo = document.getElementById('selectVeiculo');
        const btnConfirmarRota = document.getElementById('btnConfirmarRota');
        const btnTentarNovamente = document.getElementById('btnTentarNovamente');
        let veiculosDisponiveis = [];
        let iniciarAoCarregarRota = false;
        // Estado da execução no backend, independente da reprodução do canvas.
        let execucaoAtivaId = null;
        let paradaPendente = false;
        let solicitacaoParadaEmCurso = false;
        let estadoExecucaoConfirmado = false;
        let rotaCodigoAtivo = null;

        function mostrarGate(titulo, mensagem) {
            routeGateTitle.textContent = titulo;
            routeGateMessage.textContent = mensagem;
            routeGate.hidden = false;
            btnTentarNovamente.hidden = titulo !== 'Não foi possível consultar as rotas';
            formNovaRota.hidden = true;
        }

        function obterCodigoRota(rotaResposta) {
            if (!rotaResposta) return null;
            return rotaResposta.codigo || rotaResposta.codigo_rota || rotaResposta.chave || null;
        }

        function mostrarSelecaoVeiculo() {
            selectVeiculo.replaceChildren();
            const opcaoInicial = document.createElement('option');
            opcaoInicial.value = '';
            opcaoInicial.textContent = 'Escolha um veículo';
            selectVeiculo.appendChild(opcaoInicial);

            veiculosDisponiveis.forEach(function (veiculo) {
                const option = document.createElement('option');
                option.value = veiculo.id;
                option.textContent = veiculo.nome || veiculo.modelo || ('Veículo ' + veiculo.id);
                selectVeiculo.appendChild(option);
            });

            formNovaRota.hidden = false;
            if (veiculosDisponiveis.length === 0) {
                routeGateMessage.textContent = 'Não há veículos disponíveis para iniciar uma rota.';
                selectVeiculo.disabled = true;
                btnConfirmarRota.disabled = true;
            } else {
                selectVeiculo.disabled = false;
                btnConfirmarRota.disabled = false;
                selectVeiculo.focus();
            }
        }

        function tratarRotaAtiva(rotaAtiva) {
            const codigoRota = obterCodigoRota(rotaAtiva);
            if (!codigoRota) {
                mostrarGate('Rota ativa sem identificação', 'A consulta encontrou uma rota, mas não retornou o código necessário para carregar as medições.');
                return;
            }
            routeGate.hidden = true;
            const idRecebido = Number(rotaAtiva.id_execucao || rotaAtiva.codigo || rotaAtiva.execucao_id || rotaAtiva.id);
            execucaoAtivaId = Number.isInteger(idRecebido) && idRecebido > 0 ? idRecebido : null;
            estadoExecucaoConfirmado = true;
            paradaPendente = rotaAtiva.status_execucao === 'parando' ||
                rotaAtiva.status_execucao === 'parada_solicitada';
            rotaCodigoAtivo = codigoRota;
            atualizarBotoes();
            if (rotaAtiva.veiculo_nome) {
                const subtitulo = document.querySelector('.robo-header-left p');
                if (subtitulo) subtitulo.textContent = 'Acompanhando ' + rotaAtiva.veiculo_nome;
            }
            carregarMedicoes(codigoRota, iniciarAoCarregarRota);
            iniciarAoCarregarRota = false;
        }

        function consultarEstadoRota() {
            mostrarGate('Verificando rotas', 'Consultando se há algum veículo em mapeamento...');
            fetch(API_ROTA.estado, { headers: { 'Accept': 'application/json' } })
                .then(function (response) {
                    if (!response.ok) throw new Error('Erro HTTP: ' + response.status);
                    return response.json();
                })
                .then(function (data) {
                    veiculosDisponiveis = data.veiculos_disponiveis || data.veiculos || [];
                    const rotaAtiva = data.rota_ativa || data.rota || null;
                    if (rotaAtiva) {
                        tratarRotaAtiva(rotaAtiva);
                    } else if (veiculosDisponiveis.length === 0) {
                        execucaoAtivaId = null;
                        estadoExecucaoConfirmado = true;
                        paradaPendente = false;
                        atualizarBotoes();
                        mostrarGate(
                            'Nenhuma rota em execução',
                            'Não há veículos disponíveis para iniciar uma rota.'
                        );
                    } else {
                        execucaoAtivaId = null;
                        estadoExecucaoConfirmado = true;
                        paradaPendente = false;
                        atualizarBotoes();
                        mostrarGate(
                            'Nenhuma rota em execução',
                            'Selecione o veículo para iniciar o monitoramento.'
                        );
                        mostrarSelecaoVeiculo();
                    }
                })
                .catch(function (error) {
                    console.error('Erro ao consultar rota ativa:', error);
                    mostrarGate('Não foi possível consultar as rotas', 'Confira a conexão e tente consultar novamente.');
                });
        }

        if (btnTentarNovamente) btnTentarNovamente.addEventListener('click', consultarEstadoRota);
        if (formNovaRota) formNovaRota.addEventListener('submit', function (event) {
            event.preventDefault();
            const veiculoId = selectVeiculo.value;
            if (!veiculoId) return;

            btnConfirmarRota.disabled = true;
            btnConfirmarRota.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Iniciando...';
            const csrf = document.querySelector('meta[name="csrf-token"]');
            const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
            if (csrf) headers['X-CSRF-TOKEN'] = csrf.content;

            fetch(API_ROTA.iniciar, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ veiculo_id: veiculoId })
            })
                .then(function (response) {
                    if (!response.ok) return response.json().catch(function () { return {}; }).then(function (body) {
                        throw new Error(body.message || 'Erro HTTP: ' + response.status);
                    });
                    return response.json();
                })
                .then(function (data) {
                    const novaRota = data.rota || data;
                    if (!obterCodigoRota(novaRota)) throw new Error('O endpoint não retornou o código da rota iniciada.');
                    iniciarAoCarregarRota = true;
                    tratarRotaAtiva(novaRota);
                })
                .catch(function (error) {
                    console.error('Erro ao iniciar rota:', error);
                    routeGateMessage.textContent = error.message || 'Não foi possível iniciar a rota. Tente novamente.';
                    btnConfirmarRota.disabled = false;
                    btnConfirmarRota.innerHTML = '<i class="fa-solid fa-route"></i> Iniciar rota';
                });
        });


        /*
        |--------------------------------------------------------------------------
        | DADOS DO BANCO
        |--------------------------------------------------------------------------
        */

        let medicoes    = [];
        let rota        = [];
        let rotaVisual  = [];
        let pontosClima = [];


        /*
        |--------------------------------------------------------------------------
        | OBJETOS DO MAPA
        |--------------------------------------------------------------------------
        */

        // Detecções dinâmicas, sempre vinculadas ao ID da execução.
        let objetos = [];
        let escalaVisual = null;
        let execucaoAtualId = null;
        let consultaDeteccoesSeq = 0;
        let timerDeteccoes = null;

        function numeroValido(valor) {
            if (valor === null || valor === undefined || valor === '') return null;
            const n = Number(valor);
            return Number.isFinite(n) ? n : null;
        }

        // Datas da telemetria chegam em ISO 8601 com fuso UTC (+00:00).
        // Exibe somente data e hora no formato brasileiro, em Sao Paulo.
        function formatarDataHoraBR(valor) {
            if (!valor) return '';
            const data = new Date(valor);
            if (Number.isNaN(data.getTime())) return String(valor);
            return new Intl.DateTimeFormat('pt-BR', {
                timeZone: 'America/Sao_Paulo',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hourCycle: 'h23'
            }).format(data);
        }

        function transformarObjetoParaVisual(objeto) {
            if (!escalaVisual) return null;
            const x = numeroValido(objeto.x);
            const y = numeroValido(objeto.y);
            if (x === null || y === null) return null;
            return {
                ...objeto,
                x: escalaVisual.min + (x - escalaVisual.menorX) / escalaVisual.intervaloX * escalaVisual.tamanho,
                y: escalaVisual.min + (y - escalaVisual.menorY) / escalaVisual.intervaloY * escalaVisual.tamanho
            };
        }

        function atualizarListaDeteccoes() {
            const lista = document.getElementById('listaDeteccoes');
            const total = document.getElementById('quantidadeObjetos');
            if (total) total.textContent = String(objetos.length);
            if (!lista) return;
            const posicaoRolagemDeteccoes = lista.scrollTop;
            lista.replaceChildren();

            if (objetos.length === 0) {
                const vazio = document.createElement('div');
                vazio.className = 'detection';
                vazio.textContent = 'Nenhuma detecção registrada nesta execução.';
                lista.appendChild(vazio);
                return;
            }

            function criarCampo(rotulo, valor, larguraTotal) {
                const campo = document.createElement('div');
                campo.className = 'detection-field' + (larguraTotal ? ' is-wide' : '');
                const label = document.createElement('span');
                label.className = 'detection-field-label';
                label.textContent = rotulo;
                const valorEl = document.createElement('span');
                valorEl.className = 'detection-field-value';
                valorEl.textContent = valor;
                campo.appendChild(label);
                campo.appendChild(valorEl);
                return campo;
            }

            objetos.forEach(function (objeto) {
                const item = document.createElement('div');
                item.className = 'detection';

                const cabecalho = document.createElement('div');
                cabecalho.className = 'detection-header';
                const icone = document.createElement('div');
                icone.className = 'detection-icon warning';
                const iconeFonte = document.createElement('i');
                iconeFonte.className = 'fa-solid fa-eye';
                icone.appendChild(iconeFonte);

                const nome = document.createElement('strong');
                nome.className = 'detection-name';
                nome.textContent = objeto.tipo || 'Objeto';

                const confianca = numeroValido(objeto.confianca);
                const percentual = document.createElement('span');
                percentual.className = 'detection-confidence';
                percentual.textContent = confianca === null
                    ? 'N/D'
                    : (confianca * 100).toFixed(1) + '%';
                percentual.title = 'Confiança da detecção';
                cabecalho.appendChild(icone);
                cabecalho.appendChild(nome);
                cabecalho.appendChild(percentual);

                const metadados = document.createElement('div');
                metadados.className = 'detection-metadata';
                const x = numeroValido(objeto.x);
                const y = numeroValido(objeto.y);
                metadados.appendChild(criarCampo('Observação X', x === null ? 'Não informado' : x.toFixed(3), false));
                metadados.appendChild(criarCampo('Observação Y', y === null ? 'Não informado' : y.toFixed(3), false));
                if (objeto.data_hora) {
                    metadados.appendChild(criarCampo('Data e horário', formatarDataHoraBR(objeto.data_hora), true));
                }

                item.appendChild(cabecalho);
                item.appendChild(metadados);
                lista.appendChild(item);
            });
            lista.scrollTop = posicaoRolagemDeteccoes;
        }

        async function carregarDeteccoes(idExecucao) {
            const sequencia = ++consultaDeteccoesSeq;
            if (!idExecucao) {
                objetos = [];
                atualizarListaDeteccoes();
                return;
            }
            try {
                const url = API_ROTA.deteccoes + '?execucao=' + encodeURIComponent(idExecucao);
                const resposta = await fetch(url, {headers: {'Accept': 'application/json'}, credentials: 'same-origin'});
                if (!resposta.ok) throw new Error('HTTP ' + resposta.status);
                const dados = await resposta.json();
                if (sequencia !== consultaDeteccoesSeq) return;
                objetos = Array.isArray(dados) ? dados : [];
                atualizarListaDeteccoes();
                desenharMapa();
            } catch (erro) {
                if (sequencia !== consultaDeteccoesSeq) return;
                console.error('Erro ao carregar detecções da execução:', erro);
                const lista = document.getElementById('listaDeteccoes');
                if (lista) lista.textContent = 'Não foi possível consultar as detecções.';
            }
        }

        function atualizarConsultaDeteccoes(idExecucao) {
            if (timerDeteccoes) clearInterval(timerDeteccoes);
            timerDeteccoes = null;
            execucaoAtualId = idExecucao;
            carregarDeteccoes(idExecucao);
            if (idExecucao) {
                timerDeteccoes = setInterval(function () {
                    if (!document.hidden) carregarDeteccoes(execucaoAtualId);
                }, 10000);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO DA ANIMAÇÃO
        |--------------------------------------------------------------------------
        */

        let pontoAtual          = 0;
        let progresso           = 0;
        let distanciaPercorrida = 0;
        let distanciaTotal      = 0;


        /*
        |--------------------------------------------------------------------------
        | ESTADO DE EXECUÇÃO (Iniciar / Parar / Reiniciar)
        |--------------------------------------------------------------------------
        */

        let emExecucao      = false;
        let animacaoId      = null;
        let dadosCarregados = false;


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA BOTÕES
        |--------------------------------------------------------------------------
        */

        function atualizarBotoes() {

            if (btnIniciar) {

                btnIniciar.disabled = emExecucao || !dadosCarregados || rota.length < 2;

            }


            if (btnParar) {

                btnParar.disabled = !estadoExecucaoConfirmado || !execucaoAtivaId ||
                    paradaPendente || solicitacaoParadaEmCurso;
                const textoParar = btnParar.querySelector('span');
                if (textoParar) textoParar.textContent = paradaPendente ? 'Parada solicitada' : 'Parar';

            }


            if (btnReiniciar) {

                btnReiniciar.disabled = !dadosCarregados;

            }


            if (estadoRoboEl) {

                if (paradaPendente) {
                    estadoRoboEl.innerText = 'Parada solicitada (aguardando robô)';
                    estadoRoboEl.className = 'status-paused';
                } else if (execucaoAtivaId) {
                    estadoRoboEl.innerText = 'Execução ativa no banco';
                    estadoRoboEl.className = 'status-ok';
                } else {
                    estadoRoboEl.innerText = estadoExecucaoConfirmado ? 'Sem execução ativa' : 'Consultando execução';
                    estadoRoboEl.className = 'status-paused';
                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | INTERPOLAÇÃO
        |--------------------------------------------------------------------------
        */

        function interpolar(a, b, valor) {

            return a + (b - a) * valor;

        }


        /*
        |--------------------------------------------------------------------------
        | SUAVIZAÇÃO
        |--------------------------------------------------------------------------
        */

        function suavizar(valor) {

            return valor * valor * (3 - 2 * valor);

        }


        /*
        |--------------------------------------------------------------------------
        | DISTÂNCIA
        |--------------------------------------------------------------------------
        */

        function calcularDistanciaBruta(a, b) {

            const ax = Number(a.x);
            const ay = Number(a.y);
            const bx = Number(b.x);
            const by = Number(b.y);


            if (
                !Number.isFinite(ax) ||
                !Number.isFinite(ay) ||
                !Number.isFinite(bx) ||
                !Number.isFinite(by)
            ) {

                return 0;

            }


            /*
            |--------------------------------------------------------------------------
            | COORDENADAS GEOGRÁFICAS
            |--------------------------------------------------------------------------
            |
            | No seu projeto:
            |
            | X = latitude
            | Y = longitude
            |
            | Exemplo:
            | -23.550100 / -46.633098
            |
            | Nesse caso usamos Haversine e retornamos METROS.
            |
            */

            const coordenadasGeograficas =
                Math.abs(ax) <= 90 &&
                Math.abs(bx) <= 90 &&
                Math.abs(ay) <= 180 &&
                Math.abs(by) <= 180;


            if (coordenadasGeograficas) {

                const raioTerra = 6371000;

                const rad =
                    Math.PI / 180;


                const lat1 =
                    ax * rad;

                const lat2 =
                    bx * rad;


                const deltaLat =
                    (bx - ax) * rad;

                const deltaLon =
                    (by - ay) * rad;


                const h =
                    Math.sin(deltaLat / 2) ** 2 +
                    Math.cos(lat1) *
                    Math.cos(lat2) *
                    Math.sin(deltaLon / 2) ** 2;


                const angulo =
                    2 *
                    Math.atan2(
                        Math.sqrt(h),
                        Math.sqrt(1 - h)
                    );


                return raioTerra * angulo;

            }


            /*
            |--------------------------------------------------------------------------
            | COORDENADAS CARTESIANAS
            |--------------------------------------------------------------------------
            */

            const dx =
                bx - ax;

            const dy =
                by - ay;


            return Math.sqrt(
                dx * dx +
                dy * dy
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DISTÂNCIA ESTIMADA POR DESLOCAMENTO LOCAL X/Y
        |--------------------------------------------------------------------------
        | X/Y são coordenadas locais estimadas, não latitude/longitude.
        | Se os pontos forem iguais, não existe deslocamento registrado.
        | O resultado fica em unidades X/Y (metros apenas se a escala for metros).
        */
        function calcularDistancia(a, b) {
            if (!a || !b) return 0;
            const ax = numeroValido(a.x);
            const ay = numeroValido(a.y);
            const bx = numeroValido(b.x);
            const by = numeroValido(b.y);
            if (ax === null || ay === null || bx === null || by === null) return 0;
            return Math.hypot(bx - ax, by - ay);
        }


        /*
        |--------------------------------------------------------------------------
        | DISTÂNCIA TOTAL
        |--------------------------------------------------------------------------
        */

        function calcularDistanciaTotal() {

            distanciaTotal = 0;


            for (let i = 1; i < rota.length; i++){

                distanciaTotal += calcularDistancia(rota[i - 1], rota[i]);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA ROTA VISUAL
        |--------------------------------------------------------------------------
        |
        | Os valores reais continuam sendo usados
        | nas informações.
        |
        | O mapa expande automaticamente a rota
        | para que os pontos não fiquem grudados.
        |
        */

        function criarRotaVisual() {

            if (rota.length === 0){

                rotaVisual = [];

                pontosClima = [];
                escalaVisual = null;

                return;

            }


            let menorX = rota[0].x;

            let maiorX = rota[0].x;

            let menorY = rota[0].y;

            let maiorY = rota[0].y;


            rota.forEach(function (ponto) {

                if (ponto.x < menorX){

                    menorX = ponto.x;

                }


                if (
                    ponto.x > maiorX
                ) {

                    maiorX =
                        ponto.x;

                }


                if (
                    ponto.y < menorY
                ) {

                    menorY =
                        ponto.y;

                }


                if (ponto.y > maiorY){

                    maiorY = ponto.y;

                }

            });


            let intervaloX = maiorX - menorX;

            let intervaloY = maiorY - menorY;


            if (intervaloX === 0){

                intervaloX = 1;

            }


            if (intervaloY === 0) {

                intervaloY = 1;

            }


            /*
            |--------------------------------------------------------------------------
            | ESPAÇO VISUAL
            |--------------------------------------------------------------------------
            */

            const visualMinimo = 8;
            const visualMaximo = 92;
            const visualTamanho = visualMaximo - visualMinimo;
            escalaVisual = {
                min: visualMinimo, tamanho: visualTamanho,
                menorX: menorX, menorY: menorY,
                intervaloX: intervaloX, intervaloY: intervaloY
            };


            /*
            |--------------------------------------------------------------------------
            | CONVERTE A ROTA
            |--------------------------------------------------------------------------
            */

            rotaVisual =
                rota.map(function (ponto) {

                    const visualX =
                        visualMinimo +
                        (
                            (
                                ponto.x -
                                menorX
                            ) /
                            intervaloX
                        ) *
                        visualTamanho;


                    const visualY =
                        visualMinimo +
                        (
                            (
                                ponto.y -
                                menorY
                            ) /
                            intervaloY
                        ) *
                        visualTamanho;


                    return {

                        x: visualX,

                        y: visualY,

                        realX:
                            ponto.x,

                        realY:
                            ponto.y,

                        temperatura:
                            ponto.temperatura,

                        umidade:
                            ponto.umidade,

                        recebidaEm:
                            ponto.recebidaEm

                    };

                });


            /*
            |--------------------------------------------------------------------------
            | PONTOS CLIMÁTICOS
            |--------------------------------------------------------------------------
            */

            pontosClima =
                rotaVisual.map(function (ponto) {

                    return {

                        x:
                            ponto.x,

                        y:
                            ponto.y,

                        temperatura:
                            ponto.temperatura,

                        umidade:
                            ponto.umidade

                    };

                });

        }


        /*
        |--------------------------------------------------------------------------
        | POSIÇÃO VISUAL
        |--------------------------------------------------------------------------
        */

        function obterPosicaoVisual() {

            if (
                rotaVisual.length === 0
            ) {

                return {
                    x: 0,
                    y: 0
                };

            }


            if (
                pontoAtual >=
                rotaVisual.length - 1
            ) {

                return {

                    x:
                        rotaVisual[
                            rotaVisual.length - 1
                        ].x,

                    y:
                        rotaVisual[
                            rotaVisual.length - 1
                        ].y

                };

            }


            const movimento =
                suavizar(
                    progresso
                );


            return {

                x:
                    interpolar(

                        rotaVisual[
                            pontoAtual
                        ].x,

                        rotaVisual[
                            pontoAtual + 1
                        ].x,

                        movimento

                    ),

                y:
                    interpolar(

                        rotaVisual[
                            pontoAtual
                        ].y,

                        rotaVisual[
                            pontoAtual + 1
                        ].y,

                        movimento

                    )

            };

        }


        /*
        |--------------------------------------------------------------------------
        | POSIÇÃO REAL
        |--------------------------------------------------------------------------
        */

        function obterPosicaoReal() {

            if (
                rota.length === 0
            ) {

                return {
                    x: 0,
                    y: 0
                };

            }


            if (
                pontoAtual >=
                rota.length - 1
            ) {

                return {

                    x:
                        rota[
                            rota.length - 1
                        ].x,

                    y:
                        rota[
                            rota.length - 1
                        ].y

                };

            }


            const movimento =
                suavizar(
                    progresso
                );


            return {

                x:
                    interpolar(

                        rota[
                            pontoAtual
                        ].x,

                        rota[
                            pontoAtual + 1
                        ].x,

                        movimento

                    ),

                y:
                    interpolar(

                        rota[
                            pontoAtual
                        ].y,

                        rota[
                            pontoAtual + 1
                        ].y,

                        movimento

                    )

            };

        }


        /*
        |--------------------------------------------------------------------------
        | DIREÇÃO
        |--------------------------------------------------------------------------
        */

        function calcularDirecao(a, b) {

            const dx =
                b.x - a.x;

            const dy =
                b.y - a.y;


            let graus =
                Math.atan2(
                    dy,
                    dx
                ) *
                180 /
                Math.PI;


            if (
                graus < 0
            ) {

                graus += 360;

            }


            return graus;

        }


        /*
        |--------------------------------------------------------------------------
        | CLIMA
        |--------------------------------------------------------------------------
        */

        function obterClima(x, y) {

            if (
                rota.length === 0
            ) {

                return {

                    temperatura: 0,

                    umidade: 0

                };

            }


            let menorDistancia =
                Infinity;

            let pontoMaisProximo =
                rota[0];


            rota.forEach(function (ponto) {

                const distancia =
                    calcularDistanciaBruta(

                        {
                            x: x,
                            y: y
                        },

                        ponto

                    );


                if (
                    distancia <
                    menorDistancia
                ) {

                    menorDistancia =
                        distancia;

                    pontoMaisProximo =
                        ponto;

                }

            });


            return {

                temperatura:
                    pontoMaisProximo.temperatura,

                umidade:
                    pontoMaisProximo.umidade

            };

        }


        /*
        |--------------------------------------------------------------------------
        | AJUSTA CANVAS
        |--------------------------------------------------------------------------
        */

        function ajustarCanvas() {

            const width =
                canvas.clientWidth;

            const height =
                canvas.clientHeight;


            if (
                width <= 0 ||
                height <= 0
            ) {

                return;

            }


            const dpr =
                window.devicePixelRatio || 1;


            canvas.width =
                width * dpr;

            canvas.height =
                height * dpr;


            canvas.style.width =
                width + 'px';

            canvas.style.height =
                height + 'px';


            ctx.setTransform(

                dpr,
                0,
                0,
                dpr,
                0,
                0

            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONVERSÃO DO MAPA
        |--------------------------------------------------------------------------
        */

        function criarConversao(
            width,
            height
        ) {

            const margem = width <= 500 ? 18 : (width <= 700 ? 24 : 55);


            const larguraMapa =
                width -
                margem * 2;


            const alturaMapa =
                height -
                margem * 2;


            return {

                margem: margem,

                largura:
                    larguraMapa,

                altura:
                    alturaMapa,


                x: function (valor) {

                    return margem +
                        (
                            valor / 100
                        ) *
                        larguraMapa;

                },


                y: function (valor) {

                    return height -
                        margem -
                        (
                            valor / 100
                        ) *
                        alturaMapa;

                }

            };

        }


        /*
        |--------------------------------------------------------------------------
        | COR DA TEMPERATURA
        |--------------------------------------------------------------------------
        */

        function corTemperatura(temp) {

            if (
                temp < 22
            ) {

                return {
                    r: 59,
                    g: 130,
                    b: 246
                };

            }


            if (
                temp < 24
            ) {

                return {
                    r: 56,
                    g: 189,
                    b: 248
                };

            }


            if (
                temp < 26
            ) {

                return {
                    r: 74,
                    g: 222,
                    b: 128
                };

            }


            if (
                temp < 28
            ) {

                return {
                    r: 250,
                    g: 204,
                    b: 21
                };

            }


            if (
                temp < 30
            ) {

                return {
                    r: 249,
                    g: 115,
                    b: 22
                };

            }


            return {
                r: 239,
                g: 68,
                b: 68
            };

        }


        /*
        |--------------------------------------------------------------------------
        | DESENHA MAPA
        |--------------------------------------------------------------------------
        */

        function desenharMapa() {

            const width =
                canvas.clientWidth;

            const height =
                canvas.clientHeight;

            const modoMobile = width <= 500;


            if (
                !width ||
                !height
            ) {

                return;

            }


            ctx.clearRect(
                0,
                0,
                width,
                height
            );


            const mapa =
                criarConversao(
                    width,
                    height
                );


            /*
            |--------------------------------------------------------------------------
            | FUNDO
            |--------------------------------------------------------------------------
            */

            ctx.fillStyle =
                '#101a17';

            ctx.fillRect(
                0,
                0,
                width,
                height
            );


            /*
            |--------------------------------------------------------------------------
            | ÁREA AGRÍCOLA
            |--------------------------------------------------------------------------
            */

            ctx.fillStyle =
                '#16251d';

            ctx.fillRect(

                mapa.margem,

                mapa.margem,

                mapa.largura,

                mapa.altura

            );


            /*
            |--------------------------------------------------------------------------
            | GRID
            |--------------------------------------------------------------------------
            */

            ctx.strokeStyle =
                'rgba(130,170,130,0.12)';

            ctx.lineWidth = 1;


            for (
                let i = 0;
                i <= 10;
                i++
            ) {

                const x =
                    mapa.margem +
                    (
                        i / 10
                    ) *
                    mapa.largura;


                ctx.beginPath();

                ctx.moveTo(
                    x,
                    mapa.margem
                );

                ctx.lineTo(
                    x,
                    height - mapa.margem
                );

                ctx.stroke();


                const y =
                    height -
                    mapa.margem -
                    (
                        i / 10
                    ) *
                    mapa.altura;


                ctx.beginPath();

                ctx.moveTo(
                    mapa.margem,
                    y
                );

                ctx.lineTo(
                    width - mapa.margem,
                    y
                );

                ctx.stroke();

            }


            /*
            |--------------------------------------------------------------------------
            | LINHAS DE PLANTIO
            |--------------------------------------------------------------------------
            */

            ctx.strokeStyle =
                'rgba(72,187,120,0.06)';

            ctx.lineWidth = 2;


            for (
                let x =
                    mapa.margem + 20;

                x <
                    width - mapa.margem;

                x += 35
            ) {

                ctx.beginPath();

                ctx.moveTo(
                    x,
                    mapa.margem
                );

                ctx.lineTo(
                    x,
                    height - mapa.margem
                );

                ctx.stroke();

            }


            /*
            |--------------------------------------------------------------------------
            | MAPA TÉRMICO
            |--------------------------------------------------------------------------
            */

            // Calor localizado nos pontos, com bordas transparentes; não altera medições ou rota.
            if (mapa.largura > 0 && mapa.altura > 0) {
                let termico = desenharMapa.cacheTermico;
                if (!termico || termico.pontos !== pontosClima || termico.largura !== mapa.largura || termico.altura !== mapa.altura) {
                    const pontos = pontosClima.filter(function (p) { return Number.isFinite(p.x) && Number.isFinite(p.y) && Number.isFinite(p.temperatura); });
                    const camada = document.createElement('canvas');
                    camada.width = Math.max(2, Math.ceil(mapa.largura)); camada.height = Math.max(2, Math.ceil(mapa.altura));
                    const pintura = camada.getContext('2d');
                    const colunas = Math.min(220, Math.max(2, Math.ceil(mapa.largura / 3)));
                    const linhas = Math.min(160, Math.max(2, Math.ceil(mapa.altura / 3)));
                    const campo = new Float64Array(colunas * linhas);
                    const raster = document.createElement('canvas'); raster.width = colunas; raster.height = linhas;
                    const rasterCtx = raster.getContext('2d'); const pixels = rasterCtx.createImageData(colunas, linhas);
                    const amostras = new Map();
                    pontos.forEach(function (p) { const chave = p.x + ':' + p.y; const a = amostras.get(chave) || { x: p.x, y: p.y, soma: 0, n: 0 }; a.soma += p.temperatura; a.n++; amostras.set(chave, a); });
                    const dados = Array.from(amostras.values()).map(function (a) { return { x: a.x, y: a.y, temperatura: a.soma / a.n }; });
                    const paleta = [21, 23, 25, 27, 29, 31].map(function (t) { return corTemperatura(t); });
                    for (let y = 0; y < linhas && dados.length; y++) {
                        for (let x = 0; x < colunas; x++) {
                            const vx = x * 100 / (colunas - 1); const vy = 100 - y * 100 / (linhas - 1);
                            let soma = 0; let pesos = 0; let distanciaMinima = Infinity;
                            dados.forEach(function (p) { const d2 = (vx - p.x) * (vx - p.x) + (vy - p.y) * (vy - p.y); distanciaMinima = Math.min(distanciaMinima, d2); const peso = 1 / Math.pow(Math.max(d2, 0.000001), 1.5); soma += p.temperatura * peso; pesos += peso; });
                            const temperatura = soma / pesos; campo[y * colunas + x] = temperatura;
                            const nivel = Math.max(0, Math.min(5, (temperatura - 21) / 2)); const inferior = Math.min(4, Math.floor(nivel)); const fracao = nivel - inferior;
                            const a = paleta[inferior]; const b = paleta[inferior + 1]; const i = (y * colunas + x) * 4;
                            pixels.data[i] = Math.round(a.r + (b.r - a.r) * fracao); pixels.data[i + 1] = Math.round(a.g + (b.g - a.g) * fracao); pixels.data[i + 2] = Math.round(a.b + (b.b - a.b) * fracao); const distancia = Math.sqrt(distanciaMinima); const alcance = Math.max(0, Math.min(1, (16 - distancia) / 12)); pixels.data[i + 3] = Math.round(235 * alcance * alcance * (3 - 2 * alcance));
                        }
                    }
                    rasterCtx.putImageData(pixels, 0, 0);
                    pintura.imageSmoothingEnabled = true; pintura.imageSmoothingQuality = 'high';
                    pintura.drawImage(raster, 0, 0, camada.width, camada.height);
                    // Isotermas a partir de 0,5 °C sobre o mesmo campo interpolado; triangulação evita cruzamentos ambíguos.
                    if (dados.length > 1) {
                        let minimo = Infinity; let maximo = -Infinity;
                        dados.forEach(function (p) { minimo = Math.min(minimo, p.temperatura); maximo = Math.max(maximo, p.temperatura); });
                        const passo = Math.max(0.5, Math.ceil((maximo - minimo) / 12) / 2);
                        pintura.strokeStyle = 'rgba(255,255,255,0.42)'; pintura.lineWidth = 0.8; pintura.lineJoin = 'round'; pintura.lineCap = 'round';
                        for (let nivel = Math.ceil(minimo / passo) * passo; nivel < maximo; nivel += passo) {
                            pintura.beginPath();
                            for (let y = 0; y < linhas - 1; y++) {
                                for (let x = 0; x < colunas - 1; x++) {
                                    const a = [x, y, campo[y * colunas + x]]; const b = [x + 1, y, campo[y * colunas + x + 1]];
                                    const c = [x + 1, y + 1, campo[(y + 1) * colunas + x + 1]]; const d = [x, y + 1, campo[(y + 1) * colunas + x]];
                                    [[a, b, c], [a, c, d]].forEach(function (triangulo) {
                                        const cortes = [];
                                        for (let e = 0; e < 3; e++) {
                                            const p = triangulo[e]; const q = triangulo[(e + 1) % 3];
                                            if ((p[2] < nivel) !== (q[2] < nivel)) { const f = (nivel - p[2]) / (q[2] - p[2]); cortes.push([(p[0] + (q[0] - p[0]) * f) * camada.width / (colunas - 1), (p[1] + (q[1] - p[1]) * f) * camada.height / (linhas - 1)]); }
                                        }
                                        if (cortes.length === 2) { pintura.moveTo(cortes[0][0], cortes[0][1]); pintura.lineTo(cortes[1][0], cortes[1][1]); }
                                    });
                                }
                            }
                            pintura.stroke();
                        }
                    }
                    pintura.globalCompositeOperation = 'destination-in'; pintura.drawImage(raster, 0, 0, camada.width, camada.height); pintura.globalCompositeOperation = 'source-over'; termico = { pontos: pontosClima, largura: mapa.largura, altura: mapa.altura, camada: camada, ativo: dados.length > 0 };
                    desenharMapa.cacheTermico = termico;
                }
                if (termico.ativo) {
                    ctx.save(); ctx.beginPath(); ctx.rect(mapa.margem, mapa.margem, mapa.largura, mapa.altura); ctx.clip();
                    ctx.drawImage(termico.camada, mapa.margem, mapa.margem, mapa.largura, mapa.altura); ctx.restore();
                    ctx.save(); ctx.font = modoMobile ? '8px Arial' : '10px Arial'; ctx.textAlign = 'left'; ctx.fillStyle = 'rgba(255,255,255,0.8)';
                    ctx.fillText('Temperatura estimada (°C)', mapa.margem, modoMobile ? 12 : mapa.margem - 29, mapa.largura);
                    const larguraEscala = Math.min(modoMobile ? 105 : 140, mapa.largura); const escala = ctx.createLinearGradient(mapa.margem, 0, mapa.margem + larguraEscala, 0);
                    [21, 23, 25, 27, 29, 31].forEach(function (t, i) { const c = corTemperatura(t); escala.addColorStop(i / 5, 'rgb(' + c.r + ',' + c.g + ',' + c.b + ')'); });
                    ctx.fillStyle = escala; ctx.fillRect(mapa.margem, modoMobile ? 17 : mapa.margem - 22, larguraEscala, 5);
                    ctx.fillStyle = 'rgba(255,255,255,0.8)'; ctx.fillText('≤21', mapa.margem, modoMobile ? 31 : mapa.margem - 5); ctx.textAlign = 'right'; ctx.fillText('≥31 °C', mapa.margem + larguraEscala, modoMobile ? 31 : mapa.margem - 5); ctx.restore();
                }
            }

























            /*
            |--------------------------------------------------------------------------
            | VALORES DOS EIXOS
            |--------------------------------------------------------------------------
            */

            ctx.fillStyle =
                'rgba(255,255,255,0.55)';

            ctx.font =
                modoMobile ? '8px Arial' : '11px Arial';

            ctx.textAlign =
                'center';


            for (
                let i = 0;
                i <= 10;
                i++
            ) {

                const valor =
                    i * 10;


                /*
                |----------------------------------------------------------------------
                | EIXO X
                |----------------------------------------------------------------------
                */

                const x =
                    mapa.margem +
                    (
                        i / 10
                    ) *
                    mapa.largura;


                ctx.fillText(

                    valor,

                    x,

                    height - 12

                );


                /*
                |----------------------------------------------------------------------
                | EIXO Y
                |----------------------------------------------------------------------
                */

                const y =
                    height -
                    mapa.margem -
                    (
                        i / 10
                    ) *
                    mapa.altura;


                ctx.textAlign =
                    'right';


                ctx.fillText(

                    valor,

                    mapa.margem - 17,

                    y + 4

                );


                ctx.textAlign =
                    'center';

            }


            /*
            |--------------------------------------------------------------------------
            | PONTOS DE TEMPERATURA
            |--------------------------------------------------------------------------
            */

            const totalPontosClima = pontosClima.length;

            const passoPontosClima =
                modoMobile
                    ? Math.max(6, Math.ceil(totalPontosClima / 12))
                    : Math.max(4, Math.ceil(totalPontosClima / 20));


            pontosClima.forEach(
                function (ponto, index) {

                    const mostrarPonto =
                        index === 0 ||
                        index === totalPontosClima - 1 ||
                        index % passoPontosClima === 0;


                    if (!mostrarPonto) {
                        return;
                    }


                    const x =
                        mapa.x(
                            ponto.x
                        );

                    const y =
                        mapa.y(
                            ponto.y
                        );


                    const cor =
                        corTemperatura(
                            ponto.temperatura
                        );


                    /*
                    |----------------------------------------------------------------------
                    | CÍRCULO EXTERNO
                    |----------------------------------------------------------------------
                    */

                    ctx.beginPath();

                    ctx.arc(
                        x,
                        y,
                        modoMobile ? 4 : 6,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        `rgb(
                            ${cor.r},
                            ${cor.g},
                            ${cor.b}
                        )`;

                    ctx.fill();


                    ctx.strokeStyle =
                        'rgba(255,255,255,0.75)';

                    ctx.lineWidth = 1.5;

                    ctx.stroke();



                }
            );


            /*
            |--------------------------------------------------------------------------
            | ROTA COMPLETA
            |--------------------------------------------------------------------------
            */

            if (
                rotaVisual.length > 0
            ) {

                ctx.beginPath();


                rotaVisual.forEach(
                    function (
                        ponto,
                        index
                    ) {

                        const x =
                            mapa.x(
                                ponto.x
                            );

                        const y =
                            mapa.y(
                                ponto.y
                            );


                        if (
                            index === 0
                        ) {

                            ctx.moveTo(
                                x,
                                y
                            );

                        } else {

                            ctx.lineTo(
                                x,
                                y
                            );

                        }

                    }
                );


                ctx.strokeStyle =
                    'rgba(255,255,255,0.18)';

                ctx.lineWidth = 2;

                ctx.setLineDash([
                    7,
                    7
                ]);

                ctx.stroke();

                ctx.setLineDash([]);

            }


            /*
            |--------------------------------------------------------------------------
            | TRAJETO PERCORRIDO
            |--------------------------------------------------------------------------
            */

            if (
                rotaVisual.length > 0
            ) {

                ctx.beginPath();


                for (
                    let i = 0;
                    i <= pontoAtual;
                    i++
                ) {

                    const ponto =
                        rotaVisual[i];


                    const x =
                        mapa.x(
                            ponto.x
                        );

                    const y =
                        mapa.y(
                            ponto.y
                        );


                    if (
                        i === 0
                    ) {

                        ctx.moveTo(
                            x,
                            y
                        );

                    } else {

                        ctx.lineTo(
                            x,
                            y
                        );

                    }

                }


                /*
                |----------------------------------------------------------------------
                | TRECHO ATUAL
                |----------------------------------------------------------------------
                */

                if (
                    pontoAtual <
                    rotaVisual.length - 1
                ) {

                    const inicio =
                        rotaVisual[
                            pontoAtual
                        ];

                    const fim =
                        rotaVisual[
                            pontoAtual + 1
                        ];


                    const movimento =
                        suavizar(
                            progresso
                        );


                    const x =
                        interpolar(

                            inicio.x,

                            fim.x,

                            movimento

                        );


                    const y =
                        interpolar(

                            inicio.y,

                            fim.y,

                            movimento

                        );


                    ctx.lineTo(

                        mapa.x(x),

                        mapa.y(y)

                    );

                }


                ctx.strokeStyle =
                    '#4ade80';

                ctx.lineWidth = 5;

                ctx.lineJoin =
                    'round';

                ctx.lineCap =
                    'round';

                ctx.stroke();

            }


            /*
            |--------------------------------------------------------------------------
            | PONTOS DA ROTA
            |--------------------------------------------------------------------------
            */

            rotaVisual.forEach(
                function (
                    ponto,
                    index
                ) {

                    const x =
                        mapa.x(
                            ponto.x
                        );

                    const y =
                        mapa.y(
                            ponto.y
                        );


                    /*
                    |----------------------------------------------------------------------
                    | ANEL
                    |----------------------------------------------------------------------
                    */

                    ctx.beginPath();

                    ctx.arc(
                        x,
                        y,
                        6,
                        0,
                        Math.PI * 2
                    );


                    ctx.strokeStyle =
                        index <= pontoAtual
                            ? 'rgba(74,222,128,0.35)'
                            : 'rgba(255,255,255,0.18)';

                    ctx.lineWidth = 2;

                    ctx.stroke();


                    /*
                    |----------------------------------------------------------------------
                    | PONTO
                    |----------------------------------------------------------------------
                    */

                    ctx.beginPath();

                    ctx.arc(
                        x,
                        y,
                        3.5,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        index <= pontoAtual
                            ? '#4ade80'
                            : '#94a3a0';


                    ctx.fill();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ORIGEM
            |--------------------------------------------------------------------------
            */

            if (
                rotaVisual.length > 0
            ) {

                const inicio =
                    rotaVisual[0];


                const inicioX =
                    mapa.x(
                        inicio.x
                    );

                const inicioY =
                    mapa.y(
                        inicio.y
                    );


                ctx.beginPath();

                ctx.arc(
                    inicioX,
                    inicioY,
                    11,
                    0,
                    Math.PI * 2
                );


                ctx.strokeStyle =
                    '#94a3b8';

                ctx.lineWidth = 2;

                ctx.stroke();


                ctx.fillStyle =
                    '#cbd5e1';

                ctx.font =
                    modoMobile ? 'bold 8px Arial' : 'bold 10px Arial';

                ctx.textAlign =
                    'left';


                ctx.fillText(

                    'ORIGEM',

                    inicioX + (modoMobile ? 10 : 15),

                    inicioY + 4

                );

            }


            /*
            |--------------------------------------------------------------------------
            | DESTINO
            |--------------------------------------------------------------------------
            |
            | O destino é sempre o último ponto da rota carregada do banco.
            |
            */

            if (rotaVisual.length > 0){

                const destino =rotaVisual[rotaVisual.length - 1];


                const destinoX = mapa.x(destino.x);


                const destinoY = mapa.y(destino.y);


                /*
                |--------------------------------------------------------------------------
                | CÍRCULO EXTERNO
                |--------------------------------------------------------------------------
                */

                ctx.beginPath();

                ctx.arc(
                    destinoX,
                    destinoY,
                    15,
                    0,
                    Math.PI * 2
                );


                ctx.strokeStyle =
                    '#f59e0b';

                ctx.lineWidth = 3;

                ctx.stroke();


                /*
                |--------------------------------------------------------------------------
                | PONTO CENTRAL
                |--------------------------------------------------------------------------
                */

                ctx.beginPath();

                ctx.arc(
                    destinoX,
                    destinoY,
                    5,
                    0,
                    Math.PI * 2
                );


                ctx.fillStyle =
                    '#f59e0b';

                ctx.fill();


                /*
                |--------------------------------------------------------------------------
                | TEXTO
                |--------------------------------------------------------------------------
                */

                ctx.fillStyle =
                    '#f8fafc';

                ctx.font =
                    modoMobile ? 'bold 8px Arial' : 'bold 10px Arial';

                ctx.textAlign =
                    'left';


                ctx.fillText(

                    'DESTINO',

                    destinoX + (modoMobile ? 11 : 19),

                    destinoY + 4

                );

            }


            /*
            |--------------------------------------------------------------------------
            | OBJETOS DETECTADOS
            |--------------------------------------------------------------------------
            */

            objetos.forEach(
                function (objetoOriginal) {
                    const objeto = transformarObjetoParaVisual(objetoOriginal);
                    if (!objeto) return;
                    const x = mapa.x(objeto.x);
                    const y = mapa.y(objeto.y);


                    /*
                    |----------------------------------------------------------------------
                    | ÁREA DE DETECÇÃO
                    |----------------------------------------------------------------------
                    */

                    ctx.beginPath();

                    ctx.arc(
                        x,
                        y,
                        modoMobile ? 15 : 22,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        'rgba(245,158,11,0.08)';

                    ctx.fill();


                    ctx.strokeStyle =
                        'rgba(245,158,11,0.35)';

                    ctx.lineWidth = 1;

                    ctx.stroke();


                    /*
                    |----------------------------------------------------------------------
                    | OBJETO
                    |----------------------------------------------------------------------
                    */

                    ctx.beginPath();

                    ctx.arc(
                        x,
                        y,
                        modoMobile ? 6 : 8,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        '#f59e0b';

                    ctx.fill();


                    ctx.strokeStyle =
                        '#fff';

                    ctx.lineWidth = 2;

                    ctx.stroke();


                    /*
                    |----------------------------------------------------------------------
                    | NOME
                    |----------------------------------------------------------------------
                    */

                    ctx.fillStyle =
                        '#f8fafc';

                    ctx.font =
                        modoMobile ? 'bold 8px Arial' : 'bold 11px Arial';

                    ctx.textAlign =
                        'left';


                    ctx.fillText(

                        objeto.tipo,

                        x + (modoMobile ? 9 : 15),

                        y - (modoMobile ? 7 : 10)

                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | POSIÇÃO VISUAL DO ROBÔ
            |--------------------------------------------------------------------------
            */

            const posicaoVisual =
                obterPosicaoVisual();


            const roboX =
                mapa.x(
                    posicaoVisual.x
                );


            const roboY =
                mapa.y(
                    posicaoVisual.y
                );


            /*
            |--------------------------------------------------------------------------
            | POSIÇÃO REAL
            |--------------------------------------------------------------------------
            */

            const posicaoReal =
                obterPosicaoReal();


            robo.x =
                posicaoReal.x;

            robo.y =
                posicaoReal.y;


            /*
            |--------------------------------------------------------------------------
            | CLIMA
            |--------------------------------------------------------------------------
            */

            const clima =
                obterClima(

                    robo.x,

                    robo.y

                );


            robo.temperatura =
                clima.temperatura;

            robo.umidade =
                clima.umidade;


            /*
            |--------------------------------------------------------------------------
            | DIREÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                pontoAtual <
                rota.length - 1
            ) {

                robo.direcao =
                    calcularDirecao(

                        rota[pontoAtual],

                        rota[pontoAtual + 1]

                    );

            }


            /*
            |--------------------------------------------------------------------------
            | CÍRCULO DE DETECÇÃO
            |--------------------------------------------------------------------------
            */

            ctx.beginPath();

            ctx.arc(
                roboX,
                roboY,
                modoMobile ? 22 : 32,
                0,
                Math.PI * 2
            );


            ctx.fillStyle =
                'rgba(74,222,128,0.07)';

            ctx.fill();


            ctx.strokeStyle =
                'rgba(74,222,128,0.30)';

            ctx.lineWidth = 1.5;

            ctx.stroke();


            /*
            |--------------------------------------------------------------------------
            | SETA DE DIREÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                pontoAtual <
                rota.length - 1
            ) {

                const inicio =
                    rotaVisual[
                        pontoAtual
                    ];

                const fim =
                    rotaVisual[
                        pontoAtual + 1
                    ];


                const dx =
                    fim.x -
                    inicio.x;


                const dy =
                    fim.y -
                    inicio.y;


                const angulo =
                    Math.atan2(
                        -dy,
                        dx
                    );


                const tamanho = modoMobile ? 24 : 34;


                const setaX =
                    roboX +
                    Math.cos(
                        angulo
                    ) *
                    tamanho;


                const setaY =
                    roboY +
                    Math.sin(
                        angulo
                    ) *
                    tamanho;


                ctx.beginPath();

                ctx.moveTo(
                    roboX,
                    roboY
                );

                ctx.lineTo(
                    setaX,
                    setaY
                );


                ctx.strokeStyle =
                    '#22c55e';

                ctx.lineWidth = 3;

                ctx.lineCap =
                    'round';

                ctx.stroke();


                const ponta = modoMobile ? 6 : 8;


                ctx.beginPath();

                ctx.moveTo(
                    setaX,
                    setaY
                );


                ctx.lineTo(

                    setaX -
                    Math.cos(
                        angulo - 0.5
                    ) *
                    ponta,

                    setaY -
                    Math.sin(
                        angulo - 0.5
                    ) *
                    ponta

                );


                ctx.lineTo(

                    setaX -
                    Math.cos(
                        angulo + 0.5
                    ) *
                    ponta,

                    setaY -
                    Math.sin(
                        angulo + 0.5
                    ) *
                    ponta

                );


                ctx.closePath();


                ctx.fillStyle =
                    '#22c55e';

                ctx.fill();

            }


            /*
            |--------------------------------------------------------------------------
            | ROBÔ
            |--------------------------------------------------------------------------
            */

            ctx.beginPath();

            ctx.arc(
                roboX,
                roboY,
                modoMobile ? 9 : 12,
                0,
                Math.PI * 2
            );


            ctx.fillStyle =
                '#22c55e';

            ctx.fill();


            ctx.strokeStyle =
                '#ffffff';

            ctx.lineWidth = 3;

            ctx.stroke();


            /*
            |--------------------------------------------------------------------------
            | NOME DO ROBÔ
            |--------------------------------------------------------------------------
            */

            ctx.fillStyle =
                '#ffffff';

            ctx.font =
                modoMobile ? 'bold 9px Arial' : 'bold 12px Arial';

            ctx.textAlign =
                'left';


            ctx.fillText(

                'ROBÔ',

                roboX + (modoMobile ? 12 : 19),

                roboY - (modoMobile ? 14 : 21)

            );


            /*
            |--------------------------------------------------------------------------
            | COORDENADAS
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | COORDENADAS E TEMPERATURA
            |--------------------------------------------------------------------------
            |
            | As informações permanecem nos cards superiores e no painel lateral.
            | Não são repetidas sobre o mapa para evitar sobreposição visual.
            |
            */

        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA INFORMAÇÕES
        |--------------------------------------------------------------------------
        */

        function atualizarInformacoes() {

            const posicaoX = document.getElementById('posicaoX');

            const posicaoY = document.getElementById('posicaoY');

            const distancia = document.getElementById('distancia');

            const direcao = document.getElementById('direcao');

            const velocidade = document.getElementById('velocidade');

            const bateria = document.getElementById('bateria');

            const temperatura = document.getElementById('temperatura');

            const umidade = document.getElementById('umidade');


            if (posicaoX) {

                posicaoX.innerText = robo.x.toFixed(6);

            }


            if (posicaoY) {

                posicaoY.innerText = robo.y.toFixed(6);

            }


            if (distancia) {

                if (distanciaPercorrida < 10) {

                    distancia.innerText =
                        distanciaPercorrida.toFixed(2);

                } else {

                    distancia.innerText =
                        distanciaPercorrida.toFixed(1);

                }

            }


            if (direcao) {

                direcao.innerText = Math.round(robo.direcao) + '°';

            }


            if (velocidade) {

                velocidade.innerText = robo.velocidade.toFixed(1);

            }


            if (bateria) {

                bateria.innerText = Math.round(robo.bateria) + '%';

            }


            if (temperatura) {

                temperatura.innerText = Number.isFinite(robo.temperatura) ? robo.temperatura.toFixed(1) + '°C' : 'Sem leitura';

            }


            if (umidade) {

                umidade.innerText = Number.isFinite(robo.umidade) ? Math.round(robo.umidade) + '%' : 'Sem leitura';

            }

            const ultimaAtualizacao = document.getElementById('ultimaAtualizacao');


            if (ultimaAtualizacao) {

                ultimaAtualizacao.innerText = new Date().toLocaleTimeString('pt-BR');

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CALCULA DISTÂNCIA PERCORRIDA ATÉ A POSIÇÃO ATUAL
        |--------------------------------------------------------------------------
        */

        function calcularDistanciaPercorridaAtual() {

            if (rota.length < 2) {

                return 0;

            }


            let total = 0;


            for (
                let i = 1;
                i <= pontoAtual && i < rota.length;
                i++
            ) {

                total +=
                    calcularDistancia(
                        rota[i - 1],
                        rota[i]
                    );

            }


            if (
                pontoAtual <
                rota.length - 1
            ) {

                const trechoAtual =
                    calcularDistancia(
                        rota[pontoAtual],
                        rota[pontoAtual + 1]
                    );


                const progressoVisual =
                    suavizar(progresso);


                total +=
                    trechoAtual * progressoVisual;

            }


            return total;

        }


        /*
        |--------------------------------------------------------------------------
        | ANIMAÇÃO
        |--------------------------------------------------------------------------
        */

        function animar() {

            if (!emExecucao){

                return;

            }


            if (rota.length < 2) {

                emExecucao = false;

                atualizarBotoes();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | VELOCIDADE
            |--------------------------------------------------------------------------
            */

            progresso += 0.006 * velocidadeReproducao;


            /*
            |--------------------------------------------------------------------------
            | AVANÇA PONTO
            |--------------------------------------------------------------------------
            */

            if (progresso >= 1) {

                progresso -= 1;

                pontoAtual++;

            }


            /*
            |--------------------------------------------------------------------------
            | FINAL DA ROTA
            |--------------------------------------------------------------------------
            */

            if (pontoAtual >= rota.length - 1) {

                pontoAtual = rota.length - 1;

                progresso = 0;


                distanciaPercorrida = distanciaTotal;


                robo.bateria = 67;


                desenharMapa();

                atualizarInformacoes();

                emExecucao = false;

                atualizarBotoes();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DISTÂNCIA PERCORRIDA
            |--------------------------------------------------------------------------
            */

            distanciaPercorrida =
                calcularDistanciaPercorridaAtual();


            /*
            |--------------------------------------------------------------------------
            | BATERIA
            |--------------------------------------------------------------------------
            */

            if (distanciaTotal > 0){

                robo.bateria = Math.max(15,87 - (distanciaPercorrida /distanciaTotal) * 20);

            }


            /*
            |--------------------------------------------------------------------------
            | DESENHA
            |--------------------------------------------------------------------------
            */

            desenharMapa();

            atualizarInformacoes();


            /*
            |--------------------------------------------------------------------------
            | PRÓXIMO FRAME
            |--------------------------------------------------------------------------
            */

            animacaoId = requestAnimationFrame(animar);

        }


        /*
        |--------------------------------------------------------------------------
        | INICIAR
        |--------------------------------------------------------------------------
        */

        function iniciarAnimacao() {

            if (emExecucao || rota.length < 2){

                return;

            }

            emExecucao = true;

            atualizarBotoes();

            animacaoId = requestAnimationFrame(animar);

        }


        /*
        |--------------------------------------------------------------------------
        | PARAR
        |--------------------------------------------------------------------------
        */

        function pararAnimacao() {

            emExecucao = false;


            if (animacaoId){

                cancelAnimationFrame(animacaoId);
                animacaoId = null;

            }


            atualizarBotoes();

        }


        /*
        |--------------------------------------------------------------------------
        | REINICIAR
        |--------------------------------------------------------------------------
        */

        function reiniciarAnimacao() {

            pararAnimacao();

            pontoAtual = 0;

            progresso = 0;

            distanciaPercorrida = 0;


            robo.bateria = 87;


            if (rota.length > 0) {

                robo.x = rota[0].x;

                robo.y = rota[0].y;


                robo.temperatura = rota[0].temperatura;


                robo.umidade = rota[0].umidade;

            }

            desenharMapa();

            atualizarInformacoes();

            atualizarBotoes();

        }


        /*
        |--------------------------------------------------------------------------
        | EVENTOS DOS BOTÕES
        |--------------------------------------------------------------------------
        */

        if (btnVelocidade) { btnVelocidade.addEventListener('click', function () { indiceVelocidade = (indiceVelocidade + 1) % velocidadesReproducao.length; velocidadeReproducao = velocidadesReproducao[indiceVelocidade]; document.getElementById('valorVelocidade').textContent = velocidadeReproducao + 'x'; const descricao = 'Velocidade da reprodução: ' + velocidadeReproducao + 'x. Clique para alternar entre 1x, 2x, 4x e 8x.'; btnVelocidade.title = descricao; btnVelocidade.setAttribute('aria-label', descricao); }); }

        if (btnIniciar) {

            btnIniciar.addEventListener(
                'click',
                iniciarAnimacao
            );

        }


        // Dialog acessível de confirmação e retorno da parada.
        const modalParada = document.getElementById('modalParada');
        const modalParadaTitulo = document.getElementById('modalParadaTitulo');
        const modalParadaDescricao = document.getElementById('modalParadaDescricao');
        const modalParadaObservacao = document.getElementById('modalParadaObservacao');
        const modalParadaEtiqueta = document.getElementById('modalParadaEtiqueta');
        const modalParadaIcone = document.getElementById('modalParadaIcone');
        const modalParadaConfirmar = document.getElementById('modalParadaConfirmar');
        const modalParadaCancelar = document.getElementById('modalParadaCancelar');
        const modalParadaOk = document.getElementById('modalParadaOk');
        const modalParadaFechar = document.getElementById('modalParadaFechar');
        let modalidadeParada = 'confirmacao';
        let ultimaFocoParada = null;
        let atualizarGateDepoisModal = false;

        function abrirModalParada() {
            if (!execucaoAtivaId || paradaPendente || solicitacaoParadaEmCurso) return;
            modalidadeParada = 'confirmacao';
            atualizarGateDepoisModal = false;
            ultimaFocoParada = document.activeElement;
            modalParadaEtiqueta.textContent = 'CONTROLE DA EXECUÇÃO';
            modalParadaTitulo.textContent = 'Encerrar rota?';
            modalParadaDescricao.textContent = 'Deseja encerrar a execução #' + execucaoAtivaId + '? O status será alterado para abortada no banco de dados.';
            modalParadaObservacao.textContent = 'O encerramento no sistema não garante a parada física dos motores.';
            modalParadaObservacao.hidden = false;
            modalParadaIcone.className = 'parada-modal-icone';
            modalParadaIcone.innerHTML = '<i class="fa-solid fa-stop" aria-hidden="true"></i>';
            modalParadaCancelar.hidden = false;
            modalParadaConfirmar.hidden = false;
            modalParadaOk.hidden = true;
            modalParadaFechar.disabled = false;
            modalParada.hidden = false;
            modalParadaCancelar.focus();
        }

        function fecharModalParada() {
            if (solicitacaoParadaEmCurso) return;
            modalParada.hidden = true;
            if (ultimaFocoParada && typeof ultimaFocoParada.focus === 'function' && !ultimaFocoParada.disabled) {
                ultimaFocoParada.focus();
            }
            if (atualizarGateDepoisModal) {
                atualizarGateDepoisModal = false;
                consultarEstadoRota();
            }
        }

        function mostrarResultadoParada(tipo, mensagem) {
            modalidadeParada = 'resultado';
            modalParadaEtiqueta.textContent = tipo === 'sucesso' ? 'EXECUÇÃO ATUALIZADA' : 'NÃO FOI POSSÍVEL ENCERRAR';
            modalParadaTitulo.textContent = tipo === 'sucesso' ? 'Rota encerrada' : 'Não foi possível encerrar';
            modalParadaDescricao.textContent = mensagem;
            modalParadaObservacao.textContent = tipo === 'sucesso'
                ? 'Status atualizado no banco. Isso não confirma a parada física do robô.'
                : 'A execução poderá continuar ativa. Verifique o erro e tente novamente.';
            modalParadaIcone.className = 'parada-modal-icone ' + (tipo === 'sucesso' ? 'sucesso' : 'erro');
            modalParadaIcone.innerHTML = tipo === 'sucesso'
                ? '<i class="fa-solid fa-check" aria-hidden="true"></i>'
                : '<i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>';
            modalParadaCancelar.hidden = true;
            modalParadaConfirmar.hidden = true;
            modalParadaOk.hidden = false;
            modalParadaOk.focus();
        }

        async function solicitarParadaRobo() {
            if (!execucaoAtivaId || paradaPendente || solicitacaoParadaEmCurso) return;
            const idSolicitado = execucaoAtivaId;
            solicitacaoParadaEmCurso = true;
            modalParadaConfirmar.disabled = true;
            modalParadaConfirmar.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Encerrando...';
            modalParadaCancelar.disabled = true;
            modalParadaFechar.disabled = true;
            atualizarBotoes();
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]');
                const resposta = await fetch(API_ROTA.parar, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...(csrf ? {'X-CSRF-TOKEN': csrf.content} : {})
                    },
                    body: JSON.stringify({id_execucao: idSolicitado})
                });
                const dados = await resposta.json().catch(function () { return {}; });
                if (!resposta.ok) throw new Error(dados.message || 'Erro HTTP ' + resposta.status);

                if (dados.status_execucao === 'abortada') {
                    execucaoAtivaId = null;
                    estadoExecucaoConfirmado = true;
                    paradaPendente = false;
                    rotaCodigoAtivo = null;
                    pararAnimacao();
                    if (estadoRoboEl) {
                        estadoRoboEl.textContent = 'Execução abortada no sistema';
                        estadoRoboEl.className = 'status-paused';
                    }
                    atualizarGateDepoisModal = true;
                    mostrarResultadoParada('sucesso', 'A execução #' + idSolicitado + ' foi marcada como abortada no banco de dados.');
                } else {
                    paradaPendente = true;
                    pararAnimacao();
                    mostrarResultadoParada('sucesso', dados.message || 'A solicitação de parada foi registrada.');
                }
            } catch (erro) {
                console.error('Erro na solicitação de parada:', erro);
                mostrarResultadoParada('erro', erro.message || 'Erro de comunicação com o servidor.');
            } finally {
                solicitacaoParadaEmCurso = false;
                modalParadaConfirmar.disabled = false;
                modalParadaConfirmar.innerHTML = '<i class="fa-solid fa-stop" aria-hidden="true"></i> Encerrar rota';
                modalParadaCancelar.disabled = false;
                modalParadaFechar.disabled = false;
                atualizarBotoes();
            }
        }

        if (btnParar) btnParar.addEventListener('click', abrirModalParada);
        modalParadaConfirmar.addEventListener('click', solicitarParadaRobo);
        modalParadaCancelar.addEventListener('click', fecharModalParada);
        modalParadaFechar.addEventListener('click', fecharModalParada);
        modalParadaOk.addEventListener('click', fecharModalParada);
        modalParada.addEventListener('click', function (event) {
            if (event.target === modalParada && modalidadeParada !== 'confirmacao') return;
            if (event.target === modalParada) fecharModalParada();
        });
        document.addEventListener('keydown', function (event) {
            if (modalParada.hidden) return;
            if (event.key === 'Escape') {
                event.preventDefault();
                fecharModalParada();
            }
            if (event.key === 'Tab') {
                const elementos = Array.from(modalParada.querySelectorAll('button:not([disabled]):not([hidden])'));
                if (!elementos.length) return;
                const primeiro = elementos[0];
                const ultimo = elementos[elementos.length - 1];
                if (event.shiftKey && document.activeElement === primeiro) { event.preventDefault(); ultimo.focus(); }
                else if (!event.shiftKey && document.activeElement === ultimo) { event.preventDefault(); primeiro.focus(); }
            }
        });

        // Reconciliar sem substituir a rota no canvas ou abrir o modal a cada consulta.
        async function sincronizarEstadoExecucao() {
            if (document.hidden || solicitacaoParadaEmCurso) return;
            try {
                const resposta = await fetch(API_ROTA.estado, {
                    headers: {'Accept': 'application/json'}, credentials: 'same-origin', cache: 'no-store'
                });
                if (!resposta.ok) return;
                const dados = await resposta.json();
                const ativa = dados.rota_ativa || dados.rota || null;
                const codigo = obterCodigoRota(ativa);
                if (!ativa) {
                    execucaoAtivaId = null;
                    paradaPendente = false;
                    estadoExecucaoConfirmado = true;
                    rotaCodigoAtivo = null;
                    atualizarBotoes();
                    return;
                }
                if (codigo && rotaCodigoAtivo && String(codigo) !== String(rotaCodigoAtivo)) {
                    // Uma nova rota começou; a página deve selecionar sua execução.
                    window.location.reload();
                    return;
                }
                const id = Number(ativa.id_execucao || ativa.codigo || ativa.execucao_id || ativa.id);
                execucaoAtivaId = Number.isInteger(id) && id > 0 ? id : null;
                paradaPendente = ativa.status_execucao === 'parando' ||
                    ativa.status_execucao === 'parada_solicitada' || paradaPendente;
                estadoExecucaoConfirmado = true;
                atualizarBotoes();
            } catch (erro) {
                console.warn('Falha ao sincronizar estado da execução:', erro);
            }
        }
        if (btnReiniciar) {

            btnReiniciar.addEventListener(
                'click',
                reiniciarAnimacao
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CARREGA DADOS DO BANCO
        |--------------------------------------------------------------------------
        */

        function carregarMedicoes(codigoRota, iniciarAutomaticamente) {

            /*
            |--------------------------------------------------------------------------
            | VERIFICA SE VEIO UMA ROTA PELO HISTÓRICO
            |--------------------------------------------------------------------------
            */

            const parametros =
                new URLSearchParams(
                    window.location.search
                );


            const rotaHistorica = parametros.get('rota');


            /*
            |--------------------------------------------------------------------------
            | URL DOS DADOS
            |--------------------------------------------------------------------------
            */

            let urlDados =
                '/monitoramento/dados';


            const codigoParaCarregar = codigoRota || rotaHistorica;

            if (codigoParaCarregar) {

                urlDados +=
                    '?rota=' +
                    encodeURIComponent(
                        codigoParaCarregar
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | CARREGA
            |--------------------------------------------------------------------------
            */

            fetch(urlDados)

            .then(
                function (response) {

                    if (!response.ok) {

                        throw new Error(
                            'Erro HTTP: ' +
                            response.status
                        );

                    }

                    return response.json();

                }
            )

            .then(
                function (data) {

                    medicoes = Array.isArray(data) ? data : (data && Array.isArray(data.medicoes) ? data.medicoes : []);
                    const aviso = document.getElementById('avisoMedicoes');
                    if (aviso) { aviso.hidden = true; aviso.textContent = ''; }
                    // O endpoint /monitoramento/dados precisa retornar id_execucao em cada medição.
                    const idDaExecucao = medicoes.length ? Number(medicoes[0].id_execucao) :
                        Number((data && data.id_execucao) || execucaoAtivaId || codigoParaCarregar);
                    atualizarConsultaDeteccoes(Number.isInteger(idDaExecucao) && idDaExecucao > 0 ? idDaExecucao : null);
                    if (!rotaHistorica && rotaCodigoAtivo && Number.isInteger(idDaExecucao) && idDaExecucao > 0) {
                        execucaoAtivaId = idDaExecucao;
                        atualizarBotoes();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TRANSFORMA BANCO EM ROTA
                    |--------------------------------------------------------------------------
                    */

                    rota =
                        medicoes
                        .map(
                            function (medicao) {

                                return {

                                    x:
                                        parseFloat(
                                            medicao.x !== undefined ? medicao.x : medicao.latitude_X
                                        ),

                                    y:
                                        parseFloat(
                                            medicao.y !== undefined ? medicao.y : medicao.longitude_Y
                                        ),

                                    temperatura:
                                        numeroValido(medicao.temperatura),

                                    umidade:
                                        numeroValido(medicao.umidade),

                                    recebidaEm:
                                        medicao.recebida_em || medicao.data_hora

                                };

                            }
                        )
                        .filter(
                            function (ponto) {

                                return (
                                    !isNaN(ponto.x)
                                    &&
                                    !isNaN(ponto.y)
                                );

                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | ORDENA POR DATA
                    |--------------------------------------------------------------------------
                    */

                    rota.sort(
                        function (a, b) {

                            return (
                                new Date(a.recebidaEm)
                                -
                                new Date(b.recebidaEm)
                            );

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | NENHUM DADO
                    |--------------------------------------------------------------------------
                    */

                    if (rota.length === 0) {

                        console.warn(
                            'Nenhuma medição encontrada.'
                        );

                        dadosCarregados = false;
                        rotaVisual = [];
                        pontosClima = [];
                        desenharMapa.cacheTermico = null;
                        ajustarCanvas();
                        desenharMapa();
                        const avisoSemLeituras = document.getElementById('avisoMedicoes');
                        if (avisoSemLeituras) {
                            avisoSemLeituras.textContent = 'Rota em andamento. Aguardando as primeiras medições; você ainda pode usar Parar.';
                            avisoSemLeituras.hidden = false;
                        }
                        atualizarBotoes();

                        // Não bloquear o botão Parar com o modal enquanto aguarda medições.
                        if (iniciarAutomaticamente && execucaoAtivaId) {
                            routeGate.hidden = true;
                            const subtitulo = document.querySelector('.robo-header-left p');
                            if (subtitulo) subtitulo.textContent = 'Rota ativa. Aguardando primeiras medições.';
                        }

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CRIA ROTA VISUAL
                    |--------------------------------------------------------------------------
                    */

                    criarRotaVisual();


                    /*
                    |--------------------------------------------------------------------------
                    | CALCULA DISTÂNCIA
                    |--------------------------------------------------------------------------
                    */

                    calcularDistanciaTotal();


                    console.log(
                        'Distância operacional estimada:',
                        distanciaTotal.toFixed(2),
                        'metros | Pontos:',
                        rota.length
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | ESTADO INICIAL
                    |--------------------------------------------------------------------------
                    */

                    pontoAtual = 0;

                    progresso = 0;

                    distanciaPercorrida = 0;


                    robo.x =
                        rota[0].x;

                    robo.y =
                        rota[0].y;

                    robo.temperatura =
                        rota[0].temperatura;

                    robo.umidade =
                        rota[0].umidade;


                    /*
                    |--------------------------------------------------------------------------
                    | PRIMEIRO DESENHO
                    |--------------------------------------------------------------------------
                    */

                    ajustarCanvas();

                    desenharMapa();

                    atualizarInformacoes();


                    dadosCarregados = true;

                    atualizarBotoes();

                    if (iniciarAutomaticamente && rota.length >= 2) {
                        iniciarAnimacao();
                    }

                    if (iniciarAutomaticamente && rota.length < 2 && execucaoAtivaId) {
                        // Com um ponto, o mapa não anima; o botão Parar continua disponível.
                        routeGate.hidden = true;
                        const subtitulo = document.querySelector('.robo-header-left p');
                        if (subtitulo) subtitulo.textContent = 'Rota ativa. Aguardando novas medições.';
                    }

                }
            )

            .catch(
                function (error) {

                    console.error(
                        'Erro ao carregar medições:',
                        error
                    );

                    const aviso = document.getElementById('avisoMedicoes');
                    if (aviso) {
                        aviso.textContent = 'Não foi possível carregar as medições: ' + (error.message || String(error)) + '. Se a execução estiver ativa, o botão Parar continua disponível.';
                        aviso.hidden = false;
                    }
                    // Erro nas medições NÃO deve bloquear o comando de parada.
                    // Só o GET /monitoramento/rota determina se a execução está ativa.
                    if (execucaoAtivaId) routeGate.hidden = true;
                    atualizarBotoes();

                }
            );

        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVIDADE
        |--------------------------------------------------------------------------
        */

        let resizeTimeout;


        window.addEventListener(

            'resize',

            function () {

                clearTimeout(
                    resizeTimeout
                );


                resizeTimeout =
                    setTimeout(

                        function () {

                            ajustarCanvas();

                            desenharMapa();

                        },

                        100

                    );

            }

        );


        /*
        |--------------------------------------------------------------------------
        | INICIA
        |--------------------------------------------------------------------------
        */

        atualizarBotoes();

        const parametrosIniciais = new URLSearchParams(window.location.search);
        if (parametrosIniciais.get('rota')) {
            carregarMedicoes();
        } else {
            consultarEstadoRota();
        }

        // Consulta leve do estado no banco, independente da animação do mapa.
        setInterval(sincronizarEstadoExecucao, 10000);

    });

</script>

@endsection
