<?php
class pesquisa{
    function buscarUsuarios($conn){
        $lista = array();
        $sql = 'SELECT * FROM usuarios';
        $resultado = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($resultado)){
            array_push($lista, $row);
        };
        return $lista;
    }
}








?>