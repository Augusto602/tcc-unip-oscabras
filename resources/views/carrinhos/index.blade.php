@extends('layouts.app')

@section('title', 'Carrinhos')

@section('content')

<div class="carrinhos-page">

    {{-- =========================================================
         CABEÇALHO
    ========================================================== --}}

    <div class="carrinhos-header">

        <div class="carrinhos-header-left">

            <div class="carrinhos-header-icon">
                <i class="fa-solid fa-tractor"></i>
            </div>

            <div>

                <h2>Veículos</h2>

                <p>
                    Acompanhe os veículos cadastrados e o status das operações
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RESUMO
    ========================================================== --}}

    @php

        $emOperacao = $carrinhos->filter(function ($carrinho) {

            return $carrinho->execucoes
                ->where('status_execucao', 'em_andamento')
                ->isNotEmpty();

        })->count();

        $disponiveis = $carrinhos->count() - $emOperacao;

    @endphp


    <div class="carrinhos-resumo">

        {{-- TOTAL --}}

        <div class="carrinhos-resumo-card">

            <div class="resumo-icon total">
                <i class="fa-solid fa-tractor"></i>
            </div>

            <div class="resumo-info">

                <span>Total de veículos</span>

                <strong>
                    {{ $carrinhos->count() }}
                </strong>

            </div>

        </div>


        {{-- EM OPERAÇÃO --}}

        <div class="carrinhos-resumo-card">

            <div class="resumo-icon operacao">
                <i class="fa-solid fa-circle-play"></i>
            </div>

            <div class="resumo-info">

                <span>Em operação</span>

                <strong>
                    {{ $emOperacao }}
                </strong>

            </div>

        </div>


        {{-- DISPONÍVEIS --}}

        <div class="carrinhos-resumo-card">

            <div class="resumo-icon disponivel">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="resumo-info">

                <span>Disponíveis</span>

                <strong>
                    {{ $disponiveis }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
         VEÍCULOS
    ========================================================== --}}

    <div class="carrinhos-panel">

        <div class="carrinhos-panel-header">

            <div>

                <h3>
                    <i class="fa-solid fa-list"></i>
                    Veículos cadastrados
                </h3>

                <span>
                    Informações e situação atual dos veículos
                </span>

            </div>

        </div>


        <div class="carrinhos-grid">

            @forelse ($carrinhos as $carrinho)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | EXECUÇÃO ATUAL
                    |--------------------------------------------------------------------------
                    */

                    $execucaoAtual = $carrinho->execucoes
                        ->where('status_execucao', 'em_andamento')
                        ->sortByDesc('id_execucao')
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | ÚLTIMA EXECUÇÃO
                    |--------------------------------------------------------------------------
                    */

                    $ultimaExecucao = $carrinho->execucoes
                        ->sortByDesc('id_execucao')
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $estaEmOperacao = !empty($execucaoAtual);

                @endphp


                <div class="carrinho-card">

                    {{-- TOPO --}}

                    <div class="carrinho-card-top">

                        <div class="carrinho-identificacao">

                            <div class="carrinho-icon">
                                <i class="fa-solid fa-tractor"></i>
                            </div>

                            <div>

                                <h3>
                                    {{ $carrinho->nome_veiculo }}
                                </h3>

                                <span>
                                    {{ $carrinho->tipo_veiculo ?? 'Veículo agrícola' }}
                                </span>

                            </div>

                        </div>


                        @if ($estaEmOperacao)

                            <div class="carrinho-status status-operacao">

                                <span></span>

                                Em operação

                            </div>

                        @else

                            <div class="carrinho-status status-disponivel">

                                <i class="fa-solid fa-check"></i>

                                Disponível

                            </div>

                        @endif

                    </div>


                    {{-- DADOS --}}

                    <div class="carrinho-dados">

                        {{-- FABRICANTE --}}

                        <div class="carrinho-dado">

                            <span>Fabricante</span>

                            <strong>

                                <i class="fa-solid fa-industry"></i>

                                {{ $carrinho->fabricante ?? 'Não informado' }}

                            </strong>

                        </div>


                        {{-- IDENTIFICAÇÃO --}}

                        <div class="carrinho-dado">

                            <span>Identificação</span>

                            <strong>

                                <i class="fa-solid fa-hashtag"></i>

                                {{ $carrinho->id_veiculo }}

                            </strong>

                        </div>


                        {{-- COMPONENTES --}}

                        <div class="carrinho-dado carrinho-dado-full">

                            <span>Componentes</span>

                            <strong>

                                <i class="fa-solid fa-microchip"></i>

                                {{ $carrinho->componentes ?? 'Não informado' }}

                            </strong>

                        </div>


                        {{-- OPERAÇÃO --}}

                        <div class="carrinho-dado carrinho-dado-full">

                            <span>Operação atual</span>

                            <strong>

                                @if ($execucaoAtual)

                                    <i class="fa-solid fa-circle-play"></i>

                                    {{ $execucaoAtual->nome_execucao }}

                                @else

                                    <i class="fa-solid fa-circle-check"></i>

                                    Nenhuma operação ativa

                                @endif

                            </strong>

                        </div>


                        {{-- LOCAL --}}

                        @if ($execucaoAtual)

                            <div class="carrinho-dado carrinho-dado-full">

                                <span>Local da operação</span>

                                <strong>

                                    <i class="fa-solid fa-location-dot"></i>

                                    {{ $execucaoAtual->local_execucao ?? 'Não informado' }}

                                </strong>

                            </div>

                        @elseif ($ultimaExecucao)

                            <div class="carrinho-dado carrinho-dado-full">

                                <span>Última operação</span>

                                <strong>

                                    <i class="fa-solid fa-clock-rotate-left"></i>

                                    {{ $ultimaExecucao->nome_execucao }}

                                </strong>

                            </div>

                        @endif

                    </div>


                    {{-- AÇÕES --}}

                    <div class="carrinho-actions">

                        @if ($execucaoAtual)

                            <a
                                href="{{ route('monitoramento') }}?rota={{ $execucaoAtual->id_execucao }}"
                                class="carrinho-btn"
                            >

                                <i class="fa-solid fa-location-crosshairs"></i>

                                Monitorar operação

                            </a>

                        @else

                            <a
                                href="{{ route('monitoramento') }}"
                                class="carrinho-btn"
                            >

                                <i class="fa-solid fa-map-location-dot"></i>

                                Ver monitoramento

                            </a>

                        @endif

                    </div>

                </div>


            @empty

                <div class="carrinhos-vazio">

                    <div class="carrinhos-vazio-icon">
                        <i class="fa-solid fa-tractor"></i>
                    </div>

                    <strong>
                        Nenhum veículo cadastrado
                    </strong>

                    <span>
                        Cadastre um veículo para começar o monitoramento.
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

