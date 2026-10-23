@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="clientes-page">


    {{-- =========================================================
         CABEÇALHO
    ========================================================== --}}

    <div class="clientes-header">

        <div class="clientes-header-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <h1>Gerenciamento de Usuários</h1>

            <p>
                Cadastre e acompanhe os usuários com acesso ao sistema
            </p>
        </div>

    </div>


    {{-- =========================================================
         ALERTAS
    ========================================================== --}}

    @if (session('sucesso'))

        <div class="clientes-alert sucesso">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('sucesso') }}
            </span>

        </div>

    @endif


    @if ($errors->any())

        <div class="clientes-alert erro">

            <i class="fa-solid fa-circle-exclamation"></i>

            <div>

                <strong>
                    Não foi possível concluir o cadastro.
                </strong>

                <ul>

                    @foreach ($errors->all() as $erro)

                        <li>
                            {{ $erro }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         CONTEÚDO
    ========================================================== --}}

    <div class="clientes-layout">


        {{-- =====================================================
             FORMULÁRIO
        ====================================================== --}}

        <section class="clientes-card clientes-cadastro">

            <div class="clientes-card-header">

                <div>

                    <h2>
                        Novo usuário
                    </h2>

                    <p>
                        Informe os dados para liberar um novo acesso.
                    </p>

                </div>

                <div class="clientes-card-header-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('clientes.store') }}"
                class="clientes-form"
            >

                @csrf


                {{-- NOME --}}

                <div class="clientes-form-group">

                    <label for="nome">
                        Nome completo
                    </label>

                    <div class="clientes-input">

                        <i class="fa-regular fa-user"></i>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            value="{{ old('nome') }}"
                            placeholder="Digite o nome"
                            autocomplete="name"
                            required
                        >

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="clientes-form-group">

                    <label for="email">
                        Email
                    </label>

                    <div class="clientes-input">

                        <i class="fa-regular fa-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="usuario@email.com"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                {{-- SENHA --}}

                <div class="clientes-form-group">

                    <label for="senha">
                        Senha
                    </label>

                    <div class="clientes-input">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite uma senha"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                {{-- TIPO LOGIN --}}

                <div class="clientes-form-group">

                    <label for="tipo_login">
                        Tipo de login
                    </label>

                    <div class="clientes-input clientes-select">

                        <i class="fa-solid fa-user-shield"></i>

                        <select
                            id="tipo_login"
                            name="tipo_login"
                            required
                        >

                            <option value="">
                                Selecione...
                            </option>

                            <option
                                value="CEO"
                                {{ old('tipo_login') == 'CEO' ? 'selected' : '' }}
                            >
                                CEO
                            </option>

                            <option
                                value="Cliente"
                                {{ old('tipo_login') == 'Cliente' ? 'selected' : '' }}
                            >
                                Cliente
                            </option>

                            <option
                                value="Gerente"
                                {{ old('tipo_login') == 'Gerente' ? 'selected' : '' }}
                            >
                                Gerente
                            </option>

                        </select>

                    </div>

                </div>


                <button
                    type="submit"
                    class="clientes-btn-cadastrar"
                >

                    <i class="fa-solid fa-plus"></i>

                    Cadastrar usuário

                </button>

            </form>

        </section>


        {{-- =====================================================
             LISTAGEM
        ====================================================== --}}

        <section class="clientes-card clientes-usuarios">

            <div class="clientes-card-header">

                <div>

                    <h2>
                        Usuários cadastrados
                    </h2>

                    <p>
                        Consulte os usuários disponíveis no sistema.
                    </p>

                </div>

                <div class="clientes-quantidade">
                    {{ $clientes->count() }}
                </div>

            </div>


            {{-- =================================================
                 DESKTOP
            ================================================== --}}

            <div class="clientes-table-wrapper">

                <table class="clientes-table">

                    <thead>

                        <tr>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($clientes as $cliente)

                            <tr>

                                <td>

                                    <div class="clientes-user">

                                        <div class="clientes-avatar">

                                            {{ strtoupper(substr($cliente->nome_usuario, 0, 1)) }}

                                        </div>

                                        <div>

                                            <strong>
                                                {{ $cliente->nome_usuario }}
                                            </strong>

                                            <span>
                                                ID #{{ $cliente->id_usuario }}
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    <span class="clientes-email">
                                        {{ $cliente->email }}
                                    </span>

                                </td>


                                <td>

                                    <span class="clientes-perfil">

                                        {{ $cliente->Cliente }}

                                    </span>

                                </td>


                                <td>

                                    <span
                                        class="clientes-status
                                        {{ strtolower(trim((string) $cliente->Ativo)) == 'sim' ||
                                           strtolower(trim((string) $cliente->Ativo)) == 's' ||
                                           strtolower(trim((string) $cliente->Ativo)) == 'ativo' ||
                                           $cliente->Ativo == 1
                                           ? 'ativo'
                                           : 'inativo' }}"
                                    >

                                        <span class="clientes-status-dot"></span>

                                        {{ $cliente->Ativo }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="clientes-vazio">

                                        <i class="fa-regular fa-user"></i>

                                        <strong>
                                            Nenhum usuário cadastrado
                                        </strong>

                                        <span>
                                            Utilize o formulário ao lado para realizar o primeiro cadastro.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 MOBILE
            ================================================== --}}

            <div class="clientes-mobile">

                @forelse ($clientes as $cliente)

                    <div class="cliente-mobile-card">

                        <div class="cliente-mobile-top">

                            <div class="clientes-user">

                                <div class="clientes-avatar">

                                    {{ strtoupper(substr($cliente->nome_usuario, 0, 1)) }}

                                </div>

                                <div>

                                    <strong>
                                        {{ $cliente->nome_usuario }}
                                    </strong>

                                    <span>
                                        ID #{{ $cliente->id_usuario }}
                                    </span>

                                </div>

                            </div>


                            <span
                                class="clientes-status
                                {{ strtolower(trim((string) $cliente->Ativo)) == 'sim' ||
                                   strtolower(trim((string) $cliente->Ativo)) == 's' ||
                                   strtolower(trim((string) $cliente->Ativo)) == 'ativo' ||
                                   $cliente->Ativo == 1
                                   ? 'ativo'
                                   : 'inativo' }}"
                            >

                                <span class="clientes-status-dot"></span>

                                {{ $cliente->Ativo }}

                            </span>

                        </div>


                        <div class="cliente-mobile-row">

                            <span>Email</span>

                            <strong>
                                {{ $cliente->email }}
                            </strong>

                        </div>


                        <div class="cliente-mobile-row">

                            <span>Perfil</span>

                            <strong>
                                {{ $cliente->Cliente }}
                            </strong>

                        </div>

                    </div>


                @empty

                    <div class="clientes-vazio">

                        <i class="fa-regular fa-user"></i>

                        <strong>
                            Nenhum usuário cadastrado
                        </strong>

                        <span>
                            Realize o primeiro cadastro acima.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

