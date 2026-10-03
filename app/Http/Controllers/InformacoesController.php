<?php

namespace App\Http\Controllers;

class InformacoesController extends Controller
{
    public function index()
    {
        return view('informacoes.index');
    }
}