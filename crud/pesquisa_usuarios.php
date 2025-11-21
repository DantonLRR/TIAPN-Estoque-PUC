<?php
class pesquisa{
    function buscarUsuarios($conn, $id = null){
        $lista = array();
        
        if($id != null){
            
            $sql = "SELECT * FROM usuarios WHERE id = '$id'";
        } else {
            
            $sql = "SELECT * FROM usuarios";
        }
        
        $resultado = mysqli_query($conn, $sql);
        
        if($resultado){
            while ($row = mysqli_fetch_assoc($resultado)){
                array_push($lista, $row);
            };
        }
        
        return $lista;
    }
}
?>