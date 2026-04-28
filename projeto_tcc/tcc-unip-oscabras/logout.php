<?php
session_start();

// Remove todas as variáveis de sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para a página principal (login ou index)
header("Location: index.php");
exit;
?>