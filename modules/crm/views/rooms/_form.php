<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Companies;
use app\models\Places;
use app\models\Users;
use app\models\CompanyUser;


/* @var $this yii\web\View */
/* @var $model app\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-lg-4">
        <?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive">';}
        echo $form->field($model, 'image')->fileInput();

        $places = Places::find()->all();
        $items_pl = ArrayHelper::map($places,'id','name');?>
        <?= $form->field($model, 'place_id')->dropDownList($items_pl) ?>

        <?php $comp_ids = Companies::getIds();
        $companies = Companies::find()->where(['id' => $comp_ids])->all();
        $items = ArrayHelper::map($companies,'id','brand');?>
        <?= $form->field($model, 'company_id')->dropDownList($items) ?>

        <?php $user_ids = CompanyUser::find()->select('user_id')->where(['company_id' => 1])->asArray()->all();
        $items_user = Users::Map($user_ids); ?>

        <?= $form->field($model, 'user_id')->dropDownList($items_user) ?>
    </div>


    <div class="col-xs-12 col-lg-4">
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'time_start')->textInput() ?>
        <?= $form->field($model, 'time_end')->textInput() ?>
        <?= $form->field($model, 'time_step')->textInput() ?>
        <?= $form->field($model, 'money')->textInput() ?>
    </div>

    <div class="col-xs-12 col-lg-4">
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'status')->textInput() ?>
        <?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $('.steps .room').addClass('active');
</script>