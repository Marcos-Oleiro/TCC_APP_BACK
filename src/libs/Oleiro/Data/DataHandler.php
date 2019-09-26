<?php

namespace Oleiro\Data;


class DataHandler
{
    // função devolve o password que vai ser salvo no banco de dados
    public static function dbPass($passwd)
    {
        return hash('sha256', $passwd . 'nirvana');
    }
    // função que transforma a id do usuário para base64 ⁽concatenada com uma string), para ser enviada para o cliente
    public static function idEncryptor($id)
    {
        return base64_encode($id . 'teste');

    }
    // função que pega a id, em base 64, e devolve a id "limpa" para processamento
    public static function idDecryptor($str)
    {

        $str_crpt = base64_decode($str);

        // tamanho da string segredo utilizada para criar a string em base64
        // quando for pro servidor, utilizar variável de ambiente (?) e pegar o seu tamanho
        // MUDAR PARA VARIÁVEL DE AMBIENTE DEPOIS!!!!
        $size_secret = 5;
        // -----------------------------------

        return substr($str_crpt, 0, (strlen($str_crpt)) - $size_secret);

    }

}