</div>

@endsection


@section('styles')

<style>

/* =========================================================
   PÁGINA
========================================================= */

.clientes-page {
    width: 100%;

    padding: 22px 25px 60px;

    box-sizing: border-box;

    color: #e6eee9;
}


/* =========================================================
   CABEÇALHO
========================================================= */

.clientes-header {
    display: flex;

    align-items: center;

    gap: 14px;

    margin-bottom: 22px;
}


.clientes-header-icon {
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

    font-size: 19px;

    box-shadow: 0 4px 15px rgba(0,0,0,.18);
}


.clientes-header h1 {
    margin: 0;

    color: #eef5f1;

    font-size: 23px;

    font-weight: 600;

    line-height: 1.2;
}


.clientes-header p {
    margin: 5px 0 0;

    color: #83988e;

    font-size: 12px;
}


/* =========================================================
   ALERTAS
========================================================= */

.clientes-alert {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 18px;

    padding: 12px 14px;

    border-radius: 9px;

    font-size: 11px;

    line-height: 1.6;
}


.clientes-alert.sucesso {
    background: #17351f;

    border: 1px solid #2f6b3d;

    color: #7cdd88;
}


.clientes-alert.erro {
    background: #321b1b;

    border: 1px solid #603232;

    color: #eb9696;
}


.clientes-alert strong {
    display: block;

    margin-bottom: 3px;
}


.clientes-alert ul {
    margin: 4px 0 0;

    padding-left: 17px;
}


/* =========================================================
   LAYOUT
========================================================= */

.clientes-layout {
    display: grid;

    grid-template-columns:
        minmax(300px, .75fr)
        minmax(520px, 1.65fr);

    align-items: start;

    gap: 18px;
}


/* =========================================================
   CARDS
========================================================= */

.clientes-card {
    background: #17221f;

    border: 1px solid #2c423a;

    border-radius: 11px;

    overflow: hidden;

    box-shadow: 0 5px 20px rgba(0,0,0,.15);
}


