<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Rooms;
use app\models\Rents;
use app\models\Persons;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RoomsController implements the CRUD actions for Rooms model.
 */
class RoomsController extends Controller
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


    public function actionIndex()
    {
        $rooms = Rooms::find()->joinWith('places')->joinWith('user')->all();

        return $this->render('index.twig', [
            'rooms' => $rooms,
        ]);
    }




    public function actionView($id)
    {
        $room = Rooms::find()->where(['rooms.id' => $id])->one();
        $rents = Rents::find()->where(['room_id' => $id])->orderBy(['rents.id' => SORT_DESC])->all();
        return $this->render('view.twig', ['room' => $room, 'rents' => $rents]);
    }




    public function actionCreate()
    {
        $model = new Rooms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            $img = UploadedFile::getInstance($model, 'image');
            if ($img) {
                $model->image = Yii::$app->storage->saveUploadedFile($img);
            }else {
                $model->image = $image;
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Зал сохранен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update.twig', ['model' => $model]);
    }




    public function actionBron($id)
    {
        $room = Rooms::find()->where(['rooms.id' => $id])->joinWith('rents')->one();
//        ->andWhere(['date' => $date])
        $rents = Rents::find()->where(['room_id' => $id])->orderBy(['rents.id' => SORT_DESC])->all();
        $model = new Rents();
        $person = new Persons();

        // echo "<pre>";
        // print_r($fin_slot);
        // echo "</pre>";

        return $this->render('bron.twig', ['room' => $room, 'rents' => $rents, 'model' => $model, 'person' => $person]);
    }


    public function actionInstruction($id)
    {
        $room = Rooms::find()->where(['rooms.id' => $id])->joinWith('rents')->one();

        return $this->render('instruction.twig', ['room' => $room]);
    }







    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rooms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rooms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rooms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
