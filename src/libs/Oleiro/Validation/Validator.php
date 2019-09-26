<?php

namespace Oleiro\Validation;

class Validator
{

    // só checa se array tem somente 3 campos e se os campos não estão vazios, retorna false se não tem campos vazios e true se tiver campos vazios
    public static function checkEmptyFields($user_data)
    {
        $user_data_values = array_values($user_data);

        if (count($user_data_values) != 3) {
            return true;
        } elseif ((strlen($user_data_values[0]) == 0) || (strlen($user_data_values[1]) == 0) || (strlen($user_data_values[2]) == 0)) {
            return true;
        }

        return false;

    }
    // Função que verifica se os campos possuem os nomes corretos...retorna true se tiverem ou false se não tiverem
    public static function testFieldsNames($user_data)
    {
        if ((array_key_exists('nickname', $user_data) == false) || (array_key_exists('email', $user_data) == false) || (array_key_exists('passwd', $user_data) == false)) {
            return false;
        }
        return true;
    }
    // Função para validar o nickname, retorna true se os campos estiverem ok
    public static function validateNickname($nickname)
    {

        $pattern_nickaname = "/([A-Z]*|[a-z]*|[0-9]*|[\-_\$]*)^.{4,10}/";
        return self::validateReGex($pattern_nickaname, $nickname);
    }
    // Função para validar o email, retorna true se os campos estiverem ok
    public static function validateEmail($email)
    {

        $pattern_email = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
        return self::validateReGex($pattern_email, $email);
    }
    // Função para validar o passwd, retorna true se os campos estiverem ok
    public static function validatePasswd($passwd)
    {

        $pattern_passwd = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/";
        return self::validateReGex($pattern_passwd, $passwd);
    }
    // Função que testa um campo dado utilizando uma regex informada.
    public static function validateReGex($pattern, $field)
    {
        return (preg_match($pattern, $field));
    }

}
