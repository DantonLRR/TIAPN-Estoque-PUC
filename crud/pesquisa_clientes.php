<?php
class pesquisa{
    function buscarClientes($conn, $id = null){
        $lista = array();
        
        if($id != null){
            
            $sql = "SELECT * FROM clientes WHERE id = '$id'";
        } else {
            
            $sql = "SELECT * FROM clientes";
        }
        
        $resultado = mysqli_query($conn, $sql);
        
        if($resultado){
            while ($row = mysqli_fetch_assoc($resultado)){
                array_push($lista, $row);
            };
        }
        
        return $lista;
    }
    function buscarClientesSimples($conn){
    $lista = array();

    $sql = "SELECT 
                id,
                nome,
                tipo_cliente,
                cpf,
                cnpj
            FROM clientes
            ORDER BY nome ASC";

    $resultado = mysqli_query($conn, $sql);

    if($resultado){
        while ($row = mysqli_fetch_assoc($resultado)){
            array_push($lista, $row);
        }
    }

    return $lista;
}

}
?>