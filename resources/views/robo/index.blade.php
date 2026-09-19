@extends('layouts.app')

@section('title', 'Monitoramento do Robô')


@section('content')

<div class="robo-dashboard">

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

                <button type="button" class="robo-btn start" id="btnIniciar" title="Iniciar">
                    <i class="fa-solid fa-play"></i>
                    <span>Iniciar</span>
                </button>

                <button type="button" class="robo-btn stop" id="btnParar" title="Parar">
                    <i class="fa-solid fa-pause"></i>
                    <span>Parar</span>
                </button>

                <button type="button" class="robo-btn reset" id="btnReiniciar" title="Reiniciar">
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

                <strong id="posicaoX">42.0</strong>

                <small>metros</small>

            </div>

        </div>


        {{-- POSIÇÃO Y --}}
        <div class="robo-stat">

            <div class="stat-icon blue">
                <i class="fa-solid fa-arrows-up-down"></i>
            </div>

            <div class="stat-info">

                <span>Posição Y</span>

                <strong id="posicaoY">67.0</strong>

                <small>metros</small>

            </div>

        </div>


        {{-- DISTÂNCIA --}}
        <div class="robo-stat">

            <div class="stat-icon orange">
                <i class="fa-solid fa-route"></i>
            </div>

            <div class="stat-info">

                <span>Distância</span>

                <strong id="distancia">128.5</strong>

                <small>metros percorridos</small>

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


        {{-- BATERIA --}}
        <div class="robo-stat">

            <div class="stat-icon battery">
                <i class="fa-solid fa-battery-three-quarters"></i>
            </div>

            <div class="stat-info">

                <span>Bateria</span>

                <strong id="bateria">87%</strong>

                <small>carga atual</small>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ÁREA PRINCIPAL
    ========================================================== --}}

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

                    GPS ATIVO

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
                        Início
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
                            68%
                        </strong>

                    </div>


                    <div class="environment-item">

                        <i class="fa-solid fa-wind"></i>

                        <span>Vento</span>

                        <strong id="vento">
                            12.3 km/h
                        </strong>

                    </div>


                    <div class="environment-item">

                        <i class="fa-solid fa-cloud-rain"></i>

                        <span>Chuva</span>

                        <strong id="chuva">
                            0 mm
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


                <div class="detections">


                    {{-- ÁRVORE --}}

                    <div class="detection">

                        <div class="detection-icon tree">

                            <i class="fa-solid fa-tree"></i>

                        </div>

                        <div>

                            <strong>Árvore</strong>

                            <span>
                                X: 71 | Y: 78
                            </span>

                        </div>

                    </div>


                    {{-- OBSTÁCULO --}}

                    <div class="detection">

                        <div class="detection-icon warning">

                            <i class="fa-solid fa-triangle-exclamation"></i>

                        </div>

                        <div>

                            <strong>Obstáculo</strong>

                            <span>
                                X: 55 | Y: 49
                            </span>

                        </div>

                    </div>


                    {{-- IRRIGAÇÃO --}}

                    <div class="detection">

                        <div class="detection-icon water">

                            <i class="fa-solid fa-faucet-drip"></i>

                        </div>

                        <div>

                            <strong>Irrigação</strong>

                            <span>
                                X: 28 | Y: 61
                            </span>

                        </div>

                    </div>

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

                        GPS

                    </span>

                    <strong class="status-ok">
                        Alta precisão
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

html {
    height: 100%;
}

body {
    min-height: 100%;
    overflow-y: auto !important;
}


/* =========================================================
   DASHBOARD
========================================================= */

.robo-dashboard {
    width: 100%;
    min-height: 100vh;
    padding: 22px 25px 60px;
    box-sizing: border-box;
    color: #e6eee9;
    overflow-y: visible;
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
        repeat(6, minmax(130px, 1fr));

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

.stat-info small {
    color: #86988f;

    font-size: 10px;

    font-weight: 600;

    margin-left: 4px;
}


/* =========================================================
   MAPA + PAINEL LATERAL
========================================================= */

.robo-content {
    display: grid;

    grid-template-columns:
        minmax(0, 2.2fr)
        minmax(300px, .8fr);

    gap: 18px;

    align-items: stretch;
}


/* =========================================================
   PAINEL DO MAPA
========================================================= */

.map-panel {
    min-width: 0;

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
        grid-template-columns:
            repeat(3, 1fr);
    }

    .robo-content {
        grid-template-columns:
            minmax(0, 1.8fr)
            minmax(280px, .8fr);
    }

}


