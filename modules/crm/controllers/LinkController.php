<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Links;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Users;
date_default_timezone_set('Europe/Moscow');

/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
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
        $query = Links::find()->orderBy(['id'=>SORT_ASC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 5000]);
        $all = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

       // $steps = Linkstep::find()->all();
        
        // echo "<pre>";
        // print_r($steps);
        // echo "</pre>";

        return $this->render('index.twig', ['Link' => $Link ?? null, 'pages' => $pages, 'all' => $all]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Links::find()->where(['id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/crm/post']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionAdd($id)
    {
        $model = new Linkstep();
        $model['todo_id'] = $id;
        $model['status'] = 1;
        $user = Users::findOne(Yii::$app->user->id);
        $model['user_id'] = $user['id'];
        

        if ( $model->save()) {
           return $this->redirect(['update', 'id' => $model->todo_id]);
        }

        return $this->render('update', ['model' => $model]);
    } 

    public function actionCreate()
    {
        $model = new Links();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Links model.
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
     * Deletes an existing Links model.
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
     * Finds the Links model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Links the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Links::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
