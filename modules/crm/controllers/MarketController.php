<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\Contragent;
use app\models\Events;
use app\models\Market;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ReturnController implements the CRUD actions for TicketsReturn model.
 */
class MarketController extends Controller
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
       
        $events = Events::find()
        ->where(['events.id' => Biblioevents::EventsIdsAll()])
        ->joinWith('biblioevents')
        ->orderBy('date desc')
        ->all();


        $markets = Market::find()->all();

        // echo "<pre>";
        // print_r($allevents);
        // echo "</pre>";

        return $this->render('index.twig', ['markets' => $markets, 'events' => $events ]);
    }






    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }




    public function actionCreate()
    {
        $market_id = Yii::$app->request->get('id');
        $model = new Market();
        $model->load(Yii::$app->request->post());
     


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Операция сохранена.');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model]);


    }




    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }



        return $this->render('update', ['model' => $model]);
    }




    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete = 0;
        $model->save(); 

        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }




    protected function findModel($id)
    {
        if (($model = Market::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    

    

}
