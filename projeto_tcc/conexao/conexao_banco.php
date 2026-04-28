<?php

   $_SG = array();

    $_SG['link'] = mysqli_connect("localhost", "root", "123456", "usuarios");

    if (!$_SG['link']){
        die("Erro de conexão: " . mysqli_connect_error());
    }
    
?>