@media (max-width: 1000px) {

    .robo-content {
        grid-template-columns: 1fr;
    }

    .map-wrapper {
        height: 480px;
    }

}


@media (max-width: 700px) {

    .robo-dashboard {
        padding: 14px 14px 60px;
    }

    .robo-header {
        align-items: flex-start;
        gap: 12px;
    }

    .robo-online {
        font-size: 8px;
    }

    .robo-controls {
        flex-wrap: wrap;
    }

    .robo-stats {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .map-wrapper {
        height: 420px;
    }

}


@media (max-width: 480px) {

    .robo-stats {
        grid-template-columns: 1fr;
    }

    .robo-header-left h2 {
        font-size: 18px;
    }

    .environment-grid {
        grid-template-columns: 1fr;
    }

    .robo-btn span {
        display: none;
    }

    .robo-btn {
        padding: 10px 12px;
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
    | CONFIGURAÇÃO DO ROBÔ
    |--------------------------------------------------------------------------
    */

    const robo = {

        x: 10,

        y: 15,

        destinoX: 90,

        destinoY: 85,

        distancia: 0,

        velocidade: 3.8,

        direcao: 0,

        bateria: 87,

        temperatura: 24.7,

        umidade: 68,

        vento: 12.3,

        chuva: 0

    };


    /*
    |--------------------------------------------------------------------------
    | CANVAS
    |--------------------------------------------------------------------------
    */

    const canvas =
        document.getElementById('mapaRobo');


    if (!canvas) {

        console.error(
            'Canvas mapaRobo não encontrado.'
        );

        return;

    }


    const ctx =
        canvas.getContext('2d');


    if (!ctx) {

        console.error(
            'Canvas 2D não disponível.'
        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | BOTÕES DE CONTROLE
    |--------------------------------------------------------------------------
    */

    const btnIniciar =
        document.getElementById('btnIniciar');

    const btnParar =
        document.getElementById('btnParar');

    const btnReiniciar =
        document.getElementById('btnReiniciar');

    const estadoRoboEl =
        document.getElementById('estadoRobo');


    /*
    |--------------------------------------------------------------------------
    | DADOS DO BANCO
    |--------------------------------------------------------------------------
    */

    let medicoes = [];

    let rota = [];

    let rotaVisual = [];

    let pontosClima = [];


    /*
    |--------------------------------------------------------------------------
    | OBJETOS DO MAPA
    |--------------------------------------------------------------------------
    */

    const objetos = [

        {
            tipo: 'Árvore',
            x: 71,
            y: 78,
            icone: '🌳'
        },

        {
            tipo: 'Obstáculo',
            x: 55,
            y: 49,
            icone: '⚠'
        },

        {
            tipo: 'Irrigação',
            x: 28,
            y: 61,
            icone: '💧'
        }

    ];


    /*
    |--------------------------------------------------------------------------
    | ESTADO DA ANIMAÇÃO
    |--------------------------------------------------------------------------
    */

    let pontoAtual = 0;

    let progresso = 0;

    let distanciaPercorrida = 0;

    let distanciaTotal = 0;


    /*
    |--------------------------------------------------------------------------
    | ESTADO DE EXECUÇÃO (Iniciar / Parar / Reiniciar)
    |--------------------------------------------------------------------------
    */

    let emExecucao = false;

    let animacaoId = null;

    let dadosCarregados = false;


    /*
    |--------------------------------------------------------------------------
    | ATUALIZA BOTÕES
    |--------------------------------------------------------------------------
    */

    function atualizarBotoes() {

        if (btnIniciar) {

            btnIniciar.disabled =
                emExecucao ||
                !dadosCarregados ||
                rota.length < 2;

        }


        if (btnParar) {

            btnParar.disabled =
                !emExecucao;

        }


        if (btnReiniciar) {

            btnReiniciar.disabled =
                !dadosCarregados;

        }


        if (estadoRoboEl) {

            if (emExecucao) {

                estadoRoboEl.innerText =
                    'Em movimento';

                estadoRoboEl.className =
                    'status-ok';

            } else {

                estadoRoboEl.innerText =
                    'Parado';

                estadoRoboEl.className =
                    'status-paused';

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

    function calcularDistancia(a, b) {

        const dx =
            b.x - a.x;

        const dy =
            b.y - a.y;


        return Math.sqrt(
            dx * dx +
            dy * dy
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DISTÂNCIA TOTAL
    |--------------------------------------------------------------------------
    */

    function calcularDistanciaTotal() {

        distanciaTotal = 0;


        for (
            let i = 1;
            i < rota.length;
            i++
        ) {

            distanciaTotal +=
                calcularDistancia(
                    rota[i - 1],
                    rota[i]
                );

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

        if (
            rota.length === 0
        ) {

            rotaVisual = [];

            pontosClima = [];

            return;

        }


        let menorX =
            rota[0].x;

        let maiorX =
            rota[0].x;

        let menorY =
            rota[0].y;

        let maiorY =
            rota[0].y;


        rota.forEach(function (ponto) {

            if (
                ponto.x < menorX
            ) {

                menorX =
                    ponto.x;

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


            if (
                ponto.y > maiorY
            ) {

                maiorY =
                    ponto.y;

            }

        });


        let intervaloX =
            maiorX - menorX;

        let intervaloY =
            maiorY - menorY;


        if (
            intervaloX === 0
        ) {

            intervaloX = 1;

        }


        if (
            intervaloY === 0
        ) {

            intervaloY = 1;

        }


        /*
        |--------------------------------------------------------------------------
        | ESPAÇO VISUAL
        |--------------------------------------------------------------------------
        */

        const visualMinimo = 8;

        const visualMaximo = 92;

        const visualTamanho =
            visualMaximo -
            visualMinimo;


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
                calcularDistancia(

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

        const margem = 55;


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

        pontosClima.forEach(
            function (ponto) {

                const px =
                    mapa.x(
                        ponto.x
                    );

                const py =
                    mapa.y(
                        ponto.y
                    );


                const cor =
                    corTemperatura(
                        ponto.temperatura
                    );


                /*
                |----------------------------------------------------------------------
                | RAIO MENOR PARA NÃO ESCONDER A ROTA
                |----------------------------------------------------------------------
                */

                const raio =
                    Math.min(
                        mapa.largura,
                        mapa.altura
                    ) * 0.075;


                const gradiente =
                    ctx.createRadialGradient(

                        px,
                        py,
                        0,

                        px,
                        py,
                        raio

                    );


                gradiente.addColorStop(

                    0,

                    `rgba(
                        ${cor.r},
                        ${cor.g},
                        ${cor.b},
                        0.16
                    )`

                );


                gradiente.addColorStop(

                    1,

                    `rgba(
                        ${cor.r},
                        ${cor.g},
                        ${cor.b},
                        0
                    )`

                );


                ctx.fillStyle =
                    gradiente;


                ctx.beginPath();

                ctx.arc(
                    px,
                    py,
                    raio,
                    0,
                    Math.PI * 2
                );

                ctx.fill();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | VALORES DOS EIXOS
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            'rgba(255,255,255,0.55)';

        ctx.font =
            '11px Arial';

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

        pontosClima.forEach(
            function (ponto, index) {

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
                    6,
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


                /*
                |----------------------------------------------------------------------
                | NÚMERO DO PONTO
                |----------------------------------------------------------------------
                */

                ctx.fillStyle =
                    'rgba(255,255,255,0.75)';

                ctx.font =
                    '9px Arial';

                ctx.textAlign =
                    'center';


                ctx.fillText(

                    index + 1,

                    x,

                    y - 10

                );

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
        | INÍCIO
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
                'bold 10px Arial';

            ctx.textAlign =
                'left';


            ctx.fillText(

                'INÍCIO',

                inicioX + 15,

                inicioY + 4

            );

        }


        /*
        |--------------------------------------------------------------------------
        | DESTINO
        |--------------------------------------------------------------------------
        */

        const destinoX =
            mapa.x(
                robo.destinoX
            );

        const destinoY =
            mapa.y(
                robo.destinoY
            );


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


        ctx.fillStyle =
            '#f8fafc';

        ctx.font =
            'bold 10px Arial';

        ctx.textAlign =
            'left';


        ctx.fillText(

            'DESTINO',

            destinoX + 19,

            destinoY + 4

        );


        /*
        |--------------------------------------------------------------------------
        | OBJETOS DETECTADOS
        |--------------------------------------------------------------------------
        */

        objetos.forEach(
            function (objeto) {

                const x =
                    mapa.x(
                        objeto.x
                    );

                const y =
                    mapa.y(
                        objeto.y
                    );


                /*
                |----------------------------------------------------------------------
                | ÁREA DE DETECÇÃO
                |----------------------------------------------------------------------
                */

                ctx.beginPath();

                ctx.arc(
                    x,
                    y,
                    22,
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
                    8,
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
                    'bold 11px Arial';

                ctx.textAlign =
                    'left';


                ctx.fillText(

                    objeto.tipo,

                    x + 15,

                    y - 10

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
            32,
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


            const tamanho = 34;


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


            const ponta = 8;


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
            12,
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
            'bold 12px Arial';

        ctx.textAlign =
            'left';


        ctx.fillText(

            'ROBÔ',

            roboX + 19,

            roboY - 21

        );


        /*
        |--------------------------------------------------------------------------
        | COORDENADAS
        |--------------------------------------------------------------------------
        */

        const textoX =
            'X: ' +
            robo.x.toFixed(1);


        const textoY =
            'Y: ' +
            robo.y.toFixed(1);


        /*
        |--------------------------------------------------------------------------
        | FUNDO DAS COORDENADAS
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            'rgba(10,20,16,0.94)';


        ctx.beginPath();


        if (
            ctx.roundRect
        ) {

            ctx.roundRect(

                roboX + 15,

                roboY + 7,

                104,

                27,

                6

            );

        } else {

            ctx.rect(

                roboX + 15,

                roboY + 7,

                104,

                27

            );

        }


        ctx.fill();


        /*
        |--------------------------------------------------------------------------
        | X
        |--------------------------------------------------------------------------
        */

        ctx.font =
            'bold 11px Arial';

        ctx.fillStyle =
            '#69c174';

        ctx.textAlign =
            'left';


        ctx.fillText(

            textoX,

            roboX + 22,

            roboY + 25

        );


        /*
        |--------------------------------------------------------------------------
        | Y
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#55b9d9';


        ctx.fillText(

            textoY,

            roboX + 72,

            roboY + 25

        );


        /*
        |--------------------------------------------------------------------------
        | TEMPERATURA
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#fbbf24';

        ctx.font =
            'bold 10px Arial';

        ctx.textAlign =
            'left';


        ctx.fillText(

            robo.temperatura.toFixed(1) +
            '°C',

            roboX + 18,

            roboY + 43

        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZA INFORMAÇÕES
    |--------------------------------------------------------------------------
    */

    function atualizarInformacoes() {

        const posicaoX =
            document.getElementById(
                'posicaoX'
            );

        const posicaoY =
            document.getElementById(
                'posicaoY'
            );

        const distancia =
            document.getElementById(
                'distancia'
            );

        const direcao =
            document.getElementById(
                'direcao'
            );

        const velocidade =
            document.getElementById(
                'velocidade'
            );

        const bateria =
            document.getElementById(
                'bateria'
            );

        const temperatura =
            document.getElementById(
                'temperatura'
            );

        const umidade =
            document.getElementById(
                'umidade'
            );

        const vento =
            document.getElementById(
                'vento'
            );

        const chuva =
            document.getElementById(
                'chuva'
            );


        if (posicaoX) {

            posicaoX.innerText =
                robo.x.toFixed(1);

        }


        if (posicaoY) {

            posicaoY.innerText =
                robo.y.toFixed(1);

        }


        if (distancia) {

            distancia.innerText =
                distanciaPercorrida.toFixed(1);

        }


        if (direcao) {

            direcao.innerText =
                Math.round(
                    robo.direcao
                ) +
                '°';

        }


        if (velocidade) {

            velocidade.innerText =
                robo.velocidade.toFixed(1);

        }


        if (bateria) {

            bateria.innerText =
                Math.round(
                    robo.bateria
                ) +
                '%';

        }


        if (temperatura) {

            temperatura.innerText =
                robo.temperatura.toFixed(1) +
                '°C';

        }


        if (umidade) {

            umidade.innerText =
                Math.round(
                    robo.umidade
                ) +
                '%';

        }


        if (vento) {

            vento.innerText =
                robo.vento.toFixed(1) +
                ' km/h';

        }


        if (chuva) {

            chuva.innerText =
                robo.chuva +
                ' mm';

        }


        const ultimaAtualizacao =
            document.getElementById(
                'ultimaAtualizacao'
            );


        if (ultimaAtualizacao) {

            ultimaAtualizacao.innerText =
                new Date().toLocaleTimeString(
                    'pt-BR'
                );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ANIMAÇÃO
    |--------------------------------------------------------------------------
    */

    function animar() {

        if (
            !emExecucao
        ) {

            return;

        }


        if (
            rota.length < 2
        ) {

            emExecucao = false;

            atualizarBotoes();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | VELOCIDADE
        |--------------------------------------------------------------------------
        */

        progresso += 0.006;


        /*
        |--------------------------------------------------------------------------
        | AVANÇA PONTO
        |--------------------------------------------------------------------------
        */

        if (
            progresso >= 1
        ) {

            progresso = 0;

            pontoAtual++;

        }


        /*
        |--------------------------------------------------------------------------
        | FINAL DA ROTA
        |--------------------------------------------------------------------------
        */

        if (
            pontoAtual >=
            rota.length - 1
        ) {

            pontoAtual =
                rota.length - 1;

            progresso = 0;


            distanciaPercorrida =
                distanciaTotal;


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

        let distancia = 0;


        for (
            let i = 1;
            i <= pontoAtual;
            i++
        ) {

            distancia +=
                calcularDistancia(

                    rota[i - 1],

                    rota[i]

                );

        }


        /*
        |--------------------------------------------------------------------------
        | TRECHO ATUAL
        |--------------------------------------------------------------------------
        */

        const trechoAtual =
            calcularDistancia(

                rota[pontoAtual],

                rota[pontoAtual + 1]

            );


        distancia +=
            trechoAtual *
            progresso;


        distanciaPercorrida =
            distancia;


        /*
        |--------------------------------------------------------------------------
        | BATERIA
        |--------------------------------------------------------------------------
        */

        if (
            distanciaTotal > 0
        ) {

            robo.bateria =
                Math.max(

                    15,

                    87 -
                    (
                        distanciaPercorrida /
                        distanciaTotal
                    ) *
                    20

                );

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

        animacaoId =
            requestAnimationFrame(
                animar
            );

    }


    /*
    |--------------------------------------------------------------------------
    | INICIAR
    |--------------------------------------------------------------------------
    */

    function iniciarAnimacao() {

        if (
            emExecucao ||
            rota.length < 2
        ) {

            return;

        }


        emExecucao = true;

        atualizarBotoes();


        animacaoId =
            requestAnimationFrame(
                animar
            );

    }


    /*
    |--------------------------------------------------------------------------
    | PARAR
    |--------------------------------------------------------------------------
    */

    function pararAnimacao() {

        emExecucao = false;


        if (
            animacaoId
        ) {

            cancelAnimationFrame(
                animacaoId
            );

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


        if (
            rota.length > 0
        ) {

            robo.x =
                rota[0].x;

            robo.y =
                rota[0].y;


            robo.temperatura =
                rota[0].temperatura;


            robo.umidade =
                rota[0].umidade;

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

    if (btnIniciar) {

        btnIniciar.addEventListener(
            'click',
            iniciarAnimacao
        );

    }


    if (btnParar) {

        btnParar.addEventListener(
            'click',
            pararAnimacao
        );

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

    function carregarMedicoes() {

        fetch(
            '/monitoramento/dados'
        )

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

                medicoes =
                    data;


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
                                        medicao.x
                                    ),

                                y:
                                    parseFloat(
                                        medicao.y
                                    ),

                                temperatura:
                                    parseFloat(
                                        medicao.temperatura
                                    ),

                                umidade:
                                    parseFloat(
                                        medicao.umidade
                                    ),

                                recebidaEm:
                                    medicao.recebida_em

                            };

                        }
                    )
                    .filter(
                        function (ponto) {

                            return (

                                !isNaN(
                                    ponto.x
                                )

                                &&

                                !isNaN(
                                    ponto.y
                                )

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

                            new Date(
                                a.recebidaEm
                            )

                            -

                            new Date(
                                b.recebidaEm
                            )

                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | NENHUM DADO
                |--------------------------------------------------------------------------
                */

                if (
                    rota.length === 0
                ) {

                    console.warn(
                        'Nenhuma medição encontrada.'
                    );

                    dadosCarregados =
                        true;

                    atualizarBotoes();

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


                /*
                |--------------------------------------------------------------------------
                | COMEÇA PARADO — O USUÁRIO INICIA PELO BOTÃO
                |--------------------------------------------------------------------------
                */

            }
        )

        .catch(
            function (error) {

                console.error(

                    'Erro ao carregar medições:',

                    error

                );

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

    carregarMedicoes();

});

</script>

@endsection