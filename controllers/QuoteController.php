<?php

namespace app\controllers;

use Yii;
use app\models\Quote;
use app\models\Qbox;
use app\models\Qcolor;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuoteController implements the CRUD actions for Quote model.
 */
class QuoteController extends Controller
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

    public $enableCsrfValidation = false;
    
    // Цитата
    public function actionIndex( $status = FALSE)
    {   
        if ($status == 1) {
            $q = Quote::find()->where(['status' => 1])->all();
            return $this->render('list.twig', ['q' => $q , 'status' => $status]);
        } if($status == 2) {
            $q = Quote::find()->where(['status' => 2])->all();
            return $this->render('list.twig', ['q' => $q, 'status' => $status ]);
        } elseif($status == 3) {
            $q = Quote::find()->where(['status' => 3])->all();
            return $this->render('list.twig', ['q' => $q, 'status' => $status ]);
        } elseif($status == 4) {
            $q = Quote::find()->where(['status' => 4])->all();
            return $this->render('list.twig', ['q' => $q, 'status' => $status ]);
        } elseif($status == -1) {
            $q = Quote::find()->where(['status' => -1])->all();
            return $this->render('list.twig', ['q' => $q, 'status' => $status ]);
        } elseif($status == -2) {
            $q = Quote::find()->where(['status' => -2])->all();
            return $this->render('list.twig', ['q' => $q, 'status' => $status ]);
        }

        $q = Quote::find()->where(['status' => 1])->all();
        $q_d = Quote::find()->where(['status' => 2])->all();
        if (file_exists('quotes/')) {
            $photos = \yii\helpers\FileHelper::findFiles('quotes/',['only'=>['*.png','*.jpg'], 'recursive'=>FALSE]);
            sort($photos);
        }  else {
            $photos = null;
        };


        return $this->render('index.twig', ['q' => $q, 'q_d' => $q_d, 'photos' => $photos]);
    }


    // Цитата
    public function actionGenerate()
    {       
        return $this->render('generate.twig', ['photos' => $photos ?? null]);
    }


    // Цитата
    public function actionKudago()
    {       
        return $this->render('kudago.twig', ['photos' => $photos ?? null]);
    }

// Цитата
    public function actionDo()
    {       
        return $this->render('do.twig', ['photos' => $photos ?? null]);
    }



    // Генерируем цитаты
    public function actionDone(){
        // exec('php /home/v/vitalhit/newcrm/public_html/yii hello/index > /dev/null 2>&1 &');
        // Yii::$app->getResponse()->redirect('/site/quote/');
        $quote = Quote::Quote();
        return $this->redirect(['index']);
    }

    // Генерируем цитаты v 1
    public function actionDonekudago(){
        $quote = Quote::Quotekudago();
        return $this->redirect(['index']);
    }

    // Генерируем цитаты v 2
    public function actionDonedone(){
        $quote = Quote::Quotekudago();
        return $this->redirect(['index']);
    }


    // Test консоли
    public function actionTest(){
        $result = exec('php /home/v/vitalhit/igoevent.com/public_html/yii hello/index > 2>&1 &');
        echo $result;
    }






    public function actionCreate()
    {
        $model = new Quote();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Quote model.
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
     * Finds the Quote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
