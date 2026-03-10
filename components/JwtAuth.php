
<?php
namespace app\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;
use app\models\User;

class JwtAuth extends ActionFilter
{
    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;
        $auth = $headers->get('Authorization');

        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            throw new UnauthorizedHttpException('Missing token');
        }

        $token = trim(str_replace('Bearer ', '', $auth));

        try {
            $data = Yii::$app->jwt->decode($token);
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        $user = User::findOne($data['id']);
        if (!$user) {
            throw new UnauthorizedHttpException('User not found');
        }

        Yii::$app->user->login($user);

        return parent::beforeAction($action);
    }
}