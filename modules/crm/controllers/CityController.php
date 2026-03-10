<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Cities;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
date_default_timezone_set('Europe/Moscow');

/**
 * CityController implements the CRUD actions for Cities model.
 */
class CityController extends Controller
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
        $query = Cities::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 500]);
        $cities = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        
        // echo "<pre>";
        // print_r($cities);
        // echo "</pre>";

        return $this->render('index.twig', ['cities' => $cities,'pages' => $pages]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Cities::find()->where(['cities.id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/crm/cities']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }


/*
    public function actionAdd($event_id, $newsmaker_id, $user_id, $status)
    {
        $model = new cities();
        $model['event_id'] = $event_id;
        $model['newsmaker_id'] = $newsmaker_id;
        $model['user_id'] = $user_id;
        $model['status'] = $status;

        if ( $model->save()) {
         

           return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    } */

    public function actionCreate()
    {
        $model = new cities();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing cities model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing cities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // не использую
    {
       // $this->findModel($id)->delete();

      //  return $this->redirect(['index']);
    }

    /**
     * Finds the cities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return cities the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cities::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
