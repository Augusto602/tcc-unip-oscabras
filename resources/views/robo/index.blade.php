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


        <div class="robo-online">

            <span class="online-dot"></span>

            ROBÔ ONLINE

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
   DASHBOARD
========================================================= */

.robo-dashboard {
    width: 100%;
    padding: 18px 22px;
    box-sizing: border-box;
    color: #e6eee9;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.robo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.robo-title-line {
    display: flex;
    align-items: center;
    gap: 12px;
}

.robo-title-icon {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #1d392d;
    border: 1px solid #315b46;
    border-radius: 10px;

    color: #69c174;
    font-size: 19px;
}

.robo-title-line h2 {
    margin: 0;

    color: #eef5f1;

    font-size: 22px;
    font-weight: 600;
}

.robo-title-line p {
    margin: 3px 0 0;

    color: #8fa39a;

    font-size: 13px;
}


/* =========================================================
   STATUS ONLINE
========================================================= */

.robo-online {
    display: flex;
    align-items: center;
    gap: 7px;

    padding: 7px 12px;

    background: #183125;
    border: 1px solid #28533a;

    border-radius: 20px;

    color: #71d17d;

    font-size: 10px;
    font-weight: 700;
}

.online-dot {
    width: 7px;
    height: 7px;

    background: #55c965;

    border-radius: 50%;

    box-shadow: 0 0 8px rgba(85, 201, 101, .7);
}


/* =========================================================
   INDICADORES
========================================================= */

.robo-stats {
    display: grid;

    grid-template-columns:
        repeat(6, minmax(130px, 1fr));

    gap: 10px;

    margin-bottom: 15px;
}

.robo-stat {
    min-height: 82px;

    display: flex;
    align-items: center;

    gap: 10px;

    padding: 12px;

    background: #17221f;

    border: 1px solid #263a34;

    border-radius: 9px;

    box-sizing: border-box;

    transition: .2s;
}

.robo-stat:hover {
    border-color: #3c674f;

    transform: translateY(-1px);
}


/* =========================================================
   ÍCONES DOS INDICADORES
========================================================= */

.stat-icon {
    width: 36px;
    height: 36px;

    min-width: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    font-size: 15px;
}


/* Verde */

.stat-icon.green {
    background: #193629;
    color: #69c174;
}


/* Azul */

.stat-icon.blue {
    background: #182e38;
    color: #55b9d9;
}


/* Laranja */

.stat-icon.orange {
    background: #38291b;
    color: #e7a65c;
}


/* Roxo */

.stat-icon.purple {
    background: #2d2338;
    color: #b78be0;
}


/* Ciano */

.stat-icon.cyan {
    background: #173239;
    color: #62c8d5;
}


/* Bateria */

.stat-icon.battery-icon {
    background: #24331f;
    color: #91c96d;
}


/* =========================================================
   TEXTO DOS INDICADORES
========================================================= */

.stat-info {
    min-width: 0;
}

.stat-info span {
    display: block;

    color: #91a39b;

    font-size: 11px;

    margin-bottom: 2px;

    white-space: nowrap;
}

.stat-info strong {
    display: inline-block;

    color: #edf5f0;

    font-size: 19px;

    font-weight: 600;
}

.stat-info small {
    color: #72847c;

    font-size: 9px;

    margin-left: 2px;
}


/* =========================================================
   MAPA + PAINEL LATERAL
========================================================= */

.robo-content {
    display: grid;

    grid-template-columns:
        minmax(0, 2.2fr)
        minmax(280px, .8fr);

    gap: 14px;

    align-items: stretch;
}


/* =========================================================
   PAINEL DO MAPA
========================================================= */

.map-panel {
    min-width: 0;

    background: #17221f;

    border: 1px solid #263a34;

    border-radius: 10px;

    overflow: hidden;
}


/* =========================================================
   CABEÇALHO DO MAPA
========================================================= */

.panel-header {
    min-height: 55px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 10px 15px;

    background: #192622;

    border-bottom: 1px solid #293c35;
}

.panel-header h3 {
    margin: 0;

    color: #e9f1ed;

    font-size: 14px;

    font-weight: 600;
}

.panel-header h3 i {
    margin-right: 6px;

    color: #69c174;
}

.panel-header span {
    display: block;

    margin-top: 3px;

    color: #7e9389;

    font-size: 10px;
}


