@extends('layouts.app')

@section('title', 'Informações')

@section('content')

<div class="informacoes-page">

    {{-- =========================================================
         CABEÇALHO
    ========================================================== --}}

    <div class="informacoes-header">

        <div class="informacoes-header-left">

            <div class="informacoes-header-icon">
                <i class="fa-solid fa-seedling"></i>
            </div>

            <div>
                <h2>Sistema Integrado de Monitoramento</h2>

                <p>
                    Gerencie e acompanhe as operações agrícolas em um único lugar
                </p>
            </div>

        </div>

    </div>


    {{-- =========================================================
         APRESENTAÇÃO
    ========================================================== --}}

    <div class="informacoes-panel">

        <div class="informacoes-panel-header">

            <div class="informacoes-panel-title">

                <div class="informacoes-panel-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div>
                    <h3>Bem-vindo ao sistema</h3>

                    <span>
                        Plataforma integrada para gerenciamento e monitoramento
                    </span>
                </div>

            </div>

        </div>


        <div class="informacoes-introducao">

            <p>
                Aqui você pode acompanhar e gerenciar os veículos autônomos
                utilizados no mapeamento das áreas agrícolas.
            </p>

            <p>
                O sistema permite
                <strong>visualizar rotas em andamento</strong>,
                <strong>consultar o histórico de percursos</strong>,
                <strong>acompanhar os veículos</strong> e
                <strong>monitorar os dados coletados durante as operações</strong>.
            </p>

            <p>
                Além disso, é possível realizar o
                <strong>cadastro e gerenciamento de usuários</strong>,
                garantindo o acesso às funcionalidades do sistema de acordo
                com as necessidades da operação.
            </p>

        </div>

    </div>


    {{-- =========================================================
         FUNCIONALIDADES
    ========================================================== --}}

    <div class="informacoes-section-title">

        <div>
            <h3>Funcionalidades disponíveis</h3>

            <span>
                Principais recursos disponíveis no sistema
            </span>
        </div>

    </div>


    <div class="informacoes-grid">

        {{-- ROTAS --}}

        <a
            href="{{ route('dashboard') }}"
            class="informacao-card informacao-card-link"
        >

            <div class="informacao-card-icon rotas">
                <i class="fa-solid fa-route"></i>
            </div>

            <div class="informacao-card-content">

                <h3>Resumo de Rotas</h3>

                <p>
                    Consulte as rotas realizadas, operações em andamento
                    e seus principais dados.
                </p>

            </div>

            <div class="informacao-card-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </a>


        {{-- VEÍCULOS --}}

        <a
            href="{{ route('carrinhos.index') }}"
            class="informacao-card informacao-card-link"
        >

            <div class="informacao-card-icon veiculos">
                <i class="fa-solid fa-tractor"></i>
            </div>

            <div class="informacao-card-content">

                <h3>Veículos</h3>

                <p>
                    Gerencie e acompanhe os veículos cadastrados
                    utilizados nas operações agrícolas.
                </p>

            </div>

            <div class="informacao-card-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </a>


        {{-- MONITORAMENTO --}}

        <a
            href="{{ route('monitoramento') }}"
            class="informacao-card informacao-card-link"
        >

            <div class="informacao-card-icon monitoramento">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div class="informacao-card-content">

                <h3>Monitoramento</h3>

                <p>
                    Acompanhe as operações e os dados coletados
                    durante as atividades em campo.
                </p>

            </div>

            <div class="informacao-card-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </a>


        {{-- USUÁRIOS --}}

        <div class="informacao-card">

            <div class="informacao-card-icon usuarios">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="informacao-card-content">

                <h3>Cadastro de Usuários</h3>

                <p>
                    Gerencie os usuários que possuem acesso
                    às funcionalidades do sistema.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection


@section('styles')

<style>

/* =========================================================
   PÁGINA
========================================================= */

.informacoes-page {
    width: 100%;
    padding: 24px 26px 60px;

    box-sizing: border-box;

    color: #e6eee9;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.informacoes-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    margin-bottom: 24px;
}


.informacoes-header-left {
    display: flex;

    align-items: center;

    gap: 16px;
}


.informacoes-header-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #1d392d;

    border: 1px solid #315b46;
    border-radius: 11px;

    color: #69c174;

    font-size: 22px;

    box-shadow: 0 4px 15px rgba(0, 0, 0, .18);
}


