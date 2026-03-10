<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Companies;
use app\models\Places;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PlacesController implements the CRUD actions for Places model.
 */
class PlacesController extends Controller
{

    public function actionIndex()
    {
        $places = Places::find()->where(['company_id' => Companies::getCompanyId()])->orderBy('favour desc, id asc')->all();
        return $this->render('index.twig', ['places' => $places]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Places::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
