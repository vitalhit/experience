<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Markets;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
date_default_timezone_set('Europe/Moscow');

/**
 * ClientsController implements the CRUD actions for Markets model.
 */
class MarketsController extends Controller
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
        $query = Markets::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 1000]);
        $marketss = $query->offset($pages->offset)->orderBy(['id' => SORT_DESC])
        ->limit($pages->limit)
        ->all();

        return $this->render('index.twig', ['all' => $marketss,'pages' => $pages]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Markets::find()->where(['markets.id'=>$id])->one();
        if (empty($markets)) {
            Yii::$app->getSession()->setFlash('danger', 'Нет такого маркета.');
            return $this->redirect(['/crm/markets']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionCreate($event_id=False, $public_vk_id=False, $public_id=False, $info=False,$event_vk_id=False)
    {
        $model = new Markets();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->event_id=$event_id;
        $model->public_vk_id=$public_vk_id;
        $model->public_id=$public_id;
        $model->info=$info;
        $model->event_vk_id=$event_vk_id;


        return $this->render('create', [
            'model' => $model, 'event_id' => $event_id
        ]);
    }

    /**
     * Updates an existing Clients model.
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
     * Deletes an existing Markets model.
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
     * Finds the Markets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Markets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Markets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
