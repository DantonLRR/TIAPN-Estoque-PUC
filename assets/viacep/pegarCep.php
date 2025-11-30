<?php

/**
 * Retorna um objeto com endereço a partir do CEP.
 * Pode receber o CEP por argumento (string) ou usar $_POST['cep'] quando chamado por formulário.
 * Sempre devolve um objeto com as propriedades básicas (logradouro, bairro, localidade, uf, cep).
 */
function pegarEndereco($cepParam = null)
{
    $json_data = initempty();

    $cep = null;
    if ($cepParam !== null) {
        $cep = $cepParam;
    } elseif (isset($_POST['cep'])) {
        $cep = $_POST['cep'];
    } elseif (isset($_POST['cep_busca'])) {
        $cep = $_POST['cep_busca'];
    }

    if ($cep !== null) {
        $cep = filtrarCep($cep);
        if (isCep($cep)){
            $dados = getDadosViaCep($cep);
            if ($dados !== null && !property_exists($dados, 'erro')){
                $json_data = $dados;
            } else {
                $json_data = initempty();
                $json_data->cep = "CEP não encontrado";
            }
        } else {
            $json_data = initempty();
            $json_data->cep = "CEP inválido";
        }
    }

    return $json_data;
}

function filtrarCep($cep){
    return preg_replace("/[^0-9]/", "", $cep);
}

function isCep($cep){
    return preg_match("/^[0-9]{8}$/", $cep) === 1;
}

function getDadosViaCep($cep){
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $json = @file_get_contents($url);
    if ($json === false) return null;
    return json_decode($json);
}

function initempty(){
    $obj = new stdClass();
    $obj->cep = '';
    $obj->logradouro = '';
    $obj->complemento = '';
    $obj->bairro = '';
    $obj->localidade = '';
    $obj->uf = '';
    return $obj;
}
?>