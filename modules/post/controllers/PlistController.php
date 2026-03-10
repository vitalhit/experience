<?php

namespace app\modules\post\controllers;

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
use app\models\Ptext;
use app\models\Plist;
use app\models\PlistPost;
use app\models\Posts;
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
 * PromoController implements the CRUD actions for Plist model.
 */
class PlistController extends Controller
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



    public function actionIndex($plist = false, $public_vk_id=false, $publish= false)
    {
        if ( $publish ) {
          $query_stop = $publish >= 0 ? 'stop > 0' : 'stop < 0';
        }else{ $query_stop = '';
        }

        if ($public_vk_id){
          $query = Plist::find()->orderBy(['id' => SORT_ASC])->where(['public_vk_id'=>$public_vk_id])
          ->andwhere($query_stop );
        }else{
          $query = Plist::find()->orderBy(['id' => SORT_ASC]);  
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 500]);
        $all = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        
        // echo "<pre>";
        // print_r($contests);
        // echo "</pre>";

        $bands= Band::find()->all();

        return $this->render('index.twig', ['all' => $all,'pages' => $pages, 'bands' => $bands, 'plist' => $plist ]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Plist::find()->where(['id'=>$id])->one();
        $posts = Posts::find()->all();
        $myposts = PlistPost::find()->where(['plist_id' => $model->id])->joinWith('posts')->all();

        // echo "<pre>";
        // print_r($myposts);
        // echo "</pre>";
       


        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого такого текста.');
            return $this->redirect(['/post/contests']);
        }
        return $this->render('view.twig', ['model' => $model, 'myposts' => $myposts]);
    }




    public function actionCreate()
    {
        $model = new Plist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAddpost($plist_id=false)
    {
        $model = new PlistPost();
        $model['plist_id'] = $plist_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $plist_id]);
        }

        return $this->render('addpost', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Plist model.
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
     * Deletes an existing Plist model.
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
     * Finds the Plist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
