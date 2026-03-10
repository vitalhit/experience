<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Vitalhit;
use app\models\Biblioevents;
use app\models\Cities;
use app\models\Companies;
use app\models\Contragent;
use app\models\Events;
use app\models\EventFinance;
use app\models\EventFinanceOrder;
use app\models\Places;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ContractsController implements the CRUD actions for EventFinance model.
 */
class ContractsController extends Controller
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

    // подготовить документы
    public function actionTodo()
    {
        $events = Events::find()->where(['events.id' => Biblioevents::EventsIdsAll()])->joinWith('biblioevents')->orderBy('date desc')->all();

        // Финансы сгруппированные по событиям
        $allevents = null;
        foreach ($events as $event) {
            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event->id])
            ->andWhere('event_finance.state = 1')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date desc')
            ->all();
            if (!empty($fin)) {
                $allevents[] = array('event' => $event, 'finance' => $fin);
            }
        }

        // Без события
        $finance = EventFinance::find()
        ->where(['event_finance.company_id' => Companies::getCompanyId()])
        ->andWhere('event_finance.event_id is null')
        ->andWhere('event_finance.state = 1')
        ->joinWith('personf')
        ->joinWith('contragent')
        ->orderBy('date desc')
        ->all();


        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();
        
        foreach ($contragents as $contr) {
            $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
        }

        return $this->render('todo.twig', ['finance' => $finance, 'contrs' => $contrs, 'allevents' => $allevents]);
    }

    // подготовить документы
    public function actionPrinted()
    {
        $events = Events::find()->where(['events.id' => Biblioevents::EventsIdsAll()])->joinWith('biblioevents')->orderBy('date desc')->all();

        // Финансы сгруппированные по событиям
        $allevents = null;
        foreach ($events as $event) {
            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event->id])
            ->andWhere('event_finance.state = 2')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date desc')
            ->all();
            if (!empty($fin)) {
                $allevents[] = array('event' => $event, 'finance' => $fin);
            }
        }

        // Без события
        $finance = EventFinance::find()
        ->where(['event_finance.company_id' => Companies::getCompanyId()])
        ->andWhere('event_finance.event_id is null')
        ->andWhere('event_finance.state = 2')
        ->joinWith('personf')
        ->joinWith('contragent')
        ->orderBy('date desc')
        ->all();


        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();
        
        foreach ($contragents as $contr) {
            $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
        }

        return $this->render('todo.twig', ['finance' => $finance, 'contrs' => $contrs, 'allevents' => $allevents]);
    }


        public function actionIndex( $show = False , $event_id = False)
    {
        $events = Events::find()->where(['events.id' => Biblioevents::EventsIdsAll()])->joinWith('biblioevents')->orderBy('date desc')->all();

        // Финансы сгруппированные по событиям
        $alleventfinances = null;
        foreach ($events as $event) {
            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event->id])
            ->andWhere('event_finance.status > 0')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date_create desc')
            ->all();
            if (!empty($fin)) {
                $alleventfinances[] = array('event' => $event, 'finance' => $fin);
            }
        }

        // Без события
        $finance = EventFinance::find()
        ->where(['event_finance.company_id' => Companies::getCompanyId()])
        ->andWhere('event_finance.event_id is null')
        ->andWhere('event_finance.status > 0')
        ->joinWith('personf')
        ->joinWith('contragent')
        ->orderBy('id desc')
        ->all();


        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();
        
        foreach ($contragents as $contr) {
            $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
        }

        // echo "<pre>";
        // print_r($allevents);
        // echo "</pre>";
        if ($show) return $this->render('index.twig', ['finance' => $finance, 'contrs' => $contrs, 'alleventfinances' => $alleventfinances]);
        $company = Companies::getCompany();

        return $this->render('index.twig', ['finance' => $finance, 'contrs' => $contrs, 'alleventfinances' => $alleventfinances, 'company' => $company]);
    }

        public function actionNoevent( $show = False , $event_id = False)
    {
        $events = Events::find()->where(['events.id' => Biblioevents::EventsIdsAll()])->joinWith('biblioevents')->orderBy('date desc')->all();

        // Финансы сгруппированные по событиям
        $alleventfinances = null;
        foreach ($events as $event) {
            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event->id])
            ->andWhere('event_finance.status > 0')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date_create desc')
            ->all();
            if (!empty($fin)) {
                $alleventfinances[] = array('event' => $event, 'finance' => $fin);
            }
        }

        // Без события
        $finance = EventFinance::find()
        ->where(['event_finance.company_id' => Companies::getCompanyId()])
        ->andWhere('event_finance.event_id is null')
        ->andWhere('event_finance.status > 0')
        ->joinWith('personf')
        ->joinWith('contragent')
        ->orderBy('id desc')
        ->all();


        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();
        
        foreach ($contragents as $contr) {
            $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
        }

        // echo "<pre>";
        // print_r($allevents);
        // echo "</pre>";
        if ($show) return $this->render('index.twig', ['finance' => $finance, 'contrs' => $contrs, 'alleventfinances' => $alleventfinances]);
        $company = Companies::getCompany();

        return $this->render('noevent.twig', ['finance' => $finance, 'contrs' => $contrs, 'alleventfinances' => $alleventfinances, 'company' => $company]);
    }



        public function actionBalance()
    {
        $events = Events::find()->where(['events.id' => Biblioevents::EventsIdsAll()])->joinWith('biblioevents')->orderBy('date desc')->all();

        // Финансы сгруппированные по событиям
        $allevents = null;
        foreach ($events as $event) {
            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event->id])
            ->andWhere('event_finance.status > 0')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date desc')
            ->all();
            if (!empty($fin)) {
                $allevents[] = array('event' => $event, 'finance' => $fin);
            }
        }

        // Без события
        $finance = EventFinance::find()
        ->where(['event_finance.company_id' => Companies::getCompanyId()])
        ->andWhere('event_finance.event_id is null')
        ->andWhere('event_finance.status > 0')
        ->joinWith('personf')
        ->joinWith('contragent')
        ->orderBy('id desc')
        ->all();


        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();
        
        foreach ($contragents as $contr) {
            $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
            $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
        }

        // echo "<pre>";
        // print_r($allevents);
        // echo "</pre>";

        return $this->render('balance.twig', ['finance' => $finance, 'contrs' => $contrs, 'allevents' => $allevents]);
    }


    // Группируем по событиям, а внутри событий по датам!
    // public function actionIndexOld()
    // {
    //     $biblioevents = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->joinWith('events')->all();

    //     // Финансы сгруппированные по событиям
    //     foreach ($biblioevents as $bib) {
    //         $allevents = null;
    //         foreach ($bib->events as $event) {
    //             $fin = EventFinance::find()
    //             ->where(['event_finance.event_id' => $event->id])
    //             ->andWhere('event_finance.status > 0')
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
    //     $finance = EventFinance::find()
    //     ->where(['event_finance.company_id' => Companies::getCompanyId()])
    //     ->andWhere('event_finance.event_id is null')
    //     ->andWhere('event_finance.status > 0')
    //     ->joinWith('personf')
    //     ->joinWith('contragent')
    //     ->orderBy('date desc')
    //     ->all();


    //     $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->all();

    //     foreach ($contragents as $contr) {
    //         $min = EventFinance::find()->where(['from_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
    //         $plus = EventFinance::find()->where(['to_contragent' => $contr->id])->andWhere(['state' => 3])->andWhere('deleted is null')->sum('summa');
    //         $contrs[] = array('contragent' => $contr, 'summa' => $plus - $min );
    //     }

    //     // echo "<pre>";
    //     // print_r($allbiblioevents);
    //     // echo "</pre>";

    //     return $this->render('index.twig', ['finance' => $finance, 'contrs' => $contrs, 'allbiblioevents' => $allbiblioevents]);
    // }




    public function actionView($id)
    {

        $fina = EventFinance::find()->where(['event_finance.id' => $id])->joinWith('orders')->one();
        //vitalhit::pre($fina); // Пример joinWith часть 1 — 211023

        return $this->render('view', [
            'model' => $fina,
        ]);

    }




    public function actionCreate($event_id=False,$tour_id=False,$from_contragent=False,$to_contragent=False,$name=False,$price=False,$state=False, $status=False, $biblioevent_id = False)
    {
        //$event_id = Yii::$app->request->get('id');
        $model = new EventFinance();
        $model['event_id'] = $event_id;
        $model['from_contragent'] = $from_contragent;
        $model['to_contragent'] = $to_contragent;
        $model['name'] = $name;
        $model['summa'] = $price;
        if ($date_payment = 'now') {
            $model['date_payment'] = date('Y-m-d G:i');
            }
        $model['state']=$state;
        $model['status']=$status;
        if ($to_contragent){  $model['to_contragent'] = $to_contragent; }
        //else {$model['to_contragent'] = 237;}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Операция сохранена.');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model, 'event_id' => $event_id,'from_contragent'=> $from_contragent, 'to_contragent'=>$to_contragent, 'biblioevent_id' => $biblioevent_id]);
    }




    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //Vitalhit::pre($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model, 'event_id' => $model->event_id]);
    }




    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save(); 

        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }




    protected function findModel($id)
    {
        if (($model = EventFinance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAkt($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa'].'.'.$model['summa_k']);
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
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa'].'.'.$model['summa_k']);
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

    public function actionRao2($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        


        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('rao-otkaznoe-2.twig', ['model' => $model, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

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

    public function actionRaoreport($id)
    {
        $model = EventFinance::findOne($id);

        $event = Events::findOne($model->event_id);
        $place = Places::find()->where(['id' => $event->place_id])->one();

        //Vitalhit::pre($place);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        


        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('doc/rao-report.twig', ['model' => $model, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'place' => $place]);

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

    public function actionFoursiz($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $event = Events::findOne($model->event_id);

        $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
        $place = Places::find()->where(['id' => $event->place_id])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );

        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

        $content = $this->renderPartial('/contracts/doc/2022foursiz-03.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);

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

    public function actionMakecorp($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $event = Events::findOne($model->event_id);

        $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
        $place = Places::find()->where(['id' => $event->place_id])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

        $content = $this->renderPartial('makecorp.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);

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
            'options' => ['title' => 'договор: организация корпоратива'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionContractloan($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('contract-loan.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

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
        $model = EventFinance::findOne($id);

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

    public function actionContractmakeevent($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );



        // echo "<pre>";
        // print_r($money);              
        // echo "</pre>";

        $content = $this->renderPartial('contractmakeevent.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2]);

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
            'options' => ['title' => 'Договор на организацию события'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionRenthelp($id)
    {
        $model = EventFinance::findOne($id);

        $contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
        $contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();
        
        $event = Events::findOne($model->event_id);

        $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
        $place = Places::find()->where(['id' => $event->place_id])->one();
        
        $summa_p = Companies::Propis($model['summa']);
        $money = array(
            'summa_p' => $summa_p
        );

        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

        $content = $this->renderPartial('/contracts/doc/2022rent-help.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);

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

}
