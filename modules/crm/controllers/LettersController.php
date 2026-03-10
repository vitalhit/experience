<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Letters;
use app\models\Biblioevents;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LettersController implements the CRUD actions for Letters model.
 */
class LettersController extends Controller
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

    /**
     * Lists all Letters models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Letters::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Letters model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    public function actionCreate($id = false, $biblioeventid = false)
    {
        $comp_ids = Companies::getIds();

        if (!empty($biblioeventid)) {
            $biblioevent = Biblioevents::find()->where(['biblioevents.id' => $biblioeventid])->joinWith('places')->andWhere(['biblioevents.company_id' => $comp_ids])->one();
            $letters = Letters::find()->where(['biblioevent_id' => $biblioevent->id])->all();
        } else {
            $biblioevent = NULL;
            $letters = NULL;
        }

        if (!empty($id)) {
            $model = $this->findModel($id);
        } else {
            $model = new Letters();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($_POST['new'] == 'Сохранить и создать еще письмо'){
                Yii::$app->getSession()->setFlash('success', 'Письмо создано.');
                return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
            } else {
                Yii::$app->getSession()->setFlash('success', 'Письмо создано.');
                return $this->redirect(['/biblioevents/instruction?id='.$biblioevent->id]);
            }
        } else {
            return $this->render('create.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'letters' => $letters]);
        }
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Letters model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $biblioeventid)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/letters/create?biblioeventid='.$biblioeventid]);
    }

    /**
     * Finds the Letters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Letters the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Letters::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
