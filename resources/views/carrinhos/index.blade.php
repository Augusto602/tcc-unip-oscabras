@extends('layouts.app')

@section('title', 'Carrinhos')

@section('content')

<div class="page-panel">
    <h1>Carrinhos</h1>

    <p>
        Essa tela ainda está apenas visual. Depois você pode listar os carrinhos,
        status, bateria e última localização enviada.
    </p>

    <div class="fake-carts-grid">
        <div class="fake-cart-card">
            <span>🚜</span>
            <strong>Carrinho 01</strong>
            <p>Online · Bateria 82%</p>
        </div>

        <div class="fake-cart-card">
            <span>🚜</span>
            <strong>Carrinho 02</strong>
            <p>Offline · Bateria 41%</p>
        </div>

        <div class="fake-cart-card">
            <span>🚜</span>
            <strong>Carrinho 03</strong>
            <p>Online · Bateria 69%</p>
        </div>
    </div>
</div>

@endsection