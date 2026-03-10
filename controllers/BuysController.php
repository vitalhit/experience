<?php

namespace app\controllers;

use Yii;
use app\models\Buys;
use app\models\Sells;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BuysController implements the CRUD actions for Buys model.
 */
class BuysController extends Controller
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
		$buys = Buys::find()->orderBy(['id'=>SORT_DESC])->all();

		$sklads = Sells::findBySql('
			SELECT
				sells.good_id,
				goods.name,
                buys.count as type,
                buys.price as price,
				SUM(sells.count) as count,
				SUM(sells.itogo) as itogo,           
				buys.count - SUM(sells.count) as info
				FROM sells 
				LEFT JOIN goods ON sells.good_id = goods.id
				LEFT JOIN buys ON sells.good_id = buys.good_id
				GROUP BY good_id Order BY goods.name ASC')->all();
				

		return $this->render('index.twig', ['buys' => $buys, 'sklads' => $sklads]);
	}

	/**
	 * Displays a single Buys model.
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
	 * Creates a new Buys model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Buys();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Buys model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Buys model.
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
	 * Finds the Buys model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Buys the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Buys::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