.clientes-card-header {
    min-height: 68px;

    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 13px 16px;

    box-sizing: border-box;

    background: #192622;

    border-bottom: 1px solid #293c35;
}


.clientes-card-header h2 {
    margin: 0;

    color: #e9f1ed;

    font-size: 15px;

    font-weight: 600;
}


.clientes-card-header p {
    margin: 5px 0 0;

    color: #7e9389;

    font-size: 10px;

    line-height: 1.4;
}


.clientes-card-header-icon {
    width: 36px;
    height: 36px;

    min-width: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #193629;

    border-radius: 8px;

    color: #69c174;

    font-size: 14px;
}


/* =========================================================
   CONTADOR
========================================================= */

.clientes-quantidade {
    width: 35px;
    height: 35px;

    min-width: 35px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #193629;

    border: 1px solid #315b46;

    border-radius: 50%;

    color: #72cf7e;

    font-size: 11px;

    font-weight: 800;
}


/* =========================================================
   FORM
========================================================= */

.clientes-form {
    padding: 18px;
}


.clientes-form-group {
    margin-bottom: 14px;
}


.clientes-form-group label {
    display: block;

    margin-bottom: 6px;

    color: #a5b6ae;

    font-size: 10px;

    font-weight: 600;
}


.clientes-input {
    height: 43px;

    display: flex;

    align-items: center;

    gap: 9px;

    padding: 0 11px;

    box-sizing: border-box;

    background: #111a17;

    border: 1px solid #2b4038;

    border-radius: 8px;

    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background .2s ease;
}


.clientes-input:focus-within {
    background: #13201c;

    border-color: #4c805f;

    box-shadow: 0 0 0 3px rgba(105,193,116,.07);
}


.clientes-input > i {
    width: 15px;

    color: #608875;

    font-size: 11px;

    text-align: center;

    flex-shrink: 0;
}


.clientes-input input,
.clientes-input select {
    width: 100%;
    height: 100%;

    min-width: 0;

    padding: 0;

    background: transparent;

    border: none;

    outline: none;

    color: #e4ede8;

    font-size: 11px;
}


.clientes-input input::placeholder {
    color: #5f7068;
}


.clientes-input select {
    cursor: pointer;
}


.clientes-input select option {
    background: #17221f;

    color: #e4ede8;
}


/* =========================================================
   BOTÃO
========================================================= */

.clientes-btn-cadastrar {
    width: 100%;

    min-height: 43px;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    margin-top: 18px;

    padding: 10px 14px;

    background: #1f4932;

    border: 1px solid #3e7655;

    border-radius: 8px;

    color: #8be295;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;

    transition: .2s;
}


.clientes-btn-cadastrar:hover {
    background: #28593d;

    border-color: #69c174;

    color: #a0eca8;

    transform: translateY(-1px);
}


/* =========================================================
   TABELA
========================================================= */

.clientes-table-wrapper {
    width: 100%;

    overflow-x: auto;
}


.clientes-table {
    width: 100%;

    border-collapse: collapse;
}


.clientes-table th {
    padding: 12px 14px;

    text-align: left;

    background: #141f1b;

    border-bottom: 1px solid #293c35;

    color: #798e84;

    font-size: 9px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .4px;
}


.clientes-table td {
    padding: 13px 14px;

    border-bottom: 1px solid #25352f;

    color: #d8e3dd;

    font-size: 11px;

    vertical-align: middle;
}


.clientes-table tbody tr {
    transition: background .15s ease;
}


.clientes-table tbody tr:hover {
    background: rgba(105,193,116,.035);
}


.clientes-table tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   USUÁRIO
========================================================= */

.clientes-user {
    display: flex;

    align-items: center;

    gap: 9px;

    min-width: 0;
}


.clientes-avatar {
    width: 34px;
    height: 34px;

    min-width: 34px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #193629;

    border: 1px solid #315b46;

    border-radius: 8px;

    color: #7bdc86;

    font-size: 12px;

    font-weight: 800;
}


.clientes-user > div:last-child {
    min-width: 0;
}


.clientes-user strong {
    display: block;

    color: #e9f1ed;

    font-size: 11px;

    line-height: 1.3;
}


.clientes-user span {
    display: block;

    margin-top: 3px;

    color: #6f8379;

    font-size: 8px;
}


/* =========================================================
   EMAIL
========================================================= */

.clientes-email {
    color: #a9bbb2;

    font-size: 10px;
}


/* =========================================================
   PERFIL
========================================================= */