.carrinhos-page {
    width: 100%;
    padding: 22px 25px 60px;
    box-sizing: border-box;
    color: #e6eee9;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.carrinhos-header {
    display: flex;
    align-items: center;
    margin-bottom: 22px;
}

.carrinhos-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.carrinhos-header-icon {
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

.carrinhos-header h2 {
    margin: 0;

    color: #eef5f1;

    font-size: 23px;
    font-weight: 600;
}

.carrinhos-header p {
    margin: 5px 0 0;

    color: #8fa39a;

    font-size: 13px;
}


/* =========================================================
   RESUMO
========================================================= */

.carrinhos-resumo {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 14px;

    margin-bottom: 20px;
}

.carrinhos-resumo-card {
    min-height: 90px;

    display: flex;
    align-items: center;

    gap: 13px;

    padding: 15px;

    box-sizing: border-box;

    background: linear-gradient(
        160deg,
        #1b2925,
        #15211d
    );

    border: 1px solid #315b46;
    border-radius: 11px;

    box-shadow: 0 4px 15px rgba(0,0,0,.15);
}

.resumo-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 17px;
}

.resumo-icon.total {
    background: #193629;
    color: #7bdc86;
}

.resumo-icon.operacao {
    background: #182e38;
    color: #6ccbef;
}