.informacoes-header h2 {
    margin: 0;

    color: #eef5f1;

    font-size: 26px;
    font-weight: 600;

    line-height: 1.25;
}


.informacoes-header p {
    margin: 6px 0 0;

    color: #8fa39a;

    font-size: 15px;

    line-height: 1.5;
}


/* =========================================================
   PAINEL
========================================================= */

.informacoes-panel {
    width: 100%;

    margin-bottom: 28px;

    background: #17221f;

    border: 1px solid #2c423a;
    border-radius: 11px;

    overflow: hidden;

    box-shadow: 0 5px 20px rgba(0, 0, 0, .15);
}


.informacoes-panel-header {
    min-height: 65px;

    display: flex;

    align-items: center;

    padding: 13px 18px;

    background: #192622;

    border-bottom: 1px solid #293c35;
}


.informacoes-panel-title {
    display: flex;

    align-items: center;

    gap: 12px;
}


.informacoes-panel-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #193629;

    border-radius: 8px;

    color: #69c174;

    font-size: 17px;
}


.informacoes-panel-header h3 {
    margin: 0;

    color: #e9f1ed;

    font-size: 17px;
    font-weight: 600;

    line-height: 1.35;
}


.informacoes-panel-header span {
    display: block;

    margin-top: 5px;

    color: #7e9389;

    font-size: 12px;

    line-height: 1.5;
}


/* =========================================================
   INTRODUÇÃO
========================================================= */

.informacoes-introducao {
    padding: 24px 26px;
}


.informacoes-introducao p {
    margin: 0 0 15px;

    color: #b8c8c0;

    font-size: 15px;

    line-height: 1.8;
}


.informacoes-introducao p:last-child {
    margin-bottom: 0;
}


.informacoes-introducao strong {
    color: #72cf7e;

    font-weight: 600;
}


/* =========================================================
   TÍTULO DAS FUNCIONALIDADES
========================================================= */

.informacoes-section-title {
    margin-bottom: 16px;
}


.informacoes-section-title h3 {
    margin: 0;

    color: #e9f1ed;

    font-size: 19px;
    font-weight: 600;

    line-height: 1.4;
}


.informacoes-section-title span {
    display: block;

    margin-top: 4px;

    color: #7e9389;

    font-size: 12px;

    line-height: 1.5;
}


/* =========================================================
   GRID
========================================================= */

.informacoes-grid {
    display: grid;

    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 15px;
}


/* =========================================================
   CARDS
========================================================= */

.informacao-card {
    min-height: 135px;

    display: flex;

    align-items: flex-start;

    gap: 16px;

    padding: 20px;

    background: linear-gradient(
        160deg,
        #1b2925,
        #15211d
    );

    border: 1px solid #315b46;

    border-radius: 11px;

    box-sizing: border-box;

    box-shadow:
        0 4px 15px rgba(0, 0, 0, .15),
        inset 0 0 0 1px rgba(105, 193, 116, .03);

    transition:
        transform .2s ease,
        border-color .2s ease,
        background .2s ease;
}


.informacao-card:hover {
    transform: translateY(-2px);

    border-color: #477b60;

    background: linear-gradient(
        160deg,
        #1e302a,
        #17241f
    );
}


/* =========================================================
   CARDS CLICÁVEIS
========================================================= */

.informacao-card-link {
    position: relative;

    color: inherit;
    text-decoration: none;

    cursor: pointer;
}


.informacao-card-link:visited,
.informacao-card-link:active,
.informacao-card-link:focus {
    color: inherit;
    text-decoration: none;
}


.informacao-card-link:hover {
    text-decoration: none;
}


.informacao-card-arrow {
    align-self: center;

    margin-left: auto;
    padding-left: 12px;

    color: #587067;

    font-size: 13px;

    transition:
        color .2s ease,
        transform .2s ease;
}


.informacao-card-link:hover .informacao-card-arrow {
    color: #72cf7e;

    transform: translateX(4px);
}


.informacao-card-link:hover .informacao-card-content h3 {
    color: #7bdc86;
}


/* =========================================================
   ÍCONES
========================================================= */

.informacao-card-icon {
    width: 50px;
    height: 50px;

    min-width: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 20px;
}


.informacao-card-icon.rotas {
    background: #193629;

    color: #7bdc86;
}


.informacao-card-icon.veiculos {
    background: #182e38;

    color: #6ccbef;
}


.informacao-card-icon.monitoramento {
    background: #2d2338;

    color: #c79cf2;
}


