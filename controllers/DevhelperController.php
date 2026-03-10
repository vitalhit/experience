<?php

namespace app\controllers;

use Yii;
use app\models\LogCron;
use app\models\Messages;
use app\models\Tickets;
use app\models\Rents;
use app\models\Seats;
use app\models\Biblioevents;
use app\models\Events;
use app\models\Persons;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * DevhelperController implements the CRUD actions for Messages model.
 */
class DevhelperController extends Controller
{

	public function actionCheck()
	{

		$messages = Messages::find()->orderBy(['id'=>SORT_DESC])->limit(2)->all();
		echo "<pre>";
		print_r($messages);
		echo "</pre>";

	}

	public function actionCacheflush()
	{

		Yii::$app->cache->flush();
		echo 'выполнено!';
	}




}
