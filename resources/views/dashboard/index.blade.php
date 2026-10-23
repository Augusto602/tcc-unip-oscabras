@extends('layouts.app')

@section('title', 'Histórico de Rotas')

@section('content')

@php
    $rotas = $rotas ?? collect();
@endphp

<div class="historico-rotas">

    {{-- =========================================================
         CABEÇALHO
    ========================================================== --}}

    <div class="historico-header">

        <div class="historico-header-left">

            <div class="historico-icon">
                <i class="fa-solid fa-route"></i>
            </div>

            <div>
                <h2>Histórico de Rotas</h2>

                <p>
                    Visualize as rotas realizadas e operações em atividade
                </p>
            </div>

        </div>

    </div>


    {{-- =========================================================
         INDICADORES
    ========================================================== --}}

    <div class="historico-resumo">

        <div class="resumo-card">

            <div class="resumo-icon total">
                <i class="fa-solid fa-route"></i>
            </div>

            <div class="resumo-info">
                <span>Total de Rotas</span>

                <strong>
                    {{ $rotas->count() }}
                </strong>
            </div>

        </div>


        <div class="resumo-card">

            <div class="resumo-icon atividade">
                <i class="fa-solid fa-play"></i>
            </div>

            <div class="resumo-info">
                <span>Em atividade</span>

                <strong>
                    {{ $rotas->where('status', 'Em atividade')->count() }}
                </strong>
            </div>

        </div>


        <div class="resumo-card">

            <div class="resumo-icon finalizada">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="resumo-info">
                <span>Finalizadas</span>

                <strong>
                    {{ $rotas->where('status', 'Finalizada')->count() }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =========================================================
         PAINEL
    ========================================================== --}}

    <div class="historico-panel">

        <div class="historico-panel-header">

            <div>

                <h3>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Rotas registradas
                </h3>

                <span>
                    Histórico das movimentações registradas pelo robô
                </span>

            </div>

        </div>


        {{-- =====================================================
             DESKTOP - TABELA
        ====================================================== --}}

        <div class="historico-table-wrapper">

            <table class="historico-table">

                <thead>

                    <tr>
                        <th>Nome da rota</th>
                        <th>Data / Hora</th>
                        <th>Veículo</th>
                        <th>Duração</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($rotas as $rota)

                        <tr>

                            <td>

                                <div class="rota-nome">

                                    <div class="rota-marker">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $rota->nome }}
                                        </strong>

                                        <span>
                                            {{ $rota->codigo }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <div class="rota-data">

                                    <strong>
                                        {{ \Carbon\Carbon::parse($rota->data_inicio)->format('d/m/Y') }}
                                    </strong>

                                    <span>
                                        {{ \Carbon\Carbon::parse($rota->data_inicio)->format('H:i') }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                <div class="rota-veiculo">

                                    <i class="fa-solid fa-tractor"></i>

                                    <span>
                                        {{ $rota->veiculo }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                <div class="rota-duracao">

                                    <i class="fa-regular fa-clock"></i>

                                    <span>
                                        {{ $rota->duracao }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                @if ($rota->status == 'Em atividade')

                                    <span class="rota-status status-atividade">

                                        <span class="rota-status-dot"></span>

                                        Em atividade

                                    </span>

                                @else

                                    <span class="rota-status status-finalizada">

                                        <i class="fa-solid fa-check"></i>

                                        Finalizada

                                    </span>

                                @endif

                            </td>


                            <td>

                                <a
                                    href="/monitoramento?rota={{ urlencode($rota->codigo) }}"
                                    class="btn-visualizar-rota"
                                >

                                    <i class="fa-solid fa-chart-line"></i>

                                    Visualizar

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="historico-vazio">

                                    <div class="historico-vazio-icon">

                                        <i class="fa-solid fa-route"></i>

                                    </div>

                                    <strong>
                                        Nenhuma rota encontrada
                                    </strong>

                                    <span>
                                        Nenhuma movimentação foi registrada até o momento.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             MOBILE - CARDS
        ====================================================== --}}

        <div class="historico-mobile">

            @forelse ($rotas as $rota)

                <div class="rota-mobile-card">

                    <div class="rota-mobile-top">

                        <div class="rota-mobile-nome">

                            <div class="rota-marker">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>

                            <div>

                                <strong>
                                    {{ $rota->nome }}
                                </strong>

                                <span>
                                    {{ $rota->codigo }}
                                </span>

                            </div>

                        </div>


                        @if ($rota->status == 'Em atividade')

                            <span class="rota-status status-atividade">

                                <span class="rota-status-dot"></span>

                                Em atividade

                            </span>

                        @else

                            <span class="rota-status status-finalizada">

                                <i class="fa-solid fa-check"></i>

                                Finalizada

                            </span>

                        @endif

                    </div>


                    <div class="rota-mobile-dados">


                        <div class="rota-mobile-item">

                            <span class="mobile-label">
                                Data
                            </span>

                            <div class="mobile-value">

                                <i class="fa-regular fa-calendar"></i>

                                <div>

                                    <strong>
                                        {{ \Carbon\Carbon::parse($rota->data_inicio)->format('d/m/Y') }}
                                    </strong>

                                    <small>
                                        {{ \Carbon\Carbon::parse($rota->data_inicio)->format('H:i') }}
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="rota-mobile-item">

                            <span class="mobile-label">
                                Veículo
                            </span>

                            <div class="mobile-value">

                                <i class="fa-solid fa-tractor"></i>

                                <strong>
                                    {{ $rota->veiculo }}
                                </strong>

                            </div>

                        </div>


                        <div class="rota-mobile-item">

                            <span class="mobile-label">
                                Duração
                            </span>

                            <div class="mobile-value">

                                <i class="fa-regular fa-clock"></i>

                                <strong>
                                    {{ $rota->duracao }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <a
                        href="/monitoramento?rota={{ urlencode($rota->codigo) }}"
                        class="btn-mobile-rota"
                    >

                        <i class="fa-solid fa-chart-line"></i>

                        Visualizar rota

                    </a>

                </div>

            @empty

                <div class="historico-vazio mobile">

                    <div class="historico-vazio-icon">

                        <i class="fa-solid fa-route"></i>

                    </div>

                    <strong>
                        Nenhuma rota encontrada
                    </strong>

                    <span>
                        Nenhuma movimentação foi registrada até o momento.
                    </span>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection


@section('styles')

<style>

/* =========================================================
   PÁGINA
========================================================= */

.historico-rotas {
    width: 100%;

    padding: 22px 25px 60px;

    box-sizing: border-box;

    color: #e6eee9;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.historico-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    margin-bottom: 22px;
}


.historico-header-left {
    display: flex;

    align-items: center;

    gap: 15px;
}


.historico-icon {
    width: 48px;
    height: 48px;

    min-width: 48px;

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


.historico-header h2 {
    margin: 0;

    color: #eef5f1;

    font-size: 24px;
    font-weight: 600;
}


.historico-header p {
    margin: 5px 0 0;

    color: #8fa39a;

    font-size: 14px;
}


/* =========================================================
   RESUMO
========================================================= */

.historico-resumo {
    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 14px;

    margin-bottom: 20px;
}


.resumo-card {
    min-height: 94px;

    display: flex;

    align-items: center;

    gap: 13px;

    padding: 16px;

    background: linear-gradient(
        160deg,
        #1b2925,
        #15211d
    );

    border: 1px solid #315b46;

    border-radius: 11px;

    box-sizing: border-box;

    box-shadow:
        0 4px 15px rgba(0,0,0,.15),
        inset 0 0 0 1px rgba(105,193,116,.03);
}


.resumo-icon {
    width: 46px;
    height: 46px;

    min-width: 46px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 18px;
}


.resumo-icon.total {
    background: #193629;
    color: #7bdc86;
}


.resumo-icon.atividade {
    background: #182e38;
    color: #6ccbef;
}


.resumo-icon.finalizada {
    background: #2d2338;
    color: #c79cf2;
}


.resumo-info span {
    display: block;

    margin-bottom: 6px;

    color: #9db3aa;

    font-size: 12px;
    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .3px;
}


.resumo-info strong {
    color: #ffffff;

    font-size: 26px;
    font-weight: 800;
}


/* =========================================================
   PAINEL
========================================================= */

.historico-panel {
    width: 100%;

    background: #17221f;

    border: 1px solid #2c423a;

    border-radius: 11px;

    overflow: hidden;

    box-shadow: 0 5px 20px rgba(0,0,0,.15);
}


.historico-panel-header {
    min-height: 62px;

    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 12px 17px;

    background: #192622;

    border-bottom: 1px solid #293c35;
}


.historico-panel-header h3 {
    margin: 0;

    color: #e9f1ed;

    font-size: 16px;
    font-weight: 600;
}


.historico-panel-header h3 i {
    margin-right: 7px;

    color: #69c174;
}


.historico-panel-header span {
    display: block;

    margin-top: 5px;

    color: #7e9389;

    font-size: 11px;
}


/* =========================================================
   TABELA
========================================================= */

.historico-table-wrapper {
    width: 100%;

    overflow-x: auto;
}


.historico-table {
    width: 100%;

    border-collapse: collapse;

    table-layout: auto;
}


.historico-table th {
    padding: 13px 16px;

    text-align: left;

    background: #141f1b;

    border-bottom: 1px solid #293c35;

    color: #82978d;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .4px;

    white-space: nowrap;
}


.historico-table td {
    padding: 14px 16px;

    border-bottom: 1px solid #25352f;

    color: #d8e3dd;

    font-size: 13px;

    vertical-align: middle;
}


.historico-table tbody tr {
    transition: .2s;
}


.historico-table tbody tr:hover {
    background: rgba(105,193,116,.045);
}


.historico-table tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   ROTA
========================================================= */

.rota-nome {
    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 180px;
}


.rota-marker {
    width: 36px;
    height: 36px;

    min-width: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #193629;

    color: #69c174;

    border-radius: 8px;
}


.rota-nome strong {
    display: block;

    color: #eef5f0;

    font-size: 13px;
}


.rota-nome span {
    display: block;

    margin-top: 3px;

    color: #71847b;

    font-size: 10px;
}


/* =========================================================
   DATA
========================================================= */

.rota-data strong {
    display: block;

    color: #e3ece7;

    font-size: 13px;
}


.rota-data span {
    display: block;

    margin-top: 3px;

    color: #7f9389;

    font-size: 11px;
}


/* =========================================================
   VEÍCULO
========================================================= */

.rota-veiculo {
    display: flex;

    align-items: center;

    gap: 7px;

    white-space: nowrap;
}


.rota-veiculo i {
    color: #7bc988;
}


/* =========================================================
   DURAÇÃO
========================================================= */

.rota-duracao {
    display: flex;

    align-items: center;

    gap: 6px;

    color: #b9c8c0;

    white-space: nowrap;
}


/* =========================================================
   STATUS
========================================================= */

.rota-status {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 6px 10px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 700;

    white-space: nowrap;
}


.status-atividade {
    background: #17351f;

    border: 1px solid #2f6b3d;

    color: #6de07c;
}


.status-finalizada {
    background: #1c2e38;

    border: 1px solid #315467;

    color: #73c9ef;
}


.rota-status-dot {
    width: 6px;
    height: 6px;

    background: #55c965;

    border-radius: 50%;

    box-shadow: 0 0 7px rgba(85,201,101,.7);
}


/* =========================================================
   BOTÃO
========================================================= */

.btn-visualizar-rota {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 8px 12px;

    text-decoration: none;

    background: #1d392d;

    border: 1px solid #315b46;

    border-radius: 8px;

    color: #72cf7e;

    font-size: 11px;
    font-weight: 700;

    white-space: nowrap;

    transition: .2s;
}


.btn-visualizar-rota:hover {
    background: #254837;

    border-color: #69c174;

    color: #8bea96;

    transform: translateY(-1px);
}


/* =========================================================
   MOBILE CARDS
========================================================= */

.historico-mobile {
    display: none;
}


/* =========================================================
   VAZIO
========================================================= */

.historico-vazio {
    display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: center;

    min-height: 250px;

    padding: 20px;

    text-align: center;

    color: #71847b;
}


.historico-vazio-icon {
    width: 50px;
    height: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 12px;

    background: #193629;

    border-radius: 12px;

    color: #69c174;

    font-size: 20px;
}


.historico-vazio strong {
    margin-bottom: 5px;

    color: #b7c6be;

    font-size: 14px;
}


.historico-vazio span {
    font-size: 11px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 900px) {

    .historico-resumo {
        grid-template-columns: 1fr;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    .historico-rotas {
        padding: 16px 14px 40px;
    }


    /* CABEÇALHO */

    .historico-header {
        margin-bottom: 18px;
    }


    .historico-header-left {
        align-items: flex-start;

        gap: 12px;
    }


    .historico-icon {
        width: 44px;
        height: 44px;

        min-width: 44px;

        font-size: 18px;
    }


    .historico-header h2 {
        font-size: 21px;
    }


    .historico-header p {
        font-size: 12px;

        line-height: 1.5;
    }


    /* RESUMO */

    .historico-resumo {
        grid-template-columns: 1fr;

        gap: 10px;

        margin-bottom: 16px;
    }


    .resumo-card {
        min-height: 78px;

        padding: 13px 14px;
    }


    .resumo-icon {
        width: 42px;
        height: 42px;

        min-width: 42px;

        font-size: 16px;
    }


    .resumo-info span {
        font-size: 10px;
    }


    .resumo-info strong {
        font-size: 23px;
    }


    /* PAINEL */

    .historico-panel {
        background: transparent;

        border: none;

        box-shadow: none;

        overflow: visible;
    }


    .historico-panel-header {
        min-height: auto;

        padding: 14px;

        margin-bottom: 10px;

        background: #192622;

        border: 1px solid #293c35;

        border-radius: 10px;
    }


    .historico-panel-header h3 {
        font-size: 15px;
    }


    .historico-panel-header span {
        font-size: 10px;

        line-height: 1.4;
    }


    /* SOME A TABELA */

    .historico-table-wrapper {
        display: none;
    }


    /* MOSTRA CARDS */

    .historico-mobile {
        display: flex;

        flex-direction: column;

        gap: 12px;
    }


    .rota-mobile-card {
        width: 100%;

        padding: 15px;

        box-sizing: border-box;

        background: linear-gradient(
            160deg,
            #1b2925,
            #15211d
        );

        border: 1px solid #315b46;

        border-radius: 11px;

        box-shadow: 0 4px 14px rgba(0,0,0,.15);
    }


    /* TOPO */

    .rota-mobile-top {
        display: flex;

        align-items: flex-start;
        justify-content: space-between;

        gap: 10px;

        padding-bottom: 13px;

        margin-bottom: 13px;

        border-bottom: 1px solid #293c35;
    }


    .rota-mobile-nome {
        display: flex;

        align-items: center;

        gap: 10px;

        min-width: 0;
    }


    .rota-mobile-nome > div:last-child {
        min-width: 0;
    }


    .rota-mobile-nome strong {
        display: block;

        color: #eef5f0;

        font-size: 14px;

        line-height: 1.35;

        overflow-wrap: anywhere;
    }


    .rota-mobile-nome span {
        display: block;

        margin-top: 3px;

        color: #71847b;

        font-size: 10px;
    }


    .rota-mobile-top .rota-status {
        flex-shrink: 0;

        font-size: 9px;

        padding: 5px 8px;
    }


    /* DADOS */

    .rota-mobile-dados {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 10px;

        margin-bottom: 14px;
    }


    .rota-mobile-item {
        min-width: 0;

        padding: 10px;

        background: rgba(15, 26, 22, .65);

        border: 1px solid #293c35;

        border-radius: 8px;
    }


    .mobile-label {
        display: block;

        margin-bottom: 7px;

        color: #71847b;

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .4px;
    }


    .mobile-value {
        display: flex;

        align-items: center;

        gap: 7px;

        color: #dce8e2;

        min-width: 0;
    }


    .mobile-value i {
        color: #69c174;

        font-size: 12px;
    }


    .mobile-value strong {
        display: block;

        color: #e8f1ec;

        font-size: 12px;

        overflow-wrap: anywhere;
    }


    .mobile-value small {
        display: block;

        margin-top: 2px;

        color: #7e9389;

        font-size: 9px;
    }


    /* BOTÃO */

    .btn-mobile-rota {
        width: 100%;

        min-height: 42px;

        display: flex;

        align-items: center;
        justify-content: center;

        gap: 8px;

        box-sizing: border-box;

        text-decoration: none;

        background: #1d392d;

        border: 1px solid #315b46;

        border-radius: 8px;

        color: #72cf7e;

        font-size: 12px;
        font-weight: 700;
    }


    .btn-mobile-rota:active {
        background: #254837;
    }

}


/* =========================================================
   CELULAR PEQUENO
========================================================= */

@media (max-width: 420px) {

    .historico-rotas {
        padding: 14px 11px 35px;
    }


    .historico-header h2 {
        font-size: 19px;
    }


    .historico-header p {
        font-size: 11px;
    }


    .rota-mobile-card {
        padding: 13px;
    }


    .rota-mobile-dados {
        grid-template-columns: 1fr;
    }


    .rota-mobile-top {
        flex-direction: column;

        align-items: stretch;
    }


    .rota-mobile-top .rota-status {
        align-self: flex-start;
    }


    .btn-mobile-rota {
        min-height: 40px;

        font-size: 11px;
    }

}

</style>

@endsection