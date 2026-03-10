<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\Cors;
use app\models\LoginForm;
use app\models\ShopOrder;
use app\models\Persons;
use app\models\Users;

class ShopController extends Controller
{
    public $enableCsrfValidation = false;

//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['corsFilter'] = ['class' => Cors::class];
//        $behaviors['authenticator'] = ['class' => JwtBearerAuth::class];
//        return $behaviors;
//    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Удаляем стандартный authenticator для OPTIONS запросов
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://igoshop.baraban.io', 'https://foursiz.com', 'http://localhost:8083'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Восстанавливаем authenticator
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\CompositeAuth::class,
            'except' => ['options'], // исключаем OPTIONS из аутентификации
        ];

        return $behaviors;
    }

    public function actionSaveorder()
    {
        // 2) Preflight — сразу 204, без вывода
        if (Yii::$app->request->isOptions) {
            Yii::$app->response->statusCode = 204;
            return;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        file_put_contents('test.txt', PHP_EOL . "POST: " . "// 3) Читаем JSON-тело", FILE_APPEND);

        // 3) Читаем JSON-тело
        $raw = Yii::$app->request->getRawBody();
        $data = json_decode($raw, true);



        if (!is_array($data)) {
            return ['ok' => false, 'error' => 'bad_json'];
        }
        file_put_contents('test.txt', PHP_EOL . "currency: " . print_r($data['currency'], true), FILE_APPEND);



        // TODO: валидация/сохранение $data в БД
        file_put_contents('test.txt', PHP_EOL . "POST: " . print_r($data, true), FILE_APPEND);
        file_put_contents('test.txt', PHP_EOL . "fio: " . print_r($data['fio'], true), FILE_APPEND);

        $order = new ShopOrder();
        $order->fio = $data['fio'];
        $order->phone = $data['phone'] ?? '';
        $order->mail = $data['mail'] ?? '';
        $order->address = $data['address'] ?? '';
        $order->comment = $data['comment'] ?? '';
        $order->total = $data['total'] ?? 0;


        if ( $data['currency'] == 'RUB'){ 
            $order->currency  = 'rub';
            $data['currency'] = 'rub';}

        $order->status = $data['status'] ?? 'new';


        // Преобразуем items в JSON если это массив
        if (isset($data['items']) && is_array($data['items'])) {
            $order->items = json_encode($data['items'], JSON_UNESCAPED_UNICODE);
        } else {
            $order->items = $data['items'] ?? '[]';
        }

        if ($order->save()) {
            file_put_contents('test.txt', PHP_EOL . "успешно", FILE_APPEND);

            // Успешное сохранение
            return [
                'success' => true,
                'message' => 'Заказ успешно создан',
                'order_id' => $order->id
            ];
        } else {


            // Ошибка сохранения
            $errors = $order->getErrors();
            file_put_contents('test.txt', PHP_EOL . "errors: " . print_r($errors, true), FILE_APPEND);
            Yii::error('Ошибка сохранения заказа: ' . print_r($errors, true));

            return [
                'success' => false,
                'message' => 'Ошибка при создании заказа',
                'errors' => $errors
            ];
        }

        return ['ok' => true, 'status' => 'saved', 'id' => 123];
    }

    public function actionRefreshToken()
    {
        // Получаем данные из POST запроса
        $post = Yii::$app->request->post();

        // Проверяем наличие refresh token
        if (empty($post['refresh_token'])) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Refresh token is required',
            ];
        }

        // Находим пользователя по refresh token
        $user = Users::findOne(['refresh_token' => $post['refresh_token']]);

        if ($user) {
            // Генерируем новый access token
            $accessToken = Yii::$app->jwt->generateAccessToken(['id' => $user->id]);

            // Генерируем новый refresh token (ротация токенов)
            $newRefreshToken = $user->generateRefreshToken();

            // Находим информацию о person
            $person = Persons::findOne($user->person_id);

            // Возвращаем ответ в формате JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'token' => $accessToken,
                'refresh_token' => $newRefreshToken,
                'person' => $person,
            ];
        } else {
            // Если refresh token невалиден
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->statusCode = 401;

            return [
                'success' => false,
                'message' => 'Invalid refresh token',
            ];
        }
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $user = Users::findOne(['username' => $post['email']]);

            if ($user && $user->validatePassword($post['password'])) {
                // Простой токен без JWT для теста
                $simpleToken = base64_encode($user->id . ':' . (time() + (24 * 60 * 60)));

//                // Генерируем JWT токен
//                $token = Yii::$app->jwt->generateToken(['id' => $user->id]);
//
               // Генерируем refresh token
               $refreshToken = Yii::$app->security->generateRandomString(128);
               $user->refresh_token = $refreshToken;
               if (!$user->save()) {
                   Yii::error('Failed to save refresh token: ' . json_encode($user->errors));
               }

                // Находим информацию о person
                $person = Persons::findOne($user->person_id);

//                // Возвращаем ответ в формате JSON
//                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//                return [
//                    'success' => true,
//                    'token' => $token,
//                    'refresh_token' => $refreshToken,
//                    'person' => $person,
//                ];

                return [
                    'success' => true,
                    'token' => $simpleToken,
                    'refresh_token' => 'temp-refresh-token',
                    'person' => $person,
                ];

            } else {

                Yii::$app->response->statusCode = 401;
                return [
                    'success' => false,
                    'message' => 'Invalid credentials',
                ];
            }

        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ];
        }

    }

    public function actionOrders()
    {
        // Извлекаем токен из заголовка Authorization
        $headers = Yii::$app->request->headers;
        $authHeader = $headers->get('Authorization');

        $token = null;

        if ($authHeader && preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }


        if (empty($token)) {
            return $this->asJson(['token пуст']);
        }
            
        $decoded = base64_decode($token);
        $parts = explode(':', $decoded);
        $user_id = $parts[0];

        
         // return $this->asJson([
         //        'success' => true,
         //        'orders' => $orders??Null,
         //        'decoded' => $decoded
         //    ]);

        //$decoded = Yii::$app->jwt->decode($token);
        
        // file_put_contents('test.txt', 
        //     PHP_EOL . Date('Y-m-d H:i:s') . ' decoded: ' . print_r($decoded, true), 
        //     FILE_APPEND
        // );

        $user = Users::findOne(['id' => $user_id]);
        $orders = ShopOrder::findByEmail($user['username']);
        return $this->asJson([
                'success' => true,
                'orders' => $orders
            ]);

    }

}
