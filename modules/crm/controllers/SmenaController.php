<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Smena;
use app\models\EventFinance;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SmenaController extends \yii\web\Controller
{


	public function behaviors()
	{
		return [
		'verbs' => [
		'class' => VerbFilter::className(),
		'actions' => [
		'delete' => ['POST'],
		],
		],
		];
	}

	public $enableCsrfValidation = false;

	protected function findModel($id)
    {
        if (($model = Smena::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	public function actionIndex()
	{
		$all = Smena::find()->orderBy(['id' => SORT_DESC])->all();

		return $this->render('index.twig', ['all'=>$all ]);

	}


	public function actionView($id)
    {
        $finance = EventFinance::find()->where(['smena_id' => $id] )->all();

  // 		echo "<pre>";
		// print_r($finance);
		// echo "</pre>";
  //       $itog = 0;
		// foreach ($f as $finance ) {
		// 	$itog+= $f->smumm;
		// }

        return $this->render('view.twig', [
            'model' => $this->findModel($id), 'finance' => $finance
        ]);


    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }



        return $this->render('update', ['model' => $model]);
    }



	public function actionFormstartsmena()
	{
    	// Получаем id текущего пользователя
		$uid = Yii::$app->user->id;
		$user = Users::findOne($uid);

    	// Берем последнюю смену этого пользователя
		$last_smena = Smena::find()->where(['user_id' => $uid])->orderBy(['id'=>SORT_DESC])->one();

		return $this->renderPartial('form_startsmena.twig', ['user'=>$user, 'last_smena'=>$last_smena]);
	}



	public function actionStartsmena()
	{
    	// Получаем id текущего пользователя
		$uid = Yii::$app->user->id; 

    	// Начинаем новую смену
		$smena = new Smena();
		$smena->user_id = $uid;

		if(isset($_POST['type'])){
			if($_POST['type'] == 'Ресепшн'){
				$smena->type = 1;	
			}
			else if($_POST['type'] == 'Бар'){
				$smena->type = 2;	
			}
			else if($_POST['type'] == 'Другое'){
				$smena->type = 3;	
			}
		}

		$smena->save();

		return $this->redirect(['/crm/persons']);
	}



	public function actionFormendsmena()
	{
    	// Получаем id текущего пользователя
		$uid = Yii::$app->user->id;
		$user = Users::findOne($uid);

    	// Берем последнюю смену этого пользователя
		$last_smena = Smena::find()->where(['user_id' => $uid])->orderBy(['id'=>SORT_DESC])->one();
		
		// Считаем кол-во смен
		$all_smena = Smena::find()->where(['user_id' => $uid])->count();
		
		if ($last_smena->end == NULL) {
			$last_smena->end = date("Y-m-d H:i:s");
			$last_smena->time = gmdate("H:i:s", (strtotime($last_smena->end) - strtotime($last_smena->start)));
		}

		return $this->renderPartial('form_endsmena.twig', ['user'=>$user, 'last_smena'=>$last_smena, 'all_smena'=>$all_smena]);
	}



	public function actionEndsmena()
	{
    	// Получаем id текущего пользователя
		$uid = Yii::$app->user->id; 

    	// Берем последнюю смену этого пользователя
		$last_smena = Smena::find()->where(['user_id' => $uid])->orderBy(['id'=>SORT_DESC])->one();

		if ($last_smena->end == NULL) {
			$last_smena->end = date("Y-m-d H:i:s");
			$last_smena->time = gmdate("Y-m-d H:i:s", (strtotime($last_smena->end) - strtotime($last_smena->start)));
			$last_smena->save();
		}

		return $this->redirect(['/crm/persons']);
	}

    /**
     * Lists all Abonements models.
     * @return mixed
     */
    public function actionSmenaavtoclose()
    {
    	$smenas = Smena::find()->where(['end' => NULL])->all();
    	foreach ($smenas as $smena) {
			$smena->end = date("Y-m-d H:i:s");
			$smena->time = gmdate("Y-m-d H:i:s", (strtotime($smena->end) - strtotime($smena->start)));
			$smena->save();
    	}
    }


}
