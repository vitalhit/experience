<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Rents;
use app\models\Rooms;
use app\models\Smena;
use app\models\Persons;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RentsController implements the CRUD actions for Rents model.
 */
class RentsController extends Controller
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
     * Lists all Rents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $rents = Rents::find()->orderBy(['date'=>SORT_DESC])->joinWith('rooms')->joinWith('persons')->all();

        return $this->render('index.twig', [
            'rents' => $rents,
        ]);
    }

    /**
     * Displays a single Rents model.
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
     * Creates a new Rents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Проверяем наличие открытой смены у администратора
        $smena = Smena::findSmena();
        if (!$smena) {
            Yii::$app->getSession()->setFlash('danger', 'У вас нет открытой смены! Начните смену!');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model = new Rents();
        $model->smena_id = $smena->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Если аренда сохранилась в базу:
            // Пересчитываем кол-во принесенных денег за все аренды
            $sum_rents = Rents::find()->where(['person_id'=>$model->person_id])->sum('money');
            $user = Persons::findOne($model->person_id);
            $user->sum_rents = $sum_rents;
            $user->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Rents model.
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
     * Finds the Rents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
