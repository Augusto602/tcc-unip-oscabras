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
         PAINEL DE ROTAS
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
             TABELA
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

                            {{-- NOME --}}

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


                            {{-- DATA --}}

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


                            {{-- VEÍCULO --}}

                            <td>

                                <div class="rota-veiculo">

                                    <i class="fa-solid fa-tractor"></i>

                                    <span>
                                        {{ $rota->veiculo }}
                                    </span>

                                </div>

                            </td>


                            {{-- DURAÇÃO --}}

                            <td>

                                <div class="rota-duracao">

                                    <i class="fa-regular fa-clock"></i>

                                    <span>
                                        {{ $rota->duracao }}
                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}

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


                            {{-- AÇÃO --}}

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

    font-size: 23px;
    font-weight: 600;
}


.historico-header p {
    margin: 5px 0 0;

    color: #8fa39a;

    font-size: 13px;
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
    min-height: 90px;

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
    width: 45px;
    height: 45px;

    min-width: 45px;

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

    font-size: 11px;
    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .3px;
}


.resumo-info strong {
    color: #ffffff;

    font-size: 25px;
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

    font-size: 15px;
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

    font-size: 10px;
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

    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .4px;

    white-space: nowrap;
}


.historico-table td {
    padding: 14px 16px;

    border-bottom: 1px solid #25352f;

    color: #d8e3dd;

    font-size: 12px;

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
    width: 35px;
    height: 35px;

    min-width: 35px;

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

    font-size: 12px;
}


.rota-nome span {
    display: block;

    margin-top: 3px;

    color: #71847b;

    font-size: 9px;
}


/* =========================================================
   DATA
========================================================= */

.rota-data strong {
    display: block;

    color: #e3ece7;

    font-size: 12px;
}


.rota-data span {
    display: block;

    margin-top: 3px;

    color: #7f9389;

    font-size: 10px;
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

    font-size: 9px;
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
   VISUALIZAR
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

    font-size: 10px;
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
   VAZIO
========================================================= */

.historico-vazio {
    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    min-height: 250px;

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
   RESPONSIVIDADE
========================================================= */

@media (max-width: 1000px) {

    .historico-resumo {
        grid-template-columns: repeat(3, 1fr);
    }

}


@media (max-width: 750px) {

    .historico-rotas {
        padding: 14px 14px 50px;
    }


    .historico-resumo {
        grid-template-columns: 1fr;
    }


    .historico-header h2 {
        font-size: 19px;
    }


    .historico-table {
        min-width: 800px;
    }

}


@media (max-width: 480px) {

    .historico-header-left {
        align-items: flex-start;
    }


    .historico-icon {
        width: 42px;
        height: 42px;

        min-width: 42px;
    }


    .historico-header p {
        font-size: 11px;
    }

}

</style>

@endsection