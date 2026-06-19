@extends('layouts.app')

@section('title', 'Dashboard Miniclima')

@section('content')

<div class="miniclima-map">

    <div class="map-controls">
        <button type="button">+</button>
        <button type="button">−</button>
    </div>

    <div class="map-name name-1">Área Norte</div>
    <div class="map-name name-2">Estufa Central</div>
    <div class="map-name name-3">Setor Leste</div>
    <div class="map-name name-4">Plantação Sul</div>
    <div class="map-name name-5">Reservatório</div>

    <div class="climate-route route-green"></div>
    <div class="climate-route route-blue"></div>
    <div class="climate-route route-red"></div>
    <div class="climate-route route-orange"></div>

    <div class="farm-field field-1"></div>
    <div class="farm-field field-2"></div>
    <div class="farm-field field-3"></div>
    <div class="farm-field field-4"></div>

    <div class="sensor-point green-point p1">
        <span>🌡️</span>
        <strong>28°C</strong>
    </div>

    <div class="sensor-point blue-point p2">
        <span>💧</span>
        <strong>72%</strong>
    </div>

    <div class="sensor-point orange-point p3">
        <span>🌱</span>
        <strong>63%</strong>
    </div>

    <div class="sensor-point red-point p4">
        <span>☀️</span>
        <strong>31°C</strong>
    </div>

    <div class="sensor-point green-point p5">
        <span>🍃</span>
        <strong>12 km/h</strong>
    </div>

    <div class="floating-card card-temp">
        <span>Temperatura Média</span>
        <strong>29°C</strong>
    </div>

    <div class="floating-card card-umidade">
        <span>Umidade Média</span>
        <strong>68%</strong>
    </div>

    <div class="floating-card card-solo">
        <span>Umidade do Solo</span>
        <strong>63%</strong>
    </div>

</div>

@endsection