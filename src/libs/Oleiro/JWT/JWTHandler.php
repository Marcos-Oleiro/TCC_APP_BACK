<?php

namespace Oleiro\JWT;

header('Access-Control-Allow-Origin: *');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha512;

class JWTHandler
{

    // construtor do token JWT
    public static function jwtBuilder($user_id)
    {
        $signer = new Sha512();

        $token = (new Builder())->setIssuer('http://oleirosoftware.com') // Configures the issuer (iss claim)
            ->setAudience('http://oleirosoftware.org') // Configures the audience (aud claim)
            ->setIssuedAt(time()) // Configures the time that the token was issued (iat claim)
        // ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
            ->setSubject($user_id)
            ->set('valid', true)
            ->sign($signer, $_ENV["JWT_KEY"]) // creates a signature using "testing" as key
            ->getToken(); // Retrieves the generated token

        return $token;
    }
    // confere a validade, autenticidade e propriedade do token
    public static function validateToken($str_token, $id)
    {

        $signer = new Sha512();

        // tranformar a String do token em Obj
        $token = (new Parser())->parse((string) $str_token);

        // validar a ID
        if (((int) $id) != ((int) $token->getClaim('sub'))) {
            return false;
        }
        // verificar a validade
        if (!$token->getClaim('valid')) {
            return false;
        }

        // verificar a autenticidade
        if (!$token->verify($signer, $_ENV['JWT_KEY'])) {
            return false;
        }

        return true;
    }
    // validar o tipo do token
    public static function validateAuthType($auth_string)
    {

        $auth_type = strtolower(explode(" ", $auth_string)[0]);
        return (strcmp($auth_type, "bearer") == 0);
    }
    // função para fazer a verificação total do token
    public static function verifyToken($tkn_auth, $id)
    {

        if (!self::validateAuthType($tkn_auth)) {
            return false;
        }

        $str_token = explode(" ", $tkn_auth)[1];

        if (!self::validateToken($str_token, $id)) {
            return false;
        }

        return true;
    }

}
