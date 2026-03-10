<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Cities;
use app\models\Companies;
use app\models\Contragent;
use app\models\Events;
use app\models\EventFinance;
use app\models\Places;
use app\models\Vitalhit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContragentController implements the CRUD actions for Contragent model.
 */
class ContragentController extends Controller
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



    public function actionIndex($event_id=False)
    {
        $company = Companies::getCompany();
        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->asArray()->all();
        return $this->render('index.twig', ['contragents' => $contragents, 'company' => $company, 'event_id' => $event_id]);

    }

    // выпадающий  $contragents = Contragent::find()->where(['company_id' => Companies::getIds()])->asArray()->all();




    public function actionView($id)
    {
        $docs_from = EventFinance::find()->where(['event_finance.from_contragent' => $id])->joinWith('contragent')->all();
        //Vitalhit::pre($docs_from);
        $docs_to = EventFinance::find()->where(['event_finance.to_contragent' => $id])->all();

        //echo "<pre>"; print_r($docs); echo "</pre>";
        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->asArray()->all();

        return $this->render('view', [
            'model' => $this->findModel($id), 'docsf' => $docs_from, 'docst' => $docs_to, 'contragents' => $contragents
        ]);


    }




    public function actionCreate()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create.twig', ['model' => $model, 'company' => $company]);
    }

    public function actionAddip()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create-add-ip.twig', ['model' => $model, 'company' => $company]);
    }

    public function actionAddooo()
    {
        $model = new Contragent();
        $company = Companies::getCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create-add-ooo.twig', ['model' => $model, 'company' => $company]);
    }

    /**
     * Updates an existing Contragent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $company = Companies::getCompany();


        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model, 'company' => $company]);
    }

    /**
     * Deletes an existing Contragent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('danger', 'Удаление отключено..'); 
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);

    }

    /**
     * Finds the Contragent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contragent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contragent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
