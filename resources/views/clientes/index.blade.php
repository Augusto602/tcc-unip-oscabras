@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

    <div class="clientes-page">

        <h1 class="clientes-titulo">Cadastro de Usuários</h1>

        @if (session('sucesso'))
            <div class="alert alert-success clientes-alert">
                {{ session('sucesso') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger clientes-alert">
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="clientes-form-card">

            <form method="POST" action="{{ route('clientes.store') }}">
                @csrf

                <div class="clientes-form-group">
                    <label>Nome</label>

                    <input
                        type="text"
                        name="nome"
                        value="{{ old('nome') }}"
                        required
                    >
                </div>


                <div class="clientes-form-group">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>


                <div class="clientes-form-group">
                    <label>Senha</label>

                    <input
                        type="password"
                        name="senha"
                        required
                    >
                </div>


                <div class="clientes-form-group">
                    <label>Tipo de Login</label>

                    <select name="tipo_login" required>

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


                <button type="submit" class="clientes-btn-cadastrar">
                    Cadastrar
                </button>

            </form>

        </div>


        <div class="clientes-listagem">

            <h2>Usuários cadastrados</h2>

            <div class="clientes-table-wrapper">

                <table class="clientes-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo Login</th>
                            <th>Ativo</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse ($clientes as $cliente)

                            <tr>

                                <td>
                                    {{ $cliente->cad_usuario_id }}
                                </td>

                                <td>
                                    {{ $cliente->cad_usuario_nome }}
                                </td>

                                <td>
                                    {{ $cliente->cad_usuario_email }}
                                </td>

                                <td>
                                    {{ $cliente->cad_usuario_tipo_login }}
                                </td>

                                <td>
                                    {{ $cliente->cad_usuario_ativo_sn }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="clientes-sem-registro">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection