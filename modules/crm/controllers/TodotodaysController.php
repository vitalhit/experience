<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Todotodays;
use app\models\TodotodayStep;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Users;
date_default_timezone_set('Europe/Moscow');

/**
 * PromoController implements the CRUD actions for Todotodays model.
 */
class TodotodaysController extends Controller
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
        $query = Todotodays::find()->orderBy(['hour'=>SORT_ASC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        $todotodays = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        $steps = TodotodayStep::find()->all();

        //foreach ($tdtd as $todotodays) {} // не придумал как делать
        
        // echo "<pre>";
        // print_r($steps);
        // echo "</pre>";

        return $this->render('index.twig', ['todotodays' => $todotodays,'pages' => $pages, 'steps' => $steps]);
    }

        public function actionAll()
    {
        $query = Todotodays::find()->orderBy(['id'=>SORT_ASC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        $todotodays = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        $steps = TodotodayStep::find()->all();

        //foreach ($tdtd as $todotodays) {} // не придумал как делать
        
        // echo "<pre>";
        // print_r($steps);
        // echo "</pre>";

        return $this->render('all.twig', ['todotodays' => $todotodays,'pages' => $pages, 'steps' => $steps]);
    }


    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Todotodays::find()->where(['todotodays.id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/crm/todotodays']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionAdd($id, $status = 1)
    {
        $model = new Todotodaystep();
        $model['todotoday_id'] = $id;
        $model['status'] = $status;
        $user = Users::findOne(Yii::$app->user->id);
        $model['user_id'] = $user['id'];
        

        if ( $model->save()) {
           return $this->redirect(['update', 'id' => $model->todotoday_id]);
        }

        return $this->render('update', ['model' => $model]);
    } 

    public function actionCreate()
    {
        $model = new Todotodays();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Todotodays model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($_POST['goto'] == "Сохранить и к списку"){
                Yii::$app->getSession()->setFlash('success', 'Информация обновлена.'.$_POST['goto']);
                    return $this->redirect(['index']);
                }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Todotodays model.
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
     * Finds the Todotodays model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Todotodays the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Todotodays::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
