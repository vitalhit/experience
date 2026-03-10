<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Band;
use app\models\BandPerson;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\Categoryevents;
use app\models\Companies;
use app\models\CompanyUser;
use app\models\Persons;
use app\models\PictureForm;
use app\models\Pquote;
use app\models\Section;
use app\models\Quote;
use app\models\Users;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;



/**
 * PromoController implements the CRUD actions for Pquote model.
 */
class PquoteController extends Controller
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
        $query = Pquote::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        $all = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        
        // echo "<pre>";
        // print_r($contests);
        // echo "</pre>";

        return $this->render('index.twig', ['all' => $all,'pages' => $pages]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Pquote::find()->where(['quote.id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/crm/contests']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }




    public function actionCreate()
    {
        $model = new Pquote();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Pquote model.
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
     * Deletes an existing Pquote model.
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
     * Finds the Pquote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pquote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pquote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
