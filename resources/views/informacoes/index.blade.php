@extends('layouts.app')

@section('title', 'Informações')

@section('content')

<div class="page-panel">


<h1>Seja bem-vindo ao Sistema Integrado de Monitoramento</h1>

<div class="info-introduction">

    <p>
        Aqui você pode acompanhar e gerenciar os veículos autônomos
        utilizados no mapeamento das áreas agrícolas. O sistema permite
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

<div class="page-panel">


<h2>Funcionalidades disponíveis:</h2>

<div class="info-feature">

    <div class="info-feature-icon">
        🛠️
    </div>

    <div>
        <h3>Resumo de Rotas</h3>

        <p>
            Consulta das rotas realizadas e seus principais dados.
        </p>
    </div>

</div>


<div class="info-feature">

    <div class="info-feature-icon">
        🚜
    </div>

    <div>
        <h3>Veículos</h3>

        <p>
            Gerenciamento e acompanhamento dos veículos cadastrados.
        </p>
    </div>

</div>


<div class="info-feature">

    <div class="info-feature-icon">
        🤖
    </div>

    <div>
        <h3>Monitoramento</h3>

        <p>
            Acompanhamento das operações e dados coletados em tempo real.
        </p>
    </div>

</div>


<div class="info-feature">

    <div class="info-feature-icon">
        👤
    </div>

    <div>
        <h3>Cadastro de Usuários</h3>

        <p>
            Gerenciamento dos usuários que possuem acesso ao sistema.
        </p>
    </div>

</div>


</div>

@endsection
