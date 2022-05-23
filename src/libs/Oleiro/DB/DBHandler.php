<?php

namespace Oleiro\DB;

header('Access-Control-Allow-Origin: *');


class DBHandler {
    // Verifica se o campo e-mail já é cadastrado no banco de dados.
    public static function checkNewEmail($email, $db_con): bool {

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
    public static function checkNewNickname($nickname, $db_con): bool {

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
    public static function saveNewUser($nickname, $email, $passwd, $db_con) {
        $stmt = $db_con->prepare("INSERT INTO users (nickname, email,passwd, radius) VALUES(:nickname,:email,:passwd, :radius)");
        $radius = 15;
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $passwd);
        $stmt->bindParam(':radius', $radius);
        $stmt->execute();
    }
    // função devolve o password que vai ser salvo no banco de dados
    // public static function dbPass($passwd)
    // {
    //     return hash('sha256', $passwd . 'nirvana');
    // }
    // função que verifica as credenciais do usuário
    public static function checkUser($email, $passwd, $db_con) {

        $stmt = $db_con->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
        // return "olá";
        // return $passwd;
        if ($row != false) {
            if ($row['passwd'] == $passwd) {
                // return print_r($row);
//                return $row['id'];
                return array(
                    'id' => $row['id'],
                    'radius' => $row['radius'],
                    'nickname' => $row['nickname']

                );
            } else {
                return 'Senha incorreta';
            }

        } else {
            return 'E-mail ou Senha incorretos';
        }

    }

    // função retorna os dados do usuário
    public static function getUserData($id, $db_con) {

        $stmt = $db_con->prepare("SELECT photography, nickname, description FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // return $id;
        return $stmt->fetch();

    }

    // função para atualizar a descrição do usuário
    public static function updateDescription($new_desc, $id, $db_con) {

        $stmt = $db_con->prepare("UPDATE users SET description = :dcpt WHERE id = :id");

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":dcpt", $new_desc);
        return $stmt->execute();
    }

    // função para verificar a senha com a ID informada
    public static function checkPasswd($id, $passwd, $db_con) {

        $stmt = $db_con->prepare("SELECT passwd FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return (strcmp($stmt->fetch()['passwd'], $passwd) == 0);
    }

    // função para atualizar a senha do usuário
    public static function updatePasswd($id, $passwd, $db_con) {

        $stmt = $db_con->prepare("UPDATE users SET passwd = :passwd WHERE id = :id");

        $stmt->bindParam(":passwd", $passwd);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }


    public static function updateLocation($id, $lat, $long, $db_con) {

        $stmt = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":lat", $lat);
        $stmt->bindParam(":long", $long);

        return $stmt->execute();
    }

    public static function findUserPerDistance ($db_con,$lat, $long, $distance, $id){

        $stmt = $db_con->prepare("SELECT b.id,b.nickname, a.lat, a.long,ROUND(earth_distance(ll_to_earth(:lt, :lg), 
                ll_to_earth(lat, long)) :: NUMERIC,2) AS distance FROM positions a INNER JOIN users b 
                on a.id_user=b.id WHERE earth_box(ll_to_earth (:lt, :lg), :dstc) @> ll_to_earth (lat, long) 
                AND earth_distance(ll_to_earth (:lt, :lg), ll_to_earth (lat, long)) < :dstc and b.id != :id ORDER BY distance;");

        $stmt->bindParam(":lt",$lat);
        $stmt->bindParam(":lg",$long);
        $stmt->bindParam(":dstc",$distance);
        $stmt->bindParam(":id",$id);
        $stmt->execute();

        return $stmt->fetchAll();

    }

    public static function saveRadius($db_con, $radius, $id){

        $stmt = $db_con->prepare("UPDATE users SET radius = :radius where id = :id");
        $radius = $radius/1000;
        $stmt->bindParam(":radius", $radius);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }


    public static function createLocations($db_con) {

        //origem
        $id1 = 1;
        $lat1 = -32.179611;
        $long1 = -52.153114;
        $stmt1 = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt1->bindParam(":id", $id1);
        $stmt1->bindParam(":lat", $lat1);
        $stmt1->bindParam(":long", $long1);
        $stmt1->execute();

        //5km
        $id2 = 2;
        $lat2 = -32.165899;
        $long2 = -52.196593;
        $stmt2 = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt2->bindParam(":id", $id2);
        $stmt2->bindParam(":lat", $lat2);
        $stmt2->bindParam(":long", $long2);
        $stmt2->execute();

        //10km
        $id3 = 3;
        $lat3 = -32.149585;
        $long3 = -52.219761;
        $stmt3 = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt3->bindParam(":id", $id3);
        $stmt3->bindParam(":lat", $lat3);
        $stmt3->bindParam(":long", $long3);
        $stmt3->execute();

        //15km
        $id4 = 4;
        $lat4 = -32.109856;
        $long4 = -52.263839;
        $stmt4 = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt4->bindParam(":id", $id4);
        $stmt4->bindParam(":lat", $lat4);
        $stmt4->bindParam(":long", $long4);
        $stmt4->execute();

        //20km
        $id5 = 5;
        $lat5 = -32.079079;
        $long5 = -52.307271;
        $stmt5 = $db_con->prepare("INSERT INTO positions (id_user, lat, long) VALUES (:id, :lat , :long)");
        $stmt5->bindParam(":id", $id5);
        $stmt5->bindParam(":lat", $lat5);
        $stmt5->bindParam(":long", $long5);
        $stmt5->execute();
    }
}
