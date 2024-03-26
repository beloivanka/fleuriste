<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    // On genere le token

    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if($validity > 0){
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp()+$validity;
    
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

        //On encode en base64

        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //On nettoie les valeurs encodees (retrait des +, / et =)

        $base64Header = str_replace(['+','/','='], ['-','_',''], $base64Header);
        $base64Payload = str_replace(['+','/','='], ['-','_',''], $base64Payload);

        // On genere la signature

        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+','/','='], ['-','_',''], $base64Signature);

        //On cree le token

        $jwt = $base64Header . '.'.$base64Payload. '.'.$base64Signature;

        return $jwt;
    }

    //On verifie que le token est valide (correctement forme)
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        )===1;
    }

    //On recupere le Payload
    public function getPayload(string $token):array
    {
        //On demonte le token
        $array = explode('.',$token);

        //On decode le Payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

        //On recupere le Header
        public function getHeader(string $token):array
        {
            //On demonte le token
            $array = explode('.',$token);
    
            //On decode le Payload
            $header = json_decode(base64_decode($array[0]), true);
    
            return $header;
        }

    //On verifie si le token a expire
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    //On verifie la signature du token
    public function check(string $token, string $secret)
    {
        //On recupere le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //On regenere un token
        $verifToken = $this->generate($header, $payload, $secret, 0);
        
        return $token === $verifToken;
    }
}