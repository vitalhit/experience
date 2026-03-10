<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Tour;

use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\Section;
use app\models\Events;
use app\models\Tickets;
use app\models\Users;
use app\models\Persons;
use app\models\Plist;
use app\models\Categoryevents;
use app\models\Companies;
use app\models\CompanyUser;
use app\models\PictureForm;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;


class TourController extends Controller
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

	public $enableCsrfValidation = false;


	public function actionIndex()
	{
		if (empty(Companies::getCompany())) {
			Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
			return $this->redirect(['/crm/company/my']);
		}

		$tours = Tour::find()
		->where(['tour.company_id' => Companies::getCompanyId()])
		->all();

		 // echo "<pre>";
		 // print_r($tours);
		 // echo "</pre>";

		return $this->render('index.twig', ['tours' => $tours]);
	}



	public function actionView($id)
	{
		$tour = Tour::find()->where('tour.id = :bid', [':bid' => $id])->andWhere(['tour.company_id' => Companies::getCompanyId()])
		->joinWith('events')->joinWith('events.biblioevents')->joinWith('events.biblioevents.cities')->joinWith('events.biblioevents.landing')->joinWith('artists')
		->one();


		// echo "<pre>";
		// print_r($plist);
		// echo "</pre>";

		if (empty($tour)) {
			// Проверим в других компаниях
			$tour = Tour::find()->where('tour.id = :id', [':id' => $id])->andWhere(['tour.company_id' => Companies::getIds()])->joinWith('artists')->one();
			if (!empty($tour)) {
				Yii::$app->getSession()->setFlash('success', 'Этот бенд в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($tour->company_id);
				return $this->redirect(['/crm/tour/view?id='.$tour->id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого бенда.');
				return $this->redirect('/site/404');
			}
		}

		return $this->render('view.twig', ['tour' => $tour]);
	}




	public function actionTeam($id)
	{
		$tour = Tour::find()->where('tour.id = :bid', [':bid' => $id])->andWhere(['tour.company_id' => Companies::getCompanyId()])->joinWith('artists')->one();
		return $this->render('team.twig', ['tour' => $tour]);
	}


	public function actionAdd()
	{
		$tour = Tour::findOne($_POST['tour_id']);
		if (!empty($tour)) {
			$user = tourPerson::find()->where(['tour_id' => $tour->id, 'person_id' => $_POST['person_id']])->one();
			if (empty($user)) {
				$user = new tourPerson;
				$user->tour_id = $_POST['tour_id'];
				$user->person_id = $_POST['person_id'];
				$user->save();
			}
			return $this->redirect(['/crm/tour/team?id='.$tour->id]);
		}

	}



	public function actionUnlink($id, $tour_id)
	{
		$tour = Tour::find()->where('tour.id = :bid', [':bid' => $tour_id])->andWhere(['tour.company_id' => Companies::getCompanyId()])->one();
		$user = tourPerson::find()->where(['tour_id' => $tour->id, 'person_id' => $id])->one();
		if (!empty($user)) {
			$user->delete();
		}
		return $this->redirect(['/crm/tour/team?id='.$tour->id]);
	}



	public function actionCreate()
	{
		$model = new Tour();

		if ($model->load(Yii::$app->request->post())) {
			
			$model->save();

			Yii::$app->getSession()->setFlash('success', 'Тур создан.');
			return $this->redirect(['/crm/tour/view', 'id' => $model->id]);
		} else {
			return $this->render('update.twig', ['model' => $model]);
		}
	}



	public function actionUpdate($id)
	{
		$model = Tour::find()->where('tour.id = :bid', [':bid' => $id])->andWhere(['tour.company_id' => Companies::getCompanyId()])->one();

		if (empty($model)) {
			// Проверим доступ к бенду в других компаниях
			$model = Tour::find()->where('tour.id = :bid', [':bid' => $id])->andWhere(['tour.company_id' => Companies::getIds()])->one();
			if (!empty($model)) {
				Yii::$app->getSession()->setFlash('success', 'Этот бенд в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($model->company_id);
				return $this->redirect(['/crm/tour/update?id='.$model->id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого бенда.');
				return $this->redirect('/site/404');
			}
		}

		$image = $model->image;

		if ($model->load(Yii::$app->request->post())) {

			/* не загружаем картинку для тура
			$img = UploadedFile::getInstance($model, 'image');
			if ($img) {
				$model->image = Yii::$app->storage->saveUploadedFile($img);
			}else {
				$model->image = $image;
			}
			*/
			$model->save();
			Yii::$app->getSession()->setFlash('success', 'Тур сохранен.');
			return $this->redirect(['/crm/tour/view', 'id' => $model->id]);
		}
		return $this->render('update.twig', ['model' => $model]);
	}




	/* При создании события проверяем уникальность алиаса */
	public function actionAliasisset($alias, $city, $biblioeventid = false)
	{
		$alias = Biblioevents::find()->where(['alias' => $alias])->andWhere(['city' => $city])->one();
		if (!empty($biblioeventid) && $alias->id == $biblioeventid) {
			return;
		} else {
			return $alias->id;
		}
	}




	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(['index']);
	}



	protected function findModel($id)
	{
		if (($model = Biblioevents::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}