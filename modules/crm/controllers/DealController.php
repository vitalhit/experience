<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\EventDeal;
use app\models\Deals;
use app\models\Tickets;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
date_default_timezone_set('Europe/Moscow');

/**
 * EventdealController implements the CRUD actions for Clients model.
 */
class DealController extends Controller
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



    public function actionIndex($deal_id=False)
    { 
        $query = Deals::find()->orderBy(['id' => SORT_ASC]);
        //->joinWith('event')->joinWith('event.biblioevents');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 500]);
        $deals = $query->offset($pages->offset)
        ->limit($pages->limit) 
        ->all();

        //echo "<pre>"; print_r($eventdeals); echo "</pre>";
        
        $for_sum = Deals::find()->where(['id' => $deal_id ])->all();
        $sum_deals = 0;


        foreach ($for_sum as $element)
        {
            $sum_deals += $element['profit'];
        }



        return $this->render('index.twig', ['deals' => $deals,'pages' => $pages, 'deal' => $deal_id, 'sum_deals' => $sum_deals]);
    }



    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Deals::find()->where(['deals.id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такой сделки.');
            return $this->redirect(['/crm/eventdeal']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionCreate($event_id=False,$deal_id=False,$media_id=False,$newsmaker_id=False,$percent=False,$status=False,$date_closed=False,$date_create=False,$info=False)
    {
    // https://igoevent.com/crm/eventdeal/create?event_id=3616&deal_id=4&media_id=2&newsmaker_id=36&profit=20&status=1&date_closed=2021-03-01&date_create=&info=test
        $model = new Deals();

        //$model['event_id'] = $event_id;
        
        $model['media_id'] = $media_id;
        $model['newsmaker_id'] = $newsmaker_id;
        //$model['percent'] = $percent;
        //$model['status'] = $status;
       // $model['date_closed'] = $date_closed;
        $model['date_create'] = $date_create;
        $model['info'] = $info;


        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        
        $eventdeal_profit = 0;
        $eventdeal_tickets = 0;
        /*if ($event_id){
           $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status = 5')->orderBy(['date' => SORT_DESC])->all();

            foreach ($tickets as $ticket) {
                if ($ticket->utm_source == 'kudago.com') {
                $eventdeal_profit = $eventdeal_profit + $ticket->summa;
                $eventdeal_tickets ++; 
                }
            } 
        }*/


        return $this->render('create', [
            'model' => $model,
            'eventdeal_profit' => $eventdeal_profit,
            'eventdeal_tickets' => $eventdeal_tickets,
        ]);
    }
    /**
     * Updates an existing Deals model.
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

        $eventdeal_profit = 0;
        $eventdeal_tickets = 0;
        /*if ($model->event_id){
           $tickets = Tickets::find()->where(['event_id' => $model->event_id, 'tickets.del' => 0])->andWhere('tickets.status = 5')->orderBy(['date' => SORT_DESC])->all();

            foreach ($tickets as $ticket) {
                if ($ticket->utm_source == 'kudago.com') {
                $eventdeal_profit = $eventdeal_profit + $ticket->summa;
                $eventdeal_tickets ++; 
                }
            } 
        }*/

        //echo "<pre>"; print_r($model); echo "</pre>";die;
       

        return $this->render('update', [
            'model' => $model,
            'eventdeal_profit' => $eventdeal_profit,
            'eventdeal_tickets' => $eventdeal_tickets,
        ]);
    }

    /**
     * Deletes an existing Clients model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Clients model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clients the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deals::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