.resumo-icon.disponivel {
    background: #272b1b;
    color: #d1d96f;
}

.resumo-info span {
    display: block;

    margin-bottom: 5px;

    color: #9db3aa;

    font-size: 10px;
    font-weight: 600;

    text-transform: uppercase;
    letter-spacing: .3px;
}

.resumo-info strong {
    color: #ffffff;

    font-size: 24px;
    font-weight: 800;
}


/* =========================================================
   PAINEL
========================================================= */

.carrinhos-panel {
    width: 100%;

    background: #17221f;

    border: 1px solid #2c423a;
    border-radius: 11px;

    overflow: hidden;

    box-shadow: 0 5px 20px rgba(0,0,0,.15);
}

.carrinhos-panel-header {
    min-height: 62px;

    display: flex;
    align-items: center;

    padding: 12px 17px;

    background: #192622;

    border-bottom: 1px solid #293c35;
}

.carrinhos-panel-header h3 {
    margin: 0;

    color: #e9f1ed;

    font-size: 15px;
    font-weight: 600;
}

.carrinhos-panel-header h3 i {
    margin-right: 7px;

    color: #69c174;
}

.carrinhos-panel-header span {
    display: block;

    margin-top: 5px;

    color: #7e9389;

    font-size: 10px;
}


/* =========================================================
   GRID
========================================================= */

.carrinhos-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(260px, 1fr));

    gap: 14px;

    padding: 16px;
}


/* =========================================================
   CARD
========================================================= */

.carrinho-card {
    min-width: 0;

    padding: 16px;

    box-sizing: border-box;

    background: linear-gradient(
        160deg,
        #1b2925,
        #14201c
    );

    border: 1px solid #315b46;
    border-radius: 11px;

    box-shadow: 0 4px 15px rgba(0,0,0,.15);

    transition: .2s;
}

.carrinho-card:hover {
    border-color: #477b60;

    transform: translateY(-2px);

    box-shadow: 0 7px 20px rgba(0,0,0,.20);
}


/* =========================================================
   TOPO
========================================================= */

.carrinho-card-top {
    display: flex;

    align-items: flex-start;
    justify-content: space-between;

    gap: 10px;

    padding-bottom: 14px;
    margin-bottom: 14px;

    border-bottom: 1px solid #293c35;
}

.carrinho-identificacao {
    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 0;
}

.carrinho-identificacao > div:last-child {
    min-width: 0;
}

.carrinho-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #193629;

    border-radius: 9px;

    color: #7bdc86;

    font-size: 18px;
}

.carrinho-identificacao h3 {
    margin: 0;

    color: #eef5f0;

    font-size: 14px;
    font-weight: 700;

    overflow-wrap: anywhere;
}

.carrinho-identificacao span {
    display: block;

    margin-top: 4px;

    color: #71847b;

    font-size: 9px;

    overflow-wrap: anywhere;
}


/* =========================================================
   STATUS
========================================================= */

.carrinho-status {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    flex-shrink: 0;

    border-radius: 15px;

    font-size: 8px;
    font-weight: 700;

    white-space: nowrap;
}

.status-operacao {
    background: #17351f;

    border: 1px solid #2f6b3d;

    color: #6de07c;
}

.status-operacao > span {
    width: 6px;
    height: 6px;

    background: #55c965;

    border-radius: 50%;

    box-shadow: 0 0 7px rgba(85,201,101,.7);
}

.status-disponivel {
    background: #1c2e38;

    border: 1px solid #315467;

    color: #73c9ef;
}


/* =========================================================
   DADOS
========================================================= */

.carrinho-dados {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 8px;

    margin-bottom: 14px;
}

.carrinho-dado {
    min-width: 0;

    padding: 10px;

    box-sizing: border-box;

    background: #111a17;

    border: 1px solid #283b34;

    border-radius: 8px;
}

.carrinho-dado-full {
    grid-column: 1 / -1;
}

.carrinho-dado > span {
    display: block;

    margin-bottom: 5px;

    color: #71847b;

    font-size: 8px;
    font-weight: 700;

    text-transform: uppercase;
}

.carrinho-dado strong {
    display: flex;

    align-items: flex-start;

    gap: 5px;

    color: #dce7e1;

    font-size: 10px;
    font-weight: 700;

    line-height: 1.45;

    overflow-wrap: anywhere;
}

.carrinho-dado strong i {
    margin-top: 2px;

    color: #69c174;

    font-size: 9px;
}


/* =========================================================
   BOTÃO
========================================================= */

.carrinho-actions {
    display: flex;
}

.carrinho-btn {
    width: 100%;

    min-height: 39px;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 8px 12px;

    box-sizing: border-box;

    background: #1d392d;

    border: 1px solid #315b46;
    border-radius: 8px;

    color: #72cf7e;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;

    transition: .2s;
}

.carrinho-btn:hover {
    background: #254837;

    border-color: #69c174;

    color: #8bea96;
}


/* =========================================================
   VAZIO
========================================================= */

.carrinhos-vazio {
    grid-column: 1 / -1;

    min-height: 220px;

    display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: center;

    padding: 20px;

    box-sizing: border-box;

    text-align: center;

    color: #71847b;
}

.carrinhos-vazio-icon {
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

.carrinhos-vazio strong {
    margin-bottom: 5px;

    color: #b7c6be;

    font-size: 14px;
}

.carrinhos-vazio span {
    font-size: 11px;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1000px) {

    .carrinhos-grid {
        grid-template-columns:
            repeat(2, minmax(250px, 1fr));
    }

}


/* =========================================================
   CELULAR
========================================================= */

@media (max-width: 700px) {

    .carrinhos-page {
        padding: 15px 10px 40px;
    }


    .carrinhos-header {
        margin-bottom: 17px;
    }


    .carrinhos-header-left {
        align-items: flex-start;

        gap: 11px;
    }


    .carrinhos-header-icon {
        width: 43px;
        height: 43px;

        min-width: 43px;

        font-size: 17px;
    }


    .carrinhos-header h2 {
        font-size: 19px;
    }


    .carrinhos-header p {
        font-size: 10px;

        line-height: 1.4;
    }


    /*
    |--------------------------------------------------------------------------
    | RESUMO
    |--------------------------------------------------------------------------
    */

    .carrinhos-resumo {
        grid-template-columns: 1fr;

        gap: 8px;

        margin-bottom: 15px;
    }


    .carrinhos-resumo-card {
        min-height: 72px;

        padding: 10px;
    }


    .resumo-icon {
        width: 37px;
        height: 37px;

        min-width: 37px;

        font-size: 13px;
    }


    .resumo-info span {
        font-size: 8px;
    }


    .resumo-info strong {
        font-size: 19px;
    }


    /*
    |--------------------------------------------------------------------------
    | PAINEL
    |--------------------------------------------------------------------------
    */

    .carrinhos-panel {
        border-radius: 9px;
    }


    .carrinhos-panel-header {
        min-height: auto;

        padding: 11px 12px;
    }


    .carrinhos-panel-header h3 {
        font-size: 13px;
    }


    .carrinhos-panel-header span {
        font-size: 9px;
    }


    /*
    |--------------------------------------------------------------------------
    | CARDS
    |--------------------------------------------------------------------------
    */

    .carrinhos-grid {
        grid-template-columns: 1fr;

        gap: 10px;

        padding: 11px;
    }


    .carrinho-card {
        padding: 13px;
    }


    .carrinho-dados {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }


    .carrinho-btn {
        min-height: 42px;

        font-size: 11px;
    }

}


/* =========================================================
   CELULAR PEQUENO
========================================================= */

@media (max-width: 420px) {

    .carrinhos-page {
        padding: 12px 8px 35px;
    }


    .carrinhos-header h2 {
        font-size: 18px;
    }


    .carrinhos-header p {
        font-size: 9px;
    }


    .carrinho-card-top {
        flex-direction: column;

        align-items: stretch;
    }


    .carrinho-status {
        align-self: flex-start;
    }


    .carrinho-dados {
        grid-template-columns: 1fr;
    }


    .carrinho-dado-full {
        grid-column: auto;
    }

}

</style>

@endsection