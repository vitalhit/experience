<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\ProjectsStatus;
use app\models\Users;
use app\models\Places;
use app\models\Companies;
use app\models\CompanyUser;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<?php if (!empty($company)) { echo $form->field($model, 'company_id')->hiddenInput(['value' => $company['id']])->label(false);}?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'info')->textarea(['rows' => 2]) ?>

        <?= $form->field($model, 'result')->textarea(['rows' => 2]) ?>
    </div>

    <?php $projectsstatus = ProjectsStatus::find()->all(); 
    $items = ArrayHelper::map($projectsstatus,'id','name');?>

    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'status_id')->dropDownList($items) ?>
    </div>

    <?php $user_ids = CompanyUser::find()->select('user_id')->where(['company_id' => Companies::getCompanyId()])->asArray()->all();
    $items_user = Users::Map($user_ids);?>

    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'owner_id')->dropDownList($items_user) ?>
    </div>

    <?php $places = Places::find()->all(); 
    $items = ArrayHelper::map($places,'id','name');?>

    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'place_id')->dropDownList($items) ?>
    </div>
    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'client_id')->textInput() ?>
    </div>
    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'link_docs')->textInput() ?>
    </div>
    <div class="col-xs-12 col-md-3">
        <?= $form->field($model, 'type')->dropDownList([
            '1' => 'Открытый',
            '0' => 'Закрытый'
        ]);?>
    </div>


    <div class="col-xs-12 col-md-3">
        <label class="control-label">Окончание</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'deadline',
            'language' => 'ru',
            'readonly' => false,
            'placeholder' => 'Выберите дату',
            'clientOptions' => [
                'format' => 'YYYY-MM-DD LT',
                //'minDate' => '2015-08-10',
                //'maxDate' => '2015-09-10',
        ],
        ]);?>
    </div>
</div>

<?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right']) ?>

<?php ActiveForm::end(); ?>

