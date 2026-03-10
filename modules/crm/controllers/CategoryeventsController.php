<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Companies;
use app\models\Categoryevents;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CategoryeventsController implements the CRUD actions for Categoryevents model.
 */
class CategoryeventsController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    // АДМИН - перенести в админку

    // public function actionIndex()
    // {
    //     $comp_ids = Companies::getIds();
    //     $categoryevents = Categoryevents::find()->where(['company_id' => $comp_ids])->all();
    //     return $this->render('index.twig', ['categoryevents' => $categoryevents]);
    // }


    // public function actionCreateAdmin()
    // {
    //     $comp_ids = Companies::getIds();
    //     $companies = Companies::find()->where(['id' => $comp_ids])->asArray()->all();

    //     $model = new Categoryevents();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['/biblioevents/create']);
    //     } else {
    //         return $this->render('create', ['model' => $model, 'companies' => $companies]);
    //     }
    // }







    // МЕНЕДЖЕР

    public function actionMy()
    {
        $categoryevents = Categoryevents::find()->where(['company_id' => Companies::getCompanyId()])->all();
        return $this->render('index.twig', ['categoryevents' => $categoryevents]);
    }
    


    public function actionCreate()
    {
        $company = Companies::getCompany();
        $model = new Categoryevents();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Категория создана.');
            return $this->redirect(['/crm/biblioevents/create']);
        } else {
            return $this->render('create.twig', ['model' => $model, 'company' => $company]);
        }
    }


    public function actionUpdate($id)
    {
        $model = Categoryevents::find()->where(['id' => $id, 'company_id' => Companies::getCompanyId()])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'Нет доступа к этой категории.');
            return $this->redirect(['my']);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['my']);
        } else {
            foreach ($model->getErrors() as $key => $value) {
             echo $key.': '.$value[0];
            }
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Categoryevents model.
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
     * Finds the Categoryevents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categoryevents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categoryevents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
