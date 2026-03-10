<?php

namespace app\modules\post\controllers;

use Yii;
use app\models\Posts;
use app\models\PostImg;
use app\models\PostPlace;
use app\models\PostBiblioevent;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Users;
date_default_timezone_set('Europe/Moscow');

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
        
        $query = Posts::find()->orderBy(['id'=>SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 500]);
        $all = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

       // $steps = Poststep::find()->all();
        
        // echo "<pre>";
        // print_r($steps);
        // echo "</pre>";

        return $this->render('index.twig', ['Post' => $Post ?? null, 'pages' => $pages, 'all' => $all]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Posts::find()->where(['id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/post/post']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionAdd($id)
    {
        $model = new Poststep();
        $model['todo_id'] = $id;
        $model['status'] = 1;
        $user = Users::findOne(Yii::$app->user->id);
        $model['user_id'] = $user['id'];
        

        if ( $model->save()) {
           return $this->redirect(['update', 'id' => $model->todo_id]);
        }

        return $this->render('update', ['model' => $model]);
    } 

    public function actionCreate($item=False,$item_id=False,$usecase=False,$usecase_id=False,$title=False,$info=False)
    {
        $model = new Posts();
        if ($item){$model['item'] = $item;}
        if ($title){$model['title'] = $title;}
        if ($item_id){$model['item_id'] = $item_id;}
        if ($usecase){$model['usecase'] = $usecase;}
        if ($usecase_id){$model['usecase_id'] = $usecase_id;}
        if ($info){$model['html'] = $info; $model['text'] = $info; }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionJointoplace($place_id=False,$post_id=False)
    {
        $model = new PostPlace();
        if ($post_id){$model['post_id'] = $post_id;}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        }

        return $this->render('jointoplace', [
            'model' => $model,
        ]);
    }

    public function actionJointobiblioevent($biblioevent_id=False,$post_id=False)
    {
        $model = new PostBiblioevent();
        if ($post_id){$model['post_id'] = $post_id;}
        if ($biblioevent_id){$model['biblioevent_id'] = $biblioevent_id;}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        }

        return $this->render('jointobiblioevent', [
            'model' => $model,
        ]);
    }

     public function actionJointoimg($img_id=False,$post_id=False)
    {
        $model = new PostImg();
        if ($post_id){$model['post_id'] = $post_id;}
        if ($img_id){$model['img_id'] = $img_id;}
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->post_id]);
        }

        return $this->render('jointoimg', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Posts model.
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
     * Deletes an existing Posts model.
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
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}