/* =========================================================
   STATUS GPS
========================================================= */

.map-status {
    display: flex;
    align-items: center;
    gap: 5px;

    padding: 5px 9px;

    background: #172e24;

    border: 1px solid #285239;

    border-radius: 15px;

    color: #6bc978;

    font-size: 9px;

    font-weight: 700;
}

.map-status i {
    font-size: 9px;
}


/* =========================================================
   ÁREA DO MAPA
========================================================= */

.map-wrapper {
    position: relative;

    width: 100%;

    height: 525px;

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

    left: 12px;
    bottom: 12px;

    display: flex;
    flex-wrap: wrap;

    gap: 8px;

    padding: 8px 10px;

    background: rgba(16, 26, 23, .94);

    border: 1px solid #30443c;

    border-radius: 7px;

    color: #aabbb3;

    font-size: 9px;
}

.map-legend > div {
    display: flex;
    align-items: center;
    gap: 4px;
}

.legend {
    width: 7px;
    height: 7px;

    display: inline-block;

    border-radius: 50%;
}

.legend.robot {
    background: #5bd36b;
}

.legend.route {
    width: 13px;
    height: 2px;

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

    gap: 10px;

    min-width: 0;
}


/* =========================================================
   PAINÉIS DE INFORMAÇÃO
========================================================= */

.info-panel {
    background: #17221f;

    border: 1px solid #263a34;

    border-radius: 9px;

    padding: 12px;
}


/* =========================================================
   TÍTULO DOS PAINÉIS
========================================================= */

.info-panel-title {
    display: flex;

    align-items: center;

    gap: 7px;

    margin-bottom: 10px;

    color: #dce8e2;

    font-size: 12px;

    font-weight: 600;
}

.info-panel-title i {
    color: #69c174;
}


/* =========================================================
   CLIMA
========================================================= */

.environment-grid {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 7px;
}

.environment-item {
    display: grid;

    grid-template-columns:
        22px 1fr;

    align-items: center;

    padding: 8px;

    background: #121c19;

    border: 1px solid #26352f;

    border-radius: 7px;
}

.environment-item i {
    grid-row: span 2;

    color: #69b9c6;

    font-size: 13px;
}

.environment-item span {
    color: #7f948a;

    font-size: 9px;
}

.environment-item strong {
    color: #e2ebe6;

    font-size: 12px;

    font-weight: 600;
}


/* =========================================================
   DETECÇÕES
========================================================= */

.detection-count {
    margin-left: auto;

    min-width: 20px;
    height: 20px;

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

    gap: 6px;
}

.detection {
    display: flex;

    align-items: center;

    gap: 9px;

    padding: 7px;

    background: #121c19;

    border: 1px solid #26352f;

    border-radius: 7px;
}

.detection-icon {
    width: 30px;
    height: 30px;

    min-width: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    font-size: 12px;
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

    color: #dce8e2;

    font-size: 11px;
}

.detection span {
    display: block;

    color: #71847b;

    font-size: 9px;

    margin-top: 2px;
}


/* =========================================================
   SISTEMA
========================================================= */

.system-row {
    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 7px 0;

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
    width: 17px;

    color: #628f73;
}

.status-ok {
    color: #69c878;

    font-size: 10px;
}

#ultimaAtualizacao {
    color: #b2c2ba;
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
            minmax(260px, .8fr);
    }

}


@media (max-width: 1000px) {

    .robo-content {
        grid-template-columns: 1fr;
    }

    .map-wrapper {
        height: 450px;
    }

}


