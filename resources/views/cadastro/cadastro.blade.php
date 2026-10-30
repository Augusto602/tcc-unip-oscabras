<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title>Sistema Agrícola — Cadastro</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('imagem/icone_sistema.png') }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >
</head>

<body>

    <div class="bg-pattern"></div>
    <div class="grid-lines"></div>

    <div class="card-wrap">

        <div class="brand">

            <div class="brand-icon">

                <svg
                    width="34"
                    height="34"
                    viewBox="0 0 34 34"
                    fill="none"
                >
                    <path
                        d="M17 4C17 4 10 10 10 18C10 22.4 13.1 26 17 26C20.9 26 24 22.4 24 18C24 10 17 4 17 4Z"
                        fill="rgba(200,240,140,0.9)"
                    />

                    <path
                        d="M17 26V31"
                        stroke="rgba(200,240,140,0.7)"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    />

                    <path
                        d="M10 20C7 18 5 14 6 10"
                        stroke="rgba(160,220,100,0.5)"
                        stroke-width="1.2"
                        stroke-linecap="round"
                    />

                    <path
                        d="M24 20C27 18 29 14 28 10"
                        stroke="rgba(160,220,100,0.5)"
                        stroke-width="1.2"
                        stroke-linecap="round"
                    />

                    <circle
                        cx="17"
                        cy="18"
                        r="2.5"
                        fill="rgba(80,160,30,0.9)"
                    />
                </svg>

            </div>

            <p class="nome">
                Sistema Integrado De Navegação Autônoma
            </p>

            <p class="sub_nome">
                Agricultura Inteligente
            </p>

        </div>


        <div class="card">

            <p class="titulo">
                Criar conta
            </p>


            @if ($errors->any())

                <div class="erro_login">

                    @foreach ($errors->all() as $erro)

                        <div>
                            {{ $erro }}
                        </div>

                    @endforeach

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('cadastro.salvar') }}"
            >

                @csrf


                {{-- NOME --}}

                <div class="field">

                    <label>
                        Nome completo
                    </label>

                    <div class="field-wrap">

                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                        >

                            <circle
                                cx="8"
                                cy="5"
                                r="3"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                            />

                            <path
                                d="M2.5 14c.5-3 2.4-4.5 5.5-4.5s5 1.5 5.5 4.5"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />

                        </svg>


                        <input
                            type="text"
                            name="nome"
                            value="{{ old('nome') }}"
                            placeholder="Digite seu nome"
                            autocomplete="name"
                            required
                        >

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="field">

                    <label>
                        E-mail
                    </label>

                    <div class="field-wrap">

                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                        >

                            <rect
                                x="1"
                                y="3"
                                width="14"
                                height="10"
                                rx="2"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                            />

                            <path
                                d="M1 5l7 5 7-5"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />

                        </svg>


                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="seu@email.com.br"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                {{-- SENHA --}}

                <div class="field">

                    <label>
                        Senha
                    </label>

                    <div class="field-wrap">

                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                        >

                            <rect
                                x="3"
                                y="7"
                                width="10"
                                height="7"
                                rx="1.5"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                            />

                            <path
                                d="M5 7V5a3 3 0 016 0v2"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />

                            <circle
                                cx="8"
                                cy="10.5"
                                r="1"
                                fill="#7aaa6a"
                            />

                        </svg>


                        <input
                            type="password"
                            name="senha"
                            placeholder="Digite uma senha"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                {{-- CONFIRMAR SENHA --}}

                <div class="field">

                    <label>
                        Confirmar senha
                    </label>

                    <div class="field-wrap">

                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                        >

                            <rect
                                x="3"
                                y="7"
                                width="10"
                                height="7"
                                rx="1.5"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                            />

                            <path
                                d="M5 7V5a3 3 0 016 0v2"
                                stroke="#7aaa6a"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />

                            <circle
                                cx="8"
                                cy="10.5"
                                r="1"
                                fill="#7aaa6a"
                            />

                        </svg>


                        <input
                            type="password"
                            name="senha_confirmation"
                            placeholder="Confirme sua senha"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                <div class="divider"></div>


                <button
                    type="submit"
                    class="btn-login"
                >
                    Criar minha conta
                </button>


                <div class="cadastro-link">

                    <span>
                        Já possui cadastro?
                    </span>

                    <a href="{{ route('login') }}">
                        Entrar no sistema
                    </a>

                </div>

            </form>

        </div>


        <p class="footer-note">
            Sistema Agrícola v1.0 º TCC 2026
        </p>

    </div>

</body>
</html>
