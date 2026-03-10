<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\Contragent;
use app\models\Events;
use app\models\TaskReport;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ReportController implements the CRUD actions for TicketsReturn model.
 */
class ReportController extends Controller
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
       
        $all = TaskReport::find()->all();        
        return $this->render('index.twig', ['all' => $all ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate($event_id=False) 
    {
        
        $model = new TaskReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Операция сохранена.');
            return $this->redirect(['index']);
        }
        $model->event_id=$event_id;
        $model->user_id=Yii::$app->user->id;
        return $this->render('create', ['model' => $model, 'user_id' =>$user_id ?? null]);
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model, 'event_id' => $model->event_id]);
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
        if (($model = TaskReport::findOne($id)) !== null) {
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