.clientes-perfil {
    display: inline-flex;

    align-items: center;

    padding: 5px 8px;

    background: #182e38;

    border: 1px solid #294a58;

    border-radius: 13px;

    color: #6ccbef;

    font-size: 8px;

    font-weight: 700;
}


/* =========================================================
   STATUS
========================================================= */

.clientes-status {
    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    border-radius: 13px;

    font-size: 8px;

    font-weight: 700;

    white-space: nowrap;
}


.clientes-status-dot {
    width: 5px;
    height: 5px;

    border-radius: 50%;
}


.clientes-status.ativo {
    background: #17351f;

    border: 1px solid #2f6b3d;

    color: #6de07c;
}


.clientes-status.ativo .clientes-status-dot {
    background: #55c965;

    box-shadow: 0 0 6px rgba(85,201,101,.7);
}


.clientes-status.inativo {
    background: #351f1f;

    border: 1px solid #633636;

    color: #df7d7d;
}


.clientes-status.inativo .clientes-status-dot {
    background: #d95b5b;
}


/* =========================================================
   MOBILE LIST
========================================================= */

.clientes-mobile {
    display: none;
}


/* =========================================================
   VAZIO
========================================================= */

.clientes-vazio {
    min-height: 180px;

    display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: center;

    padding: 20px;

    text-align: center;
}


.clientes-vazio i {
    margin-bottom: 10px;

    color: #547864;

    font-size: 22px;
}


.clientes-vazio strong {
    color: #b8c7c0;

    font-size: 12px;
}


.clientes-vazio span {
    margin-top: 5px;

    color: #71847b;

    font-size: 9px;

    line-height: 1.5;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1050px) {

    .clientes-layout {
        grid-template-columns:
            minmax(280px, .9fr)
            minmax(420px, 1.4fr);
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 800px) {

    .clientes-page {
        padding: 15px 11px 40px;
    }


    .clientes-layout {
        grid-template-columns: 1fr;

        gap: 14px;
    }


    .clientes-header {
        margin-bottom: 17px;
    }


    .clientes-header-icon {
        width: 43px;
        height: 43px;

        min-width: 43px;

        font-size: 17px;
    }


    .clientes-header h1 {
        font-size: 19px;
    }


    .clientes-header p {
        font-size: 10px;
    }


    /* FORM */

    .clientes-form {
        padding: 14px;
    }


    .clientes-card-header {
        min-height: auto;

        padding: 12px 13px;
    }


    .clientes-card-header h2 {
        font-size: 14px;
    }


    .clientes-card-header p {
        font-size: 9px;
    }


    .clientes-input {
        height: 44px;
    }


    .clientes-btn-cadastrar {
        min-height: 44px;
    }


    /* TABELA SOME */

    .clientes-table-wrapper {
        display: none;
    }


    /* MOBILE APARECE */

    .clientes-mobile {
        display: flex;

        flex-direction: column;

        gap: 9px;

        padding: 11px;
    }


    .cliente-mobile-card {
        padding: 12px;

        background: #131e1a;

        border: 1px solid #2b4038;

        border-radius: 9px;
    }


    .cliente-mobile-top {
        display: flex;

        align-items: flex-start;
        justify-content: space-between;

        gap: 10px;

        padding-bottom: 11px;

        margin-bottom: 10px;

        border-bottom: 1px solid #25352f;
    }


    .cliente-mobile-row {
        display: flex;

        align-items: flex-start;
        justify-content: space-between;

        gap: 12px;

        padding: 6px 0;
    }


    .cliente-mobile-row > span {
        color: #71847b;

        font-size: 8px;

        font-weight: 700;

        text-transform: uppercase;
    }


    .cliente-mobile-row > strong {
        max-width: 70%;

        color: #d8e3dd;

        font-size: 10px;

        font-weight: 600;

        text-align: right;

        overflow-wrap: anywhere;
    }

}


/* =========================================================
   CELULAR PEQUENO
========================================================= */

@media (max-width: 420px) {

    .clientes-page {
        padding: 12px 8px 35px;
    }


    .clientes-header {
        align-items: flex-start;

        gap: 10px;
    }


    .clientes-header h1 {
        font-size: 18px;
    }


    .clientes-header p {
        font-size: 9px;
    }


    .clientes-card-header {
        padding: 10px 11px;
    }


    .clientes-form {
        padding: 12px;
    }


    .cliente-mobile-top {
        flex-direction: column;
    }


    .cliente-mobile-top .clientes-status {
        align-self: flex-start;
    }

}

</style>

@endsection