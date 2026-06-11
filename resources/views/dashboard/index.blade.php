@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <p>Bem-vindo, {{ session('usuario_nome') }}.</p>

    <div class="panel">
        <h2>Sistema Agrícola</h2>
        <p>Área principal do sistema.</p>
    </div>
@endsection