@media (max-width: 700px) {

    .robo-dashboard {
        padding: 12px;
    }

    .robo-header {
        align-items: flex-start;
        gap: 10px;
    }

    .robo-online {
        font-size: 8px;
    }

    .robo-stats {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .map-wrapper {
        height: 400px;
    }

}


@media (max-width: 480px) {

    .robo-stats {
        grid-template-columns: 1fr;
    }

    .robo-title-line h2 {
        font-size: 18px;
    }

    .environment-grid {
        grid-template-columns: 1fr;
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
    | ROTA DO ROBÔ
    |--------------------------------------------------------------------------
    */

    const rota = [

        { x: 10, y: 15 },
        { x: 18, y: 20 },
        { x: 25, y: 30 },
        { x: 32, y: 42 },
        { x: 40, y: 50 },
        { x: 48, y: 58 },
        { x: 55, y: 52 },
        { x: 62, y: 60 },
        { x: 68, y: 70 },
        { x: 75, y: 68 },
        { x: 82, y: 76 },
        { x: 90, y: 85 }

    ];


    /*
    |--------------------------------------------------------------------------
    | TEMPERATURA POR REGIÃO
    |--------------------------------------------------------------------------
    |
    | Futuramente estes dados podem vir do banco.
    |
    */

    const pontosClima = [

        {
            x: 15,
            y: 20,
            temperatura: 22.5,
            umidade: 74
        },

        {
            x: 30,
            y: 30,
            temperatura: 23.8,
            umidade: 70
        },

        {
            x: 45,
            y: 45,
            temperatura: 25.2,
            umidade: 67
        },

        {
            x: 60,
            y: 55,
            temperatura: 27.4,
            umidade: 61
        },

        {
            x: 75,
            y: 70,
            temperatura: 29.1,
            umidade: 56
        },

        {
            x: 90,
            y: 85,
            temperatura: 30.2,
            umidade: 52
        }

    ];


    /*
    |--------------------------------------------------------------------------
    | OBJETOS DETECTADOS
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
    | ESTADO
    |--------------------------------------------------------------------------
    */

    let pontoAtual = 0;

    let progresso = 0;

    let distanciaPercorrida = 0;

    let distanciaTotal = 0;


    /*
    |--------------------------------------------------------------------------
    | DISTÂNCIA ENTRE DOIS PONTOS
    |--------------------------------------------------------------------------
    */

    function calcularDistancia(a, b) {

        const dx = b.x - a.x;

        const dy = b.y - a.y;

        return Math.sqrt(
            dx * dx +
            dy * dy
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DISTÂNCIA TOTAL DA ROTA
    |--------------------------------------------------------------------------
    */

    rota.forEach(function (ponto, index) {

        if (index > 0) {

            distanciaTotal +=
                calcularDistancia(
                    rota[index - 1],
                    ponto
                );

        }

    });


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
    | TEMPERATURA DA REGIÃO
    |--------------------------------------------------------------------------
    |
    | Procura os pontos climáticos mais próximos.
    | Isso permite criar uma transição suave.
    |
    */

    function obterClima(x, y) {

        let menorDistancia = Infinity;

        let pontoMaisProximo = pontosClima[0];


        pontosClima.forEach(function (ponto) {

            const distancia = Math.sqrt(

                Math.pow(
                    ponto.x - x,
                    2
                )

                +

                Math.pow(
                    ponto.y - y,
                    2
                )

            );


            if (distancia < menorDistancia) {

                menorDistancia =
                    distancia;

                pontoMaisProximo =
                    ponto;

            }

        });


        return pontoMaisProximo;

    }


    /*
    |--------------------------------------------------------------------------
    | TAMANHO DO CANVAS
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
    | CONVERTER COORDENADAS
    |--------------------------------------------------------------------------
    */

    function criarConversao(width, height) {

        const margem = 45;

        const larguraMapa =
            width - margem * 2;

        const alturaMapa =
            height - margem * 2;


        return {

            margem: margem,

            largura: larguraMapa,

            altura: alturaMapa,


            x: function (valor) {

                return margem +
                    (valor / 100) *
                    larguraMapa;

            },


            y: function (valor) {

                return height -
                    margem -
                    (valor / 100) *
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

        if (temp < 22) {

            return {
                r: 59,
                g: 130,
                b: 246
            };

        }

        if (temp < 24) {

            return {
                r: 56,
                g: 189,
                b: 248
            };

        }

        if (temp < 26) {

            return {
                r: 74,
                g: 222,
                b: 128
            };

        }

        if (temp < 28) {

            return {
                r: 250,
                g: 204,
                b: 21
            };

        }

        if (temp < 30) {

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
    | MAPA
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
        | MAPA TÉRMICO
        |--------------------------------------------------------------------------
        */

        pontosClima.forEach(function (ponto) {

            const px =
                mapa.x(ponto.x);

            const py =
                mapa.y(ponto.y);


            const cor =
                corTemperatura(
                    ponto.temperatura
                );


            const raio =
                Math.min(
                    mapa.largura,
                    mapa.altura
                ) * 0.18;


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
                    0.20
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

        });


        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        ctx.strokeStyle =
            'rgba(130,170,130,0.10)';

        ctx.lineWidth = 1;


        for (
            let i = 0;
            i <= 10;
            i++
        ) {

            const x =
                mapa.margem +
                (i / 10) *
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
                (i / 10) *
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
            'rgba(72,187,120,0.08)';

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
        | EIXOS
        |--------------------------------------------------------------------------
        */

        ctx.strokeStyle =
            'rgba(255,255,255,0.25)';

        ctx.lineWidth = 1;


        ctx.beginPath();

        ctx.moveTo(
            mapa.margem,
            height - mapa.margem
        );

        ctx.lineTo(
            width - mapa.margem,
            height - mapa.margem
        );

        ctx.stroke();


        ctx.beginPath();

        ctx.moveTo(
            mapa.margem,
            height - mapa.margem
        );

        ctx.lineTo(
            mapa.margem,
            mapa.margem
        );

        ctx.stroke();


        /*
        |--------------------------------------------------------------------------
        | VALORES X / Y
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            'rgba(255,255,255,0.45)';

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


            const x =
                mapa.margem +
                (i / 10) *
                mapa.largura;


            ctx.fillText(
                valor,
                x,
                height - 25
            );


            const y =
                height -
                mapa.margem -
                (i / 10) *
                mapa.altura;


            ctx.textAlign =
                'right';


            ctx.fillText(
                valor,
                mapa.margem - 8,
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

        pontosClima.forEach(function (ponto) {

            const x =
                mapa.x(ponto.x);

            const y =
                mapa.y(ponto.y);


            const cor =
                corTemperatura(
                    ponto.temperatura
                );


            /*
            |--------------------------------------------------------------
            | PONTO
            |--------------------------------------------------------------
            */

            ctx.beginPath();

            ctx.arc(
                x,
                y,
                5,
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


            /*
            |--------------------------------------------------------------
            | BORDA
            |--------------------------------------------------------------
            */

            ctx.strokeStyle =
                'rgba(255,255,255,0.55)';

            ctx.lineWidth = 1;

            ctx.stroke();


            /*
            |--------------------------------------------------------------
            | TEMPERATURA
            |--------------------------------------------------------------
            */

            ctx.fillStyle =
                '#dce8e2';

            ctx.font =
                '10px Arial';

            ctx.textAlign =
                'left';


            ctx.fillText(

                ponto.temperatura.toFixed(1) +
                '°C',

                x + 8,
                y + 3

            );

        });


        /*
        |--------------------------------------------------------------------------
        | ROTA FUTURA
        |--------------------------------------------------------------------------
        */

        ctx.beginPath();


        rota.forEach(function (ponto, index) {

            const x =
                mapa.x(ponto.x);

            const y =
                mapa.y(ponto.y);


            if (index === 0) {

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

        });


        ctx.strokeStyle =
            'rgba(255,255,255,0.12)';

        ctx.lineWidth = 2;

        ctx.setLineDash([
            6,
            6
        ]);

        ctx.stroke();

        ctx.setLineDash([]);


        /*
        |--------------------------------------------------------------------------
        | TRAJETO PERCORRIDO
        |--------------------------------------------------------------------------
        */

        if (pontoAtual >= 0) {

            ctx.beginPath();


            for (
                let i = 0;
                i <= pontoAtual;
                i++
            ) {

                const ponto =
                    rota[i];


                const x =
                    mapa.x(ponto.x);

                const y =
                    mapa.y(ponto.y);


                if (i === 0) {

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
            |--------------------------------------------------------------
            | TRECHO ATUAL
            |--------------------------------------------------------------
            */

            if (
                pontoAtual <
                rota.length - 1
            ) {

                const inicio =
                    rota[pontoAtual];

                const fim =
                    rota[pontoAtual + 1];


                const x =
                    interpolar(
                        inicio.x,
                        fim.x,
                        progresso
                    );


                const y =
                    interpolar(
                        inicio.y,
                        fim.y,
                        progresso
                    );


                ctx.lineTo(
                    mapa.x(x),
                    mapa.y(y)
                );

            }


            /*
            |--------------------------------------------------------------
            | DESENHO
            |--------------------------------------------------------------
            */

            ctx.strokeStyle =
                '#4ade80';

            ctx.lineWidth = 4;

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

        rota.forEach(function (
            ponto,
            index
        ) {

            const x =
                mapa.x(ponto.x);

            const y =
                mapa.y(ponto.y);


            ctx.beginPath();

            ctx.arc(
                x,
                y,
                3,
                0,
                Math.PI * 2
            );


            ctx.fillStyle =
                index <= pontoAtual
                    ? '#4ade80'
                    : 'rgba(255,255,255,0.25)';


            ctx.fill();

        });


        /*
        |--------------------------------------------------------------------------
        | INÍCIO
        |--------------------------------------------------------------------------
        */

        const inicioX =
            mapa.x(
                rota[0].x
            );

        const inicioY =
            mapa.y(
                rota[0].y
            );


        ctx.beginPath();

        ctx.arc(
            inicioX,
            inicioY,
            8,
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
            inicioX + 12,
            inicioY + 4
        );


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
            13,
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
            4,
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


        ctx.fillText(
            'DESTINO',
            destinoX + 18,
            destinoY + 4
        );


        /*
        |--------------------------------------------------------------------------
        | OBJETOS
        |--------------------------------------------------------------------------
        */

        objetos.forEach(function (objeto) {

            const x =
                mapa.x(objeto.x);

            const y =
                mapa.y(objeto.y);


            /*
            |--------------------------------------------------------------
            | ÁREA DE DETECÇÃO
            |--------------------------------------------------------------
            */

            ctx.beginPath();

            ctx.arc(
                x,
                y,
                16,
                0,
                Math.PI * 2
            );


            ctx.fillStyle =
                'rgba(245,158,11,0.10)';

            ctx.fill();


            ctx.strokeStyle =
                'rgba(245,158,11,0.30)';

            ctx.stroke();


            /*
            |--------------------------------------------------------------
            | PONTO
            |--------------------------------------------------------------
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
                '#f59e0b';

            ctx.fill();


            /*
            |--------------------------------------------------------------
            | NOME
            |--------------------------------------------------------------
            */

            ctx.fillStyle =
                '#f8fafc';

            ctx.font =
                '11px Arial';

            ctx.textAlign =
                'left';


            ctx.fillText(
                objeto.tipo,
                x + 12,
                y - 8
            );

        });


        /*
        |--------------------------------------------------------------------------
        | POSIÇÃO ATUAL DO ROBÔ
        |--------------------------------------------------------------------------
        */

        if (
            pontoAtual <
            rota.length - 1
        ) {

            const inicio =
                rota[pontoAtual];

            const fim =
                rota[pontoAtual + 1];


            robo.x =
                interpolar(
                    inicio.x,
                    fim.x,
                    progresso
                );


            robo.y =
                interpolar(
                    inicio.y,
                    fim.y,
                    progresso
                );

        } else {

            robo.x =
                rota[
                    rota.length - 1
                ].x;

            robo.y =
                rota[
                    rota.length - 1
                ].y;

        }


        const roboX =
            mapa.x(robo.x);

        const roboY =
            mapa.y(robo.y);


        /*
        |--------------------------------------------------------------------------
        | CLIMA NA POSIÇÃO DO ROBÔ
        |--------------------------------------------------------------------------
        */

        const climaAtual =
            obterClima(
                robo.x,
                robo.y
            );


        robo.temperatura =
            climaAtual.temperatura;

        robo.umidade =
            climaAtual.umidade;


        /*
        |--------------------------------------------------------------------------
        | CÍRCULO DE DETECÇÃO
        |--------------------------------------------------------------------------
        */

        ctx.beginPath();

        ctx.arc(
            roboX,
            roboY,
            25,
            0,
            Math.PI * 2
        );


        ctx.fillStyle =
            'rgba(74,222,128,0.08)';

        ctx.fill();


        ctx.strokeStyle =
            'rgba(74,222,128,0.25)';

        ctx.lineWidth = 1;

        ctx.stroke();


        /*
        |--------------------------------------------------------------------------
        | DIREÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            pontoAtual <
            rota.length - 1
        ) {

            const inicio =
                rota[pontoAtual];

            const fim =
                rota[pontoAtual + 1];


            const dx =
                fim.x - inicio.x;

            const dy =
                fim.y - inicio.y;


            robo.direcao =
                Math.atan2(
                    dy,
                    dx
                ) *
                180 /
                Math.PI;


            if (
                robo.direcao < 0
            ) {

                robo.direcao += 360;

            }


            /*
            |--------------------------------------------------------------
            | SETA
            |--------------------------------------------------------------
            */

            const angulo =
                Math.atan2(
                    -dy,
                    dx
                );


            const tamanhoSeta =
                25;


            const setaX =
                roboX +
                Math.cos(
                    angulo
                ) *
                tamanhoSeta;


            const setaY =
                roboY +
                Math.sin(
                    angulo
                ) *
                tamanhoSeta;


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


            /*
            |--------------------------------------------------------------
            | PONTA DA SETA
            |--------------------------------------------------------------
            */

            const tamanhoPonta =
                7;


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
                tamanhoPonta,

                setaY -
                Math.sin(
                    angulo - 0.5
                ) *
                tamanhoPonta

            );


            ctx.lineTo(

                setaX -
                Math.cos(
                    angulo + 0.5
                ) *
                tamanhoPonta,

                setaY -
                Math.sin(
                    angulo + 0.5
                ) *
                tamanhoPonta

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
            10,
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
        | IDENTIFICAÇÃO
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
            roboX + 17,
            roboY - 12
        );


        ctx.fillStyle =
            'rgba(255,255,255,0.60)';

        ctx.font =
            '10px Arial';


        ctx.fillText(

            'X: ' +
            robo.x.toFixed(1) +
            ' | Y: ' +
            robo.y.toFixed(1),

            roboX + 17,
            roboY + 3

        );


        /*
        |--------------------------------------------------------------------------
        | TEMPERATURA DO ROBÔ
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#fbbf24';

        ctx.font =
            'bold 10px Arial';


        ctx.fillText(

            robo.temperatura.toFixed(1) +
            '°C',

            roboX + 17,
            roboY + 17

        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PAINEL
    |--------------------------------------------------------------------------
    */

    function atualizarInformacoes() {

        const x =
            document.getElementById(
                'posicaoX'
            );

        const y =
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


        if (x) {

            x.innerText =
                robo.x.toFixed(1);

        }


        if (y) {

            y.innerText =
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
                robo.bateria +
                '%';

        }


        if (temperatura) {

            temperatura.innerText =
                robo.temperatura.toFixed(1) +
                '°C';

        }


        if (umidade) {

            umidade.innerText =
                robo.umidade +
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


        const ultima =
            document.getElementById(
                'ultimaAtualizacao'
            );


        if (ultima) {

            const agora =
                new Date();


            ultima.innerText =
                agora.toLocaleTimeString(
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
            pontoAtual >=
            rota.length - 1
        ) {

            desenharMapa();

            atualizarInformacoes();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | VELOCIDADE DA ANIMAÇÃO
        |--------------------------------------------------------------------------
        */

        progresso += 0.008;


        /*
        |--------------------------------------------------------------------------
        | CHEGOU AO PRÓXIMO PONTO
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
        | DISTÂNCIA DO TRECHO ATUAL
        |--------------------------------------------------------------------------
        */

        if (
            pontoAtual <
            rota.length - 1
        ) {

            const trecho =
                calcularDistancia(
                    rota[pontoAtual],
                    rota[pontoAtual + 1]
                );


            distancia +=
                trecho *
                progresso;

        }


        distanciaPercorrida =
            distancia;


        /*
        |--------------------------------------------------------------------------
        | REDUZ BATERIA
        |--------------------------------------------------------------------------
        */

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


        robo.bateria =
            Math.round(
                robo.bateria
            );


        /*
        |--------------------------------------------------------------------------
        | DESENHAR
        |--------------------------------------------------------------------------
        */

        desenharMapa();

        atualizarInformacoes();


        requestAnimationFrame(
            animar
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INICIALIZAÇÃO
    |--------------------------------------------------------------------------
    */

    ajustarCanvas();

    desenharMapa();

    atualizarInformacoes();


    /*
    |--------------------------------------------------------------------------
    | INICIAR
    |--------------------------------------------------------------------------
    */

    setTimeout(
        function () {

            requestAnimationFrame(
                animar
            );

        },
        800
    );


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

});

</script>

@endsection


