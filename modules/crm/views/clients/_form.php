<?php

use yii\helpers\Html;
use app\models\Companies;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Cities;
use app\models\Froms;

/* @var $this yii\web\View */
/* @var $model app\models\Clients */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'mail') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'second_name') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']) ?>
        <?= $form->field($model, 'birthday')->textInput(['placeholder' => "1980-05-28"]) ?>
         <?= $form->field($model, 'client_company_id')->textInput() ?>
    </div>
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'mail_gmail') ?>
        <?= $form->field($model, 'mail_yandex') ?>
        
       <?= $form->field($model, 'position') ?>
        
        <?= $form->field($model, 'vishes') ?>
        <?= $form->field($model, 'sex')->dropDownList([ '1' => 'мужской', '0' => 'женский']); ?>
        <?= $form->field($model, 'sendmail')->dropDownList([ '0' => 'Нет', '1' => 'Да']);?>
        <?= $form->field($model, 'status') ?>
    </div>
    <div class="col-xs-12 col-lg-3">
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
        
        <?= $form->field($model, 'discount') ?>
        <?= $form->field($model, 'inside')->textInput() ?>
        <?= $form->field($model, 'site')->textInput() ?>
    </div>
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'lastvisit')->textInput() ?>

        <?= $form->field($model, 'sum_visits')->textInput() ?>

        <?= $form->field($model, 'sum_tickets')->textInput() ?>

        <?= $form->field($model, 'vk_id')->textInput() ?>

        <?= $form->field($model, 'fb_id')->textInput() ?>

        <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_tt')->textInput(['maxlength' => true]) 
        ?>
        <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true]) 
        ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?= $form->field($model, 'tags')->textarea(['rows' => '3']) ?>
    </div>
</div>



<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
