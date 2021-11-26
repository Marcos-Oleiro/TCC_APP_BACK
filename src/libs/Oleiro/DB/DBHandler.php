<?php

namespace Oleiro\DB;
header('Access-Control-Allow-Origin: *');


class DBHandler
{
    // Verifica se o campo e-mail já é cadastrado no banco de dados.
    public static function checkNewEmail($email, $db_con)
    {

        $stmt = $db_con->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false

        if ($row == false) {
            return true; // true indica que o usuário é novo
        }
        return false;
    }
    // Verifica se o campo nickname já é cadastrado no banco de dados.
    public static function checkNewNickname($nickname, $db_con)
    {

        $stmt = $db_con->prepare("SELECT * FROM users WHERE nickname = :nickname");
        $stmt->bindParam(':nickname', $nickname);
        $stmt->execute();
        $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false

        if (($row == false)) {
            return true; // true indica que o usuário é novo
        }

        return false;
    }
    // função que salva no banco de dados o usuário recém registrado
    public static function saveNewUser($nickname, $email, $passwd, $db_con)
    {
        $stmt = $db_con->prepare("INSERT INTO users (nickname, email,passwd) VALUES(:nickname,:email,:passwd)");
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $passwd);
        $stmt->execute();
    }
    // função devolve o password que vai ser salvo no banco de dados
    // public static function dbPass($passwd)
    // {
    //     return hash('sha256', $passwd . 'nirvana');
    // }
    // função que verifica as credenciais do usuário
    public static function checkUser($email, $passwd, $db_con)
    {

        $stmt = $db_con->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
        // return "olá";
        // return $passwd;
        if ($row != false) {
            if ($row['passwd'] == $passwd) {
                // return print_r($row);
                return $row['id'];
            } else {
                return 'Senha incorreta';
            }

        } else {
            return 'E-mail ou Senha incorretos';
        }

    }
    // função retorna os dados do usuário
    public static function getUserData($id, $db_con)
    {

        $stmt = $db_con->prepare("SELECT photography, nickname, description FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch();
        // return $id;
        return $row;

    }
    // função para atualizar a descrição do usuário
    public static function updateDescription($new_desc, $id, $db_con)
    {

        $stmt = $db_con->prepare("UPDATE users SET description = :dcpt WHERE id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":dcpt", $new_desc);
        $result = $stmt->execute();

        return $result;
    }
    // função para verificar a senha com a ID informada
    public static function checkPasswd($id, $passwd, $db_con)
    {

        $stmt = $db_con->prepare("SELECT passwd FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return (strcmp($stmt->fetch()['passwd'], $passwd) == 0);
    }
    // função para atualizar a senha do usuário
    public static function updatePasswd($id, $passwd, $db_con)
    {

        $stmt = $db_con->prepare("UPDATE users SET passwd = :passwd WHERE id = :id");

        $stmt->bindParam(":passwd", $passwd);
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();

        return $result;
    }


    public static function updateLocation( $id, $lat, $long, $db_con){

        $stmt = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":lat", $lat);
        $stmt->bindParam(":long", $long);

        $result = $stmt->execute();
        return $result;
    }

}
