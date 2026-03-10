<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Band;
use app\models\BandPerson;
use app\models\BandPlist;
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


class BandController extends Controller
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

		$bands = Band::find()
		->where(['band.company_id' => Companies::getCompanyId()])
		->joinWith('artists')
		->all();

		// echo "<pre>";
		// print_r($bands);
		// echo "</pre>";

		return $this->render('index.twig', ['bands' => $bands]);
	}



	public function actionView($id)
	{
		$band = Band::find()->where('band.id = :bid', [':bid' => $id])->andWhere(['band.company_id' => Companies::getCompanyId()])
		->joinWith('events')->joinWith('events.biblioevents')->joinWith('events.biblioevents.cities')->joinWith('events.biblioevents.landing')->joinWith('artists')
		->one();


		// echo "<pre>";
		// print_r($plist);
		// echo "</pre>";

		if (empty($band)) {
			// Проверим в других компаниях
			$band = Band::find()->where('band.id = :id', [':id' => $id])->andWhere(['band.company_id' => Companies::getIds()])->joinWith('artists')->one();
			if (!empty($band)) {
				Yii::$app->getSession()->setFlash('success', 'Этот бенд в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($band->company_id);
				return $this->redirect(['/crm/band/view?id='.$band->id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого бенда.');
				return $this->redirect('/site/404');
			}
		}

		return $this->render('view.twig', ['band' => $band ]);
	}




	public function actionTeam($id)
	{
		$band = Band::find()->where('band.id = :bid', [':bid' => $id])->andWhere(['band.company_id' => Companies::getCompanyId()])->joinWith('artists')->one();
		return $this->render('team.twig', ['band' => $band]);
	}


	public function actionAdd()
	{
		$band = Band::findOne($_POST['band_id']);
		if (!empty($band)) {
			$user = BandPerson::find()->where(['band_id' => $band->id, 'person_id' => $_POST['person_id']])->one();
			if (empty($user)) {
				$user = new BandPerson;
				$user->band_id = $_POST['band_id'];
				$user->person_id = $_POST['person_id'];
				$user->save();
			}
			return $this->redirect(['/crm/band/team?id='.$band->id]);
		}

	}



	public function actionUnlink($id, $band_id)
	{
		$band = Band::find()->where('band.id = :bid', [':bid' => $band_id])->andWhere(['band.company_id' => Companies::getCompanyId()])->one();
		$user = BandPerson::find()->where(['band_id' => $band->id, 'person_id' => $id])->one();
		if (!empty($user)) {
			$user->delete();
		}
		return $this->redirect(['/crm/band/team?id='.$band->id]);
	}



	public function actionCreate()
	{
		$model = new Band();
		
		if ($model->load(Yii::$app->request->post())) {
			$img = UploadedFile::getInstance($model, 'image');
			if (!empty($img)) {
				$model->image = Yii::$app->storage->saveUploadedFile($img);
			}

			//echo "<pre>";  print_r($model); echo "</pre>";
			$model->save();

			Yii::$app->getSession()->setFlash('success', 'Бенд создан.');
			return $this->redirect(['/crm/band/view', 'id' => $model->id]);
		} else {
			return $this->render('update.twig', ['model' => $model]);
		}
	}



	public function actionUpdate($id)
	{
		$model = Band::find()->where('band.id = :bid', [':bid' => $id])->andWhere(['band.company_id' => Companies::getCompanyId()])->one();

		if (empty($model)) {
			// Проверим доступ к бенду в других компаниях
			$model = Band::find()->where('band.id = :bid', [':bid' => $id])->andWhere(['band.company_id' => Companies::getIds()])->one();
			if (!empty($model)) {
				Yii::$app->getSession()->setFlash('success', 'Этот бенд в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($model->company_id);
				return $this->redirect(['/crm/band/update?id='.$model->id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого бенда.');
				return $this->redirect('/site/404');
			}
		}

		$image = $model->image;

		if ($model->load(Yii::$app->request->post())) {

			$img = UploadedFile::getInstance($model, 'image');
			if ($img) {
				$model->image = Yii::$app->storage->saveUploadedFile($img);

// 				echo "<pre>";
// 		print_r($img);
// 		echo "</pre>";
// die;
			}else {
				$model->image = $image;
			}
			$model->save();
			Yii::$app->getSession()->setFlash('success', 'Бенд сохранен.');
			return $this->redirect(['/crm/band/view', 'id' => $model->id]);
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