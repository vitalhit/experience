<?php

namespace app\controllers;

use Yii;

use app\models\Places;

use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{

    public $enableCsrfValidation = false; // проверка формы на подленные данные

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    // Список мест
    public function actionPlaceList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $get = Yii::$app->request->get();

        if (empty($get['value'])) {
            return null;
        }

        $places = Places::find()->select(['places.id', 'places.name', 'cities.id as city_id', 'cities.name as city_name'])
        ->where(['like', 'places.name', $get['value']])->andWhere(['>', 'places.status', 0])
        ->join('LEFT JOIN', 'cities', 'cities.id = places.city')
        ->asArray()->limit(10)->all();

        // file_put_contents('test.txt', PHP_EOL .'places: '. json_encode($places) . PHP_EOL, FILE_APPEND);

        return [
            'list' => $this->renderPartial('place_list.twig', ['places' => $places])
        ];
    }



}
