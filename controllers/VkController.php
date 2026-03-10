<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Vk;
use app\models\Vkcallback;
use app\models\Persons;
use app\models\Users;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;

class VkController extends Controller
{
	public $enableCsrfValidation = false;


	// Расширение для Chrome
	public function actionIndex()
	{
		
	}


	// Принимаем post из группы вк (от бота)
	public function actionCallback()
	{
		// return 'e50214b8';
		$handler = new Vkcallback(); 
		$data = json_decode(file_get_contents('php://input')); 
		$handler->parse($data);
	}


	public function actionTest()
	{
		Vk::Send(  'Тест', ['90794']);
	}
}