.informacao-card-icon.usuarios {
    background: #382c1c;

    color: #e8b968;
}


/* =========================================================
   CONTEÚDO DOS CARDS
========================================================= */

.informacao-card-content {
    flex: 1;

    min-width: 0;
}


.informacao-card-content h3 {
    margin: 2px 0 8px;

    color: #eef5f0;

    font-size: 16px;
    font-weight: 600;

    line-height: 1.4;
}


.informacao-card-content p {
    margin: 0;

    color: #8fa39a;

    font-size: 13px;

    line-height: 1.7;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 900px) {

    .informacoes-page {
        padding: 20px 20px 50px;
    }


    .informacoes-grid {
        grid-template-columns: 1fr;
    }


    .informacao-card {
        min-height: auto;
    }

}


/* =========================================================
   CELULAR
========================================================= */

@media (max-width: 600px) {

    .informacoes-page {
        padding: 16px 14px 38px;
    }


    /* CABEÇALHO */

    .informacoes-header {
        margin-bottom: 18px;
    }


    .informacoes-header-left {
        align-items: flex-start;

        gap: 12px;
    }


    .informacoes-header-icon {
        width: 44px;
        height: 44px;

        min-width: 44px;

        font-size: 18px;

        border-radius: 9px;
    }


    .informacoes-header h2 {
        font-size: 21px;

        line-height: 1.3;
    }


    .informacoes-header p {
        margin-top: 4px;

        font-size: 13px;

        line-height: 1.5;
    }


    /* PAINEL */

    .informacoes-panel {
        margin-bottom: 22px;

        border-radius: 9px;
    }


    .informacoes-panel-header {
        min-height: auto;

        padding: 14px;
    }


    .informacoes-panel-title {
        align-items: flex-start;

        gap: 10px;
    }


    .informacoes-panel-icon {
        width: 38px;
        height: 38px;

        min-width: 38px;

        font-size: 15px;
    }


    .informacoes-panel-header h3 {
        font-size: 16px;
    }


    .informacoes-panel-header span {
        font-size: 11px;

        line-height: 1.45;
    }


    /* INTRODUÇÃO */

    .informacoes-introducao {
        padding: 17px;
    }


    .informacoes-introducao p {
        margin-bottom: 13px;

        font-size: 14px;

        line-height: 1.7;
    }


    /* TÍTULO */

    .informacoes-section-title {
        margin-bottom: 13px;
    }


    .informacoes-section-title h3 {
        font-size: 17px;
    }


    .informacoes-section-title span {
        font-size: 11px;
    }


    /* CARDS */

    .informacoes-grid {
        gap: 12px;
    }


    .informacao-card {
        min-height: auto;

        padding: 16px;

        gap: 13px;
    }


    .informacao-card-icon {
        width: 45px;
        height: 45px;

        min-width: 45px;

        font-size: 18px;
    }


    .informacao-card-content h3 {
        margin-top: 0;

        margin-bottom: 6px;

        font-size: 15px;
    }


    .informacao-card-content p {
        font-size: 13px;

        line-height: 1.6;
    }

}


/* =========================================================
   CELULARES PEQUENOS
========================================================= */

@media (max-width: 380px) {

    .informacoes-page {
        padding: 14px 11px 35px;
    }


    .informacoes-header-left {
        gap: 10px;
    }


    .informacoes-header-icon {
        width: 41px;
        height: 41px;

        min-width: 41px;
    }


    .informacoes-header h2 {
        font-size: 18px;
    }


    .informacoes-header p {
        font-size: 11px;
    }


    .informacoes-panel-header {
        padding: 12px;
    }


    .informacoes-panel-header h3 {
        font-size: 15px;
    }


    .informacoes-panel-header span {
        font-size: 10px;
    }


    .informacoes-introducao {
        padding: 15px;
    }


    .informacoes-introducao p {
        font-size: 13px;
    }


    .informacao-card {
        padding: 15px;

        gap: 11px;
    }


    .informacao-card-icon {
        width: 42px;
        height: 42px;

        min-width: 42px;

        font-size: 17px;
    }


    .informacao-card-content h3 {
        font-size: 15px;
    }


    .informacao-card-content p {
        font-size: 12px;
    }

}


/* =========================================================
   CELULARES MUITO PEQUENOS
========================================================= */

@media (max-width: 330px) {

    .informacao-card {
        flex-direction: column;
    }


    .informacao-card-icon {
        margin-bottom: 2px;
    }

}

</style>

@endsection