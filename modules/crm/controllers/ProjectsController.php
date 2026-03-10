<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Projects;
use app\models\Companies;
use app\models\Tasks;
use app\models\Users;
use app\models\ProjectsStatus;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller
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
		];
	}


	// МЕНЕДЖЕР

	public function actionMy()
	{
		if (empty(Companies::getCompany())) {
			Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
			return $this->redirect(['/crm/company/my']);
		}
		$projects = Projects::find()
			->where(['projects.company_id' => Companies::getCompanyId()])
			->joinWith('owners')
			->joinWith('tasks')
			->joinWith('place')
			->joinWith('status')
			->orderBy(['id'=>SORT_DESC])
			->all();
		$projects_count = count($projects);
		$projects_word = $this->pluralForm($projects_count, 'проект', 'проекта', 'проектов');
		
		return $this->render('index.twig', [
			'projects' => $projects, 'projects_count' => $projects_count, 'projects_word' => $projects_word,
		]);
	}



	// Склонение существительных с числительными 
	static function pluralForm($n, $form1, $form2, $form5) {
		$n = abs($n) % 100;
		$n1 = $n % 10;
		if ($n > 10 && $n < 20) return $form5;
		if ($n1 > 1 && $n1 < 5) return $form2;
		if ($n1 == 1) return $form1;
		return $form5;
	}



	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}



	public function actionCreate()
	{
		$company = Companies::getCompany();
		$model = new Projects();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', 'Проект создан.');
			return $this->redirect(['/crm/tasks/create?projectid='.$model->id]);
		} else {
			return $this->render('create.twig', ['model' => $model, 'company' => $company]);
		}
	}



	public function actionUpdate($id)
	{
		$model = Projects::find()->where(['id' => $id, 'company_id' => Companies::getCompanyId()])->one();
		if (empty($model)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого проекта.<br> Выберите компанию, если она не выбрана!');
			return $this->redirect(['/crm/projects/my']);
		}
		$statuses = ProjectsStatus::find()->all();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', 'Проект сохранен.');
			return $this->redirect(['/crm/tasks/create?projectid='.$model->id]);
		} else {
			return $this->render('update.twig', ['model' => $model, 'statuses' => $statuses]);
		}
	}



	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Projects model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Projects the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Projects::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
