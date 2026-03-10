<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Band;
use app\models\Biblioevents;
use app\models\Img;
use app\models\Landing;
use app\models\Places;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\imagine\Image;

/**
 * LandingController implements the CRUD actions for Landing model.
 */
class LandingController extends Controller
{
	/**
	 * {@inheritdoc}
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


	public function actionIndex()
	{
		$img = 'e4/f2/16cd827edc4c9f7099ca9ae87da2259a704c.jpg';
		// $image = Img::ImgThumb($img);
		$image = Img::find()->where(['image' => $img])->one();

		$dot = strrpos($image->image, '.');
		$name = substr($image->image, 0, $dot);
		$ext = substr($image->image, -(strlen($image->image) - $dot));

		if (empty($image->imgland)) {
			Image::thumbnail(Yii::$app->params['storagePath'].$image->image, 1300, null)
			->save(Yii::getAlias(Yii::$app->params['storagePath'].$name.'_imgland'.$ext), ['quality' => 65]);
			$image->imgland = $name.'_imgland'.$ext;
		}

		if (empty($image->imgrazdel)) {
			Image::thumbnail(Yii::$app->params['storagePath'].$image->image, 360, 240)
			->save(Yii::getAlias(Yii::$app->params['storagePath'].$name.'_imgrazdel'.$ext), ['quality' => 65]);
			$image->imgrazdel = $name.'_imgrazdel'.$ext;
		}

		$image->save();
		echo "<pre>";
		print_r($image);
		echo "</pre>";
	}



	public function actionCreate($biblioevent_id = false, $place_id = false, $band_id = false)
	{
		$model = new Landing();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//обработать видео ссылку
			if (!empty($biblioevent_id)) {
				$biblio = Biblioevents::findOne($biblioevent_id);
				$biblio->landing_id = $model->id;
				$biblio->save();
            	return $this->redirect(['/crm/biblioevents']);
			}
			if (!empty($place_id)) {
				$place = Places::findOne($place_id);
				$place->landing_id = $model->id;
				$place->save();
				Yii::$app->getSession()->setFlash('success', 'Лендинг сохранен.');
            	return $this->redirect(['/crm/places/view?id='.$place_id]);
			}
			if (!empty($band_id)) {
				$band = Band::findOne($band_id);
				$band->landing_id = $model->id;
				$band->save();
				Yii::$app->getSession()->setFlash('success', 'Лендинг сохранен.');
            	return $this->redirect(['/crm/band/view?id='.$band_id]);
			}
		}
		return $this->render('create', ['model' => $model]);
	}




	public function actionUpdate($id, $biblioevent_id = false, $place_id = false, $band_id = false )
	{
		$model = $this->findModel($id);
		$land = $this->findModel($id);

		// echo "<pre>";
		// print_r(Yii::$app->request->referrer);
		// echo "</pre>";

		if ($model->load(Yii::$app->request->post())) {

			$image = UploadedFile::getInstance($model, 'image');
			$image1 = UploadedFile::getInstance($model, 'image1');
			$image2 = UploadedFile::getInstance($model, 'image2');
			$image3 = UploadedFile::getInstance($model, 'image3');
			$image4 = UploadedFile::getInstance($model, 'image4');
			$image5 = UploadedFile::getInstance($model, 'image5');
			$image6 = UploadedFile::getInstance($model, 'image6');
			$image7 = UploadedFile::getInstance($model, 'image7');
			$image8 = UploadedFile::getInstance($model, 'image8');
			$image9 = UploadedFile::getInstance($model, 'image9');
			$image10 = UploadedFile::getInstance($model, 'image10');
			$ogimage = UploadedFile::getInstance($model, 'ogimage');

			if ($image) { $model->image = Yii::$app->storage->saveLandingFile($image); } else { $model->image = $land->image;}
			if ($image1) { $model->image1 = Yii::$app->storage->saveLandingFile($image1); } else { $model->image1 = $land->image1;}
			if ($image2) { $model->image2 = Yii::$app->storage->saveLandingFile($image2); } else { $model->image2 = $land->image2;}
			if ($image3) { $model->image3 = Yii::$app->storage->saveLandingFile($image3); } else { $model->image3 = $land->image3;}
			if ($image4) { $model->image4 = Yii::$app->storage->saveLandingFile($image4); } else { $model->image4 = $land->image4;}
			if ($image5) { $model->image5 = Yii::$app->storage->saveLandingFile($image5); } else { $model->image5 = $land->image5;}
			if ($image6) { $model->image6 = Yii::$app->storage->saveLandingFile($image6); } else { $model->image6 = $land->image6;}
			if ($image7) { $model->image7 = Yii::$app->storage->saveLandingFile($image7); } else { $model->image7 = $land->image7;}
			if ($image8) { $model->image8 = Yii::$app->storage->saveLandingFile($image8); } else { $model->image8 = $land->image8;}
			if ($image9) { $model->image9 = Yii::$app->storage->saveLandingFile($image9); } else { $model->image9 = $land->image9;}
			if ($image10) { $model->image10 = Yii::$app->storage->saveLandingFile($image10); } else { $model->image10 = $land->image10;}
			if ($ogimage) { $model->ogimage = Yii::$app->storage->saveUploadedFile($ogimage); } else { $model->ogimage = $land->ogimage;}

			$model->save();

			Yii::$app->getSession()->setFlash('success', 'Лендинг сохранен.');
			if (!empty($band_id)) {
				return $this->redirect(['/crm/band/view?id='.$band_id]);
			}
			if (!empty($biblioevent_id)) {
				return $this->redirect(['/crm/biblioevents/view?id='.$biblioevent_id]);
			}
			if (!empty($place_id)) {
				return $this->redirect(['/crm/places']);
			}
			return $this->redirect(Yii::$app->request->referrer);
		}
		return $this->render('update', ['model' => $model]);
	}

public function actionUpdate2($id, $biblioevent_id = false, $place_id = false, $band_id = false )
	{
		$model = $this->findModel($id);
		$land = $this->findModel($id);

		// echo "<pre>";
		// print_r(Yii::$app->request->referrer);
		// echo "</pre>";

		if ($model->load(Yii::$app->request->post())) {

			$image = UploadedFile::getInstance($model, 'image');
			$image1 = UploadedFile::getInstance($model, 'image1');
			$image2 = UploadedFile::getInstance($model, 'image2');
			$image3 = UploadedFile::getInstance($model, 'image3');
			$image4 = UploadedFile::getInstance($model, 'image4');
			$image5 = UploadedFile::getInstance($model, 'image5');
			$image6 = UploadedFile::getInstance($model, 'image6');
			$image7 = UploadedFile::getInstance($model, 'image7');
			$image8 = UploadedFile::getInstance($model, 'image8');
			$image9 = UploadedFile::getInstance($model, 'image9');
			$image10 = UploadedFile::getInstance($model, 'image10');
			$ogimage = UploadedFile::getInstance($model, 'ogimage');

			if ($image) { $model->image = Yii::$app->storage->saveUploadedFile($image); } else { $model->image = $land->image;}
			if ($image1) { $model->image1 = Yii::$app->storage->saveUploadedFile($image1); } else { $model->image1 = $land->image1;}
			if ($image2) { $model->image2 = Yii::$app->storage->saveUploadedFile($image2); } else { $model->image2 = $land->image2;}
			if ($image3) { $model->image3 = Yii::$app->storage->saveUploadedFile($image3); } else { $model->image3 = $land->image3;}
			if ($image4) { $model->image4 = Yii::$app->storage->saveUploadedFile($image4); } else { $model->image4 = $land->image4;}
			if ($image5) { $model->image5 = Yii::$app->storage->saveUploadedFile($image5); } else { $model->image5 = $land->image5;}
			if ($image6) { $model->image6 = Yii::$app->storage->saveUploadedFile($image6); } else { $model->image6 = $land->image6;}
			if ($image7) { $model->image7 = Yii::$app->storage->saveUploadedFile($image7); } else { $model->image7 = $land->image7;}
			if ($image8) { $model->image8 = Yii::$app->storage->saveUploadedFile($image8); } else { $model->image8 = $land->image8;}
			if ($image9) { $model->image9 = Yii::$app->storage->saveUploadedFile($image9); } else { $model->image9 = $land->image9;}
			if ($image10) { $model->image10 = Yii::$app->storage->saveUploadedFile($image10); } else { $model->image10 = $land->image10;}
			if ($ogimage) { $model->ogimage = Yii::$app->storage->saveUploadedFile($ogimage); } else { $model->ogimage = $land->ogimage;}

			$model->save();

			Yii::$app->getSession()->setFlash('success', 'Лендинг сохранен.');
			if (!empty($band_id)) {
				return $this->redirect(['/crm/band/view?id='.$band_id]);
			}
			if (!empty($biblioevent_id)) {
				return $this->redirect(['/crm/biblioevents/view?id='.$biblioevent_id]);
			}
			if (!empty($place_id)) {
				return $this->redirect(['/crm/places']);
			}
			return $this->redirect(Yii::$app->request->referrer);
		}
		return $this->render('update2', ['model' => $model]);
	}


	// удаляем фото по ajax
	public function actionDelimage($img, $landing_id)
	{
		$model = $this->findModel($landing_id);
		$model->$img = '';
		if ($model->save()) {
			return $model->id;
		}
	}



	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}



	protected function findModel($id)
	{
		if (($model = Landing::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
