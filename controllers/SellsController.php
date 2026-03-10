<?php

namespace app\controllers;

use Yii;
use app\models\Sells;
use app\models\Persons;
use app\models\Goods;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SellsController implements the CRUD actions for Sells model.
 */
class SellsController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['index', 'create', 'view', 'update', 'delete'],
				'rules' => [
					[
						'allow' => false,
						'actions' => ['index', 'create', 'view', 'update', 'delete'],
						'roles' => ['?'],
					],
					[
						'allow' => true,
						'actions' => ['index', 'create', 'view', 'update', 'delete'],
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Lists all Sells models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$sells = Sells::find()->joinWith('persons')->joinWith('goods')->orderBy(['id'=>SORT_DESC])->all();
		
		$summnal2 = Sells::find()->where(['type' => 1])->andwhere('DATE(date) = DATE(NOW())')->sum('itogo');
		$summbez2 = Sells::find()->where(['type' => 2])->andwhere('DATE(date) = DATE(NOW())')->sum('itogo');
		$summgr2 = Sells::find()->where(['type' => 4])->andwhere('DATE(date) = DATE(NOW())')->sum('itogo');

		return $this->render('index.twig', ['sells' => $sells, 'summnal2' => $summnal2, 'summbez2' => $summbez2, 'summgr2' => $summgr2]);
	}

	/**
	 * Displays a single Sells model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Sells model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Sells();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Sells model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Sells model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Sells model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Sells the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Sells::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
