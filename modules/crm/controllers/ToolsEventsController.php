<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\ToolsEvents;
use app\models\Companies;
use app\models\Tools;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
date_default_timezone_set('Europe/Moscow');

/**
 * PromoController implements the CRUD actions for ToolsEvents model.
 */
class ToolsEventsController extends Controller
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
        $query = ToolsEvents::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        $alltools = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        return $this->render('index.twig', ['alltools' => $alltools,'pages' => $pages]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = ToolsEvents::find()->where(['id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого ToolsEvents.');
            return $this->redirect(['/crm/tools-events']);
        }
        $toolsevents = Tools::find()->where(['id'=>$model['tool_id']])->one();
        // echo "<pre>";
        // print_r($newsmaker);
        // echo "</pre>";
        return $this->render('view.twig', ['model' => $model, 'toolsevents' => $toolsevents]);
    }



    public function actionAdd($event_id, $tool_id, $user_id, $status)
    {
        $model = new ToolsEvents();
        $model['event_id'] = $event_id;
        $model['tool_id'] = $tool_id;
        $model['user_id'] = $user_id;
        $model['status'] = $status;

        if ( $model->save()) {
         

           return $this->redirect(['update', 'id' => $model->event_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new ToolsEvents();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing ToolsEvents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) 
    {
        $model = $this->findModel($id); //ToolsEvents model

         // echo "<pre>";
        // print_r( $model);
        // echo "</pre>";

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ToolsEvents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ToolsEvents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToolsEvents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToolsEvents::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
