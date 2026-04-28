<?php

    session_start();
    require_once("conexao/conexao_banco.php");

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit;
    }


    $data_atual       = date("Y-m-d");
    $hora_atual       = date("H:i:s");
    $nome_usuario = $_SESSION['usuario_nome'];


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $nome       = trim((isset($_POST["nome"])) ? $_POST ["nome"] : '');
        $email      = trim((isset($_POST["email"])) ? $_POST ["email"] : '');
        $senha      = trim((isset($_POST["senha"])) ? $_POST ["senha"] : '');
        $tipo_login      = trim((isset($_POST["tipo_login"])) ? $_POST ["tipo_login"] : '');

        $sql_insert = "INSERT INTO cad_usuarios  (cad_usuario_nome,
                                                  cad_usuario_ativo_sn,
                                                  cad_usuario_dt_cad,
                                                  cad_usuario_usu_cad,
                                                  cad_usuario_tipo_login,
                                                  cad_usuario_senha,
                                                  cad_usuario_email)
                                                                            
                                        VALUES ('$nome',
                                                'S',
                                                '$data_atual $hora_atual',
                                                '$nome_colaborador',
                                                '$tipo_login',
                                                '$senha',
                                                '$email');";

        $query_insert = mysqli_query($_SG['link'], $sql_insert);

        if (!$query_insert){

            echo "Erro: " . mysqli_error($_SG['link']);
            exit;

        }else{

            echo "<script language='javascript' type='text/javascript'>window.location.href='cad_clientes.php?usu_criado=S';</script>";
            return true;
            exit;

        }

    }

    exit;

?>