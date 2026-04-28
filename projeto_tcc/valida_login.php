<?php

session_start();
require_once("conexao/conexao_banco.php");

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $sql = "SELECT * FROM cad_usuarios WHERE cad_usuario_email = '$email' AND cad_usuario_ativo_sn = 'S'";

    $query = mysqli_query($_SG['link'], $sql);

    if (!$query){
        echo "Erro: Contate o suporte!" ;
        exit;
    }

    $usuario = mysqli_fetch_assoc($query);

    if ($usuario){

        $senha_banco = $usuario['cad_usuario_senha'];

        
        if($senha == $senha_banco){

            $_SESSION['usuario_id']             = $usuario['cad_usuario_id'];
            $_SESSION['usuario_nome']           = $usuario['cad_usuario_nome'];
            $_SESSION['tipo_login']             = $usuario['cad_usuario_tipo_login'];

            header("Location: dashboard.php");
            exit;

        }else{

            $_SESSION['erro_login'] = "Senha incorreta!";
            header("Location: index.php");
            exit;

        }

    }else{

        $_SESSION['erro_login'] = "Erro!";
        header("Location: index.php");
        exit;
        
    }