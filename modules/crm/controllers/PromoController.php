<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\NewsmakersEvents;
use app\models\Companies;
use app\models\Newsmakers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
date_default_timezone_set('Europe/Moscow');

/**
 * PromoController implements the CRUD actions for NewsmakersEvents model.
 */
class PromoController extends Controller
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



    public function actionIndex($newsmaker_id=False)
    {
        if ($newsmaker_id)
        {
            $query = NewsmakersEvents::find()->where(['newsmaker_id' => 2]);   
        } else{
            $query = NewsmakersEvents::find();    
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        
        

        $allpromo = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        

        return $this->render('index.twig', ['allpromo' => $allpromo,'pages' => $pages]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = NewsmakersEvents::find()->where(['id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого промо.');
            return $this->redirect(['/crm/promo']);
        }
        $newsmaker = Newsmakers::find()->where(['id'=>$model['newsmaker_id']])->one();
        // echo "<pre>";
        // print_r($newsmaker);
        // echo "</pre>";
        return $this->render('view.twig', ['model' => $model, 'newsmaker' => $newsmaker]);
    }



    public function actionAdd($event_id, $newsmaker_id, $user_id, $status, $type_id = false, $gourl )
    {
        $model = new NewsmakersEvents();
        $model['event_id'] = $event_id;
        $model['newsmaker_id'] = $newsmaker_id;
        $model['user_id'] = $user_id;
        $model['status'] = $status;
        $model['type_id'] = $type_id;

        if ( $model->save()) {
         

           return $this->redirect(['update', 'id' => $model->id, 'gourl' => $gourl]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new NewsmakersEvents();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing NewsmakersEvents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $gourl=False) 
    {
        $model = $this->findModel($id); //NewsmakersEvents model

         // echo "<pre>";
        // print_r( $model);
        // echo "</pre>";

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update',  [
            'model' => $model, 'gourl' => $gourl
        ]);
    }

    /**
     * Deletes an existing NewsmakersEvents model.
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
     * Finds the NewsmakersEvents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NewsmakersEvents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NewsmakersEvents::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
