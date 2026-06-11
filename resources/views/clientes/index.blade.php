@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <h1>Cadastro de Usuários</h1>

    @if (session('sucesso'))
        <div class="alert alert-success">
            {{ session('sucesso') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf

        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="{{ old('nome') }}" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>

        <div>
            <label>Tipo de Login</label>
            <select name="tipo_login" required>
                <option value="">Selecione...</option>
                <option value="CEO">CEO</option>
                <option value="Cliente">Cliente</option>
                <option value="Gerente">Gerente</option>
            </select>
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <hr>

    <h2>Usuários cadastrados</h2>

    <table border="1" cellpadding="8">
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
                    <td>{{ $cliente->cad_usuario_id }}</td>
                    <td>{{ $cliente->cad_usuario_nome }}</td>
                    <td>{{ $cliente->cad_usuario_email }}</td>
                    <td>{{ $cliente->cad_usuario_tipo_login }}</td>
                    <td>{{ $cliente->cad_usuario_ativo_sn }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum usuário cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection