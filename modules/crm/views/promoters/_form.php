<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Cities; 
use app\models\Companies;
use app\models\Froms;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-xs-12 col-lg-2">
        <?= $form->field($model, 'mail') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'second_name') ?>
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']) ?>
        <?= $form->field($model, 'birthday')->textInput(['placeholder' => "1980-05-28"]) ?>
    </div>
    <div class="col-xs-12 col-lg-2">
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'groups') ?>
        <?= $form->field($model, 'vishes') ?>
        <?= $form->field($model, 'sex')->dropDownList([ '1' => 'мужской', '0' => 'женский']); ?>
        <?= $form->field($model, 'sendmail')->dropDownList([ '0' => 'Нет', '1' => 'Да']);?>
        <?= $form->field($model, 'status') ?>
    </div>
    <div class="col-xs-12 col-lg-2">
        <?= $form->field($model, 'user_id')->textInput() ?>
        <?= $form->field($model, 'user_id')->textInput() ?>
        <?php if ($model->company_id){ ?>
        <?= $form->field($model, 'company_id')->textInput(); ?>
        <?php 
            }else{ ?>
            <?= $form->field($model, 'company_id')->textInput(['value' => Companies::getCompanyId() ]); ?>   
        <?php
            }
        ?>
        <?php $froms = Froms::find()->all(); 
        $items = ArrayHelper::map($froms,'id','url');
        $params = ['prompt' => 'Выберите...']; ?>
        <?= $form->field($model, 'froms_id')->dropDownList($items,$params);?>
        <?= $form->field($model, 'vk_id')->textInput() ?>
        
    </div>
    <div class="col-xs-12 col-lg-2">

        <?= $form->field($model, 'site')->textInput() ?>
        <?= $form->field($model, 'link_vk')->textInput() ?>
        <?= $form->field($model, 'link_fb')->textInput() ?>
        <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-lg-2">
        <?= $form->field($model, 'utm_source')->textInput() ?>
        <?= $form->field($model, 'utm_medium')->textInput() ?>
        <?= $form->field($model, 'utm_campaign')->textInput() ?>
        <?= $form->field($model, 'utm_content')->textInput() ?>
        <?= $form->field($model, 'utm_term') ?>
    </div>
    <div class="col-xs-12 col-lg-12">
        <?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
    </div>
</div>



<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
