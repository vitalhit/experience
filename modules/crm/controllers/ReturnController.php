<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\Contragent;
use app\models\Events;
use app\models\TicketsReturn;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ReturnController implements the CRUD actions for TicketsReturn model.
 */
class ReturnController extends Controller
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

        $returns = TicketsReturn::find()
        ->andWhere('tickets_return.event_id')
        ->all();

        
        // echo "<pre>";
        // print_r($allevents);
        // echo "</pre>";

        return $this->render('index.twig', ['returns' => $returns, 'events' => $events ]);
    }



    // Группируем по событиям, а внутри событий по датам!
    // public function actionIndexOld()
    // {
    //     $biblioevents = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->joinWith('events')->all();

    //     // Финансы сгруппированные по событиям
    //     foreach ($biblioevents as $bib) {
    //         $allevents = null;
    //         foreach ($bib->events as $event) {
    //             $fin = TicketsReturn::find()
    //             ->where(['tickets_return.event_id' => $event->id])
    //             ->andWhere('tickets_return.status > 0')
    //             ->joinWith('personf')
    //             ->joinWith('contragent')
    //             ->orderBy('date desc')
    //             ->all();
    //             if (!empty($fin)) {
    //                 $allevents[] = array('event' => $event, 'finance' => $fin);
    //             }
    //         }
    //         if (!empty($allevents)) {
    //             $allbiblioevents[] = array('biblioevent' => $bib, 'events' => $allevents);
    //         }
    //     }

    //     // Без события
    //     $finance = TicketsReturn::find()
    //     ->where(['tickets_return.company_id' => Companies::getCompanyId()])
    //     ->andWhere('tickets_return.event_id is null')
    //     ->andWhere('tickets_return.status > 0')
    //     ->joinWith('personf')
    //     ->joinWith('contragent')
    //     ->orderBy('date desc')
    //     ->all();


    //     $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();

    //     foreach ($contragents as $contr) {
    //         $min = TicketsReturn::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
    //         $plus = TicketsReturn::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
    //         $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
    //     }

    //     // echo "<pre>";
    //     // print_r($allbiblioevents);
    //     // echo "</pre>";

    //     return $this->render('index.twig', ['finance' => $finance, 'contrs' => $contrs, 'allbiblioevents' => $allbiblioevents]);
    // }




    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionNew()
    {
        
        $model = new TicketsReturn();

        $model->text = Yii::$app->request->get('text');
        $model->creator = Yii::$app->request->get('creator');
        $model->ticket_id = Yii::$app->request->get('ticket_id');
        $model->owner = Yii::$app->request->get('owner');
        $model->status = Yii::$app->request->get('status');
        $model->event_id = Yii::$app->request->get('event_id');
        $model->price = Yii::$app->request->get('price');
        $model->price_for_eventer = 0;
        $model->price_for_person = 0;
        $model->price_for_igoevent = 0;

        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Операция сохранена.');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model, 'event_id' => $event_id ?? null]);
    }


    public function actionCreate()
    {
        $event_id = Yii::$app->request->get('id');
        $model = new TicketsReturn();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Операция сохранена.');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model, 'event_id' => $event_id]);
    }




    public function actionUpdate($id, $edit=False)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        if ($edit){
            return $this->render('create', ['model' => $model, 'event_id' => $model->event_id]);
        }else
        {
            return $this->render('update', ['model' => $model, 'event_id' => $model->event_id]);
        }
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
        if (($model = TicketsReturn::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAkt($id)
    {
        $model = TicketsReturn::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('akt.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
        // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
        // A4 paper format
            'format' => Pdf::FORMAT_A4, 
        // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
        // your html content input
            'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/bootstrap.css',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/akt.css',
         // set mPDF properties on the fly
            'options' => ['title' => 'Акт'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionBill($id)
    {
        $model = TicketsReturn::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('bill.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
        // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
        // A4 paper format
            'format' => Pdf::FORMAT_A4, 
        // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
        // your html content input
            'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/bootstrap.css',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/akt.css',
         // set mPDF properties on the fly
            'options' => ['title' => 'Акт'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionContractmusic($id)
    {
        $model = TicketsReturn::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('contractmusic.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
        // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
        // A4 paper format
            'format' => Pdf::FORMAT_A4, 
        // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
        // your html content input
            'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/bootstrap.css',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/akt.css',
         // set mPDF properties on the fly
            'options' => ['title' => 'Догово распространения музики'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

}
