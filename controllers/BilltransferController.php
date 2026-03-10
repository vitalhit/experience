<?php

namespace app\controllers;

use Yii;
use app\models\Billtransfer;
use app\models\Bills;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BilltransferController implements the CRUD actions for Billtransfer model.
 */
class BilltransferController extends Controller
{
    /**
     * @inheritdoc
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

    /**
     * Lists all Bills models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dohod = Billtransfer::find()->joinWith('billTo')->where(['type'=> 1])->orderBy(['date'=>SORT_DESC])->all();
        $rashod = Billtransfer::find()->joinWith('billFrom')->where(['type'=> 2])->orderBy(['date'=>SORT_DESC])->all();
        $perevod = Billtransfer::find()->joinWith('billTo AS billTo')->joinWith('billFrom AS billFrom')->where(['type'=> 3])->orderBy(['date'=>SORT_DESC])->all();

        return $this->render('index.twig', ['dohod' => $dohod, 'rashod' => $rashod, 'perevod' => $perevod]);

    }



    public $enableCsrfValidation = false;

    /**
     * Lists all times models.
     * @return mixed
     */
    public function actionDohod()
    {

        $billtransfer = new Billtransfer();
        $billtransfer->type = $_POST['type'];
        $billtransfer->date = date("Y-m-d H:i:s");
        $billtransfer->name = $_POST['name'];
        $billtransfer->bill_id_to = $_POST['bill_id_to'];
        $billtransfer->summa = $_POST['summa'];
        $billtransfer->save();

        $bill = Bills::findOne($_POST['bill_id_to']);
        $bill->summa = $bill->summa + $billtransfer->summa;
        $bill->save();

        return $this->redirect('/bills/index');

    }


    /**
     * Lists all times models.
     * @return mixed
     */
    public function actionRashod()
    {

        $billtransfer = new Billtransfer();
        $billtransfer->type = $_POST['type'];
        $billtransfer->date = date("Y-m-d H:i:s");
        $billtransfer->name = $_POST['name'];
        $billtransfer->bill_id_from = $_POST['bill_id_from'];
        $billtransfer->summa = $_POST['summa'];
        $billtransfer->save();

        $bill = Bills::findOne($_POST['bill_id_from']);
        $bill->summa = $bill->summa - $billtransfer->summa;
        $bill->save();

        return $this->redirect('/bills/index');

    }



    /**
     * Lists all times models.
     * @return mixed
     */
    public function actionPerevod()
    {

        $billtransfer = new Billtransfer();
        $billtransfer->type = $_POST['type'];
        $billtransfer->date = date("Y-m-d H:i:s");
        $billtransfer->bill_id_from = $_POST['bill_id_from'];
        $billtransfer->bill_id_to = $_POST['bill_id_to'];
        $billtransfer->summa = $_POST['summa'];
        $billtransfer->save();

        $bill = Bills::findOne($_POST['bill_id_from']);
        $bill->summa = $bill->summa - $billtransfer->summa;
        $bill->save();

        $bill = Bills::findOne($_POST['bill_id_to']);
        $bill->summa = $bill->summa + $billtransfer->summa;
        $bill->save();

        return $this->redirect('/bills/index');

    }
    /**
     * Displays a single Billtransfer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Billtransfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Billtransfer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Billtransfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Billtransfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Billtransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Billtransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Billtransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
