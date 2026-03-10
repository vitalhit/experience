<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Places;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PlacesController implements the CRUD actions for Places model.
 */
class PlacesController extends Controller
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


    public function actionIndex($closed=False)
    {
        
        if ($closed??Null){
            $places = Places::find()->where(['places.closed' => 1])->orderBy('id asc')->all();
        }else {
            $places = Places::find()->where(['places.closed' => 0])->orderBy('id asc')->all();
        }
        return $this->render('index.twig', ['places' => $places]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Places();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = Places::find()->where(['places.id' => $id])->joinWith('cities')->one();
        $ismap = $model->map;
        $isfoto = $model->foto;
        $isshema = $model->shema;
        $isfoto_street = $model->foto_street;
        $isfoto_stage = $model->foto_stage;
        $isfoto_hall = $model->foto_hall;
        $isfoto_seats = $model->foto_seats;

        if ($model->load(Yii::$app->request->post())) {

            $map = UploadedFile::getInstance($model, 'map');
            if ($map) {
                $model->map = Yii::$app->storage->savePlaceFile($map, $model->id);
            } else {
                $model->map = $ismap;
            }

            $foto = UploadedFile::getInstance($model, 'foto');
            if ($foto) {
                $model->foto = Yii::$app->storage->savePlaceFile($foto, $model->id);
            } else {
                $model->foto = $isfoto;
            }

            $shema = UploadedFile::getInstance($model, 'shema');
            if ($shema) {
                $model->shema = Yii::$app->storage->savePlaceFile($shema, $model->id);
            } else {
                $model->shema = $isshema;
            }

            $foto_stage = UploadedFile::getInstance($model, 'foto_stage');
            if ($foto_stage) {
                $model->foto_stage = Yii::$app->storage->savePlaceFile($foto_stage, $model->id);
            } else {
                $model->foto_stage = $isfoto_stage;
            }

            $foto_street = UploadedFile::getInstance($model, 'foto_street');
            if ($foto_street) {
                $model->foto_street = Yii::$app->storage->savePlaceFile($foto_street, $model->id);
            } else {
                $model->foto_street = $isfoto_street;
            }

            $foto_hall = UploadedFile::getInstance($model, 'foto_hall');
            if ($foto_hall) {
                $model->foto_hall = Yii::$app->storage->savePlaceFile($foto_hall, $model->id);
            } else {
                $model->foto_hall = $isfoto_hall;
            }

            $foto_seats = UploadedFile::getInstance($model, 'foto_seats');
            if ($foto_seats) {
                $model->foto_seats = Yii::$app->storage->savePlaceFile($foto_seats, $model->id);
            } else {
                $model->foto_seats = $isfoto_seats;
            }

            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Место сохранено.');
            return $this->redirect(['/admin/places/']);
        }
        return $this->render('update.twig', ['model' => $model]);
    }


    public function actionEdit($id)
    {
        $model = Places::find()->where(['places.id' => $id])->joinWith('cities')->one();
        $ismap = $model->map;
        $isfoto = $model->foto;
        $isshema = $model->shema;
        $isfoto_street = $model->foto_street;
        $isfoto_stage = $model->foto_stage;

        if ($model->load(Yii::$app->request->post())) {


            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Место сохранено.');
            return $this->redirect(['/admin/places/']);
        }
        return $this->render('edit.twig', ['model' => $model]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Places model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Places the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Places::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
