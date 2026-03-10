<?php

namespace app\controllers;

use Yii;
use app\models\Seatings;
use app\models\Places;
use app\models\UploadFile;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeatingsController implements the CRUD actions for Seatings model.
 */
class SeatingsController extends Controller
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

    /**
     * Lists all Seatings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $seatings = Seatings::find()->joinWith('places')->all();
        
        return $this->render('index.twig', ['seatings' => $seatings]);
    }


    /**
     * Displays a single Seatings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Seatings::find()->where(['seatings.id' => $id])->joinWith('seats')->one();

        return $this->render('view.twig', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Seatings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $model = new Seatings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->image = UploadedFile::getInstance($model, 'image');

            if($model->image) {
                $uniqid = uniqid();
                $model->image->saveAs("uploads/{$model->image->baseName}".$uniqid.".{$model->image->extension}");
                $model->image = $model->image->baseName.$uniqid.'.'.$model->image->extension;
                $model->save();
            }
            return $this->redirect(['/seats/create', 'seatingid' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Seatings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->image = UploadedFile::getInstance($model, 'image');

            if($model->image) {
                $uniqid = uniqid();
                $model->image->saveAs("uploads/{$model->image->baseName}".$uniqid.".{$model->image->extension}");
                $model->image = $model->image->baseName.$uniqid.'.'.$model->image->extension;
                $model->save();
            }
            return $this->redirect(['/seats/create', 'seatingid' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Seatings model.
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
     * Finds the Seatings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seatings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seatings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
