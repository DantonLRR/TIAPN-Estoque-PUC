<?php
// Configurações do banco de dados
define('HOST', 'localhost'); 
define('USUARIO', 'root');   
define('SENHA', '');         
define('DB', 'banco_tiapn'); 


$conn = mysqli_connect(HOST, USUARIO, SENHA, DB);


if (!$conn) {
    die('Falha na conexão com o banco de dados: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8');
?>
