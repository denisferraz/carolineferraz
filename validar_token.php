<?php

function validarToken(){

    $token = $_COOKIE['token'];

    $token_array = explode('.', $token);

    $header = $token_array[0];
    $payload = $token_array[1];
    $signature = $token_array[2];

    // Chave secreta e única
    $chave = "CGBU85S4623M5W4X6ODF";

    $validar_assinatura = hash_hmac('sha256', "$header.$payload", $chave, true);
    $validar_assinatura = base64_encode($validar_assinatura);
    if($signature == $validar_assinatura){
        $dados_token = base64_decode($payload);
        $dados_token = json_decode($dados_token);

        if($dados_token->exp > time()){

            return true;
        }else{

            return false;
        }        
    }else{ 

        return false;
    }    
}

function recuperarNomeToken(){
    $token = $_COOKIE['token'];

    $token_array = explode('.', $token);
    $payload = $token_array[1];

    $dados_token = base64_decode($payload);
    $dados_token = json_decode($dados_token);

    return $dados_token->nome;
}

function recuperarEmailToken(){

    $token = $_COOKIE['token'];

    $token_array = explode('.', $token);
    $payload = $token_array[1];
    $dados_token = base64_decode($payload);
    $dados_token = json_decode($dados_token);

    return $dados_token->email;
}