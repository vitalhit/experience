<?php
namespace app\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\base\Component;

class JwtService extends Component
{
    public string $secret;
    public string $algo = 'HS256';
    public int $expire = 3600; // 1 час
    public int $refreshTokenExpire = 2592000; // 30 дней для refresh token

    public function generateToken(array $payload): string
    {
        $payload['exp'] = time() + $this->expire;
        $payload['iat'] = time();
        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, $this->algo));
    }

    public function generateAccessToken(array $payload): string
    {
        $payload['exp'] = time() + $this->accessTokenExpire;
        $payload['iat'] = time();
        $payload['type'] = 'access';
        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function generateRefreshToken(array $payload): string
    {
        $payload['exp'] = time() + $this->refreshTokenExpire;
        $payload['iat'] = time();
        $payload['type'] = 'refresh';
        return JWT::encode($payload, $this->secret, $this->algo);
    }
}