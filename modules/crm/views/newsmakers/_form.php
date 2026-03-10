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
    <div class="col-xs-12 col-lg-2">
        
        <?= $form->field($model, 'title') ?>
        
        
        <?= $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']) ?>
        <?= $form->field($model, 'link_vk')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'public_vk_id')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'status')->dropDownList([ '' => 'Локальный', '1' => 'Глобальный','-1' => 'Не выводить в списке СМИ']);?>
        <?php if ($model->company_id){ ?>
        <?= $form->field($model, 'company_id')->textInput(); ?>
        <?php 
            }else{ ?>
            <?= $form->field($model, 'company_id')->textInput(['value' => Companies::getCompanyId() ]); ?>   
        <?php
            }
        ?>
       <?= $form->field($model, 'groups')->dropDownList([ '' => 'Без группы', 'ВК:паблик' =>'ВК:паблик', 'ВК:паблик - не размещют' =>'ВК:паблик - не размещют', 'ВК:группа'=>'ВК:группа' , 'Скидки' =>'Скидки', 'СМИ' => 'СМИ','СМИ:политика'=>'СМИ:политика', '1' => 'Билетный оператор','Интернет издание' => 'Интернет издание', 'Радио' => 'Радио', 'Бумажное издание' => 'Бумажное издание',  ]);?>

    <div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success pull-right']) ?>
</div>

    </div>
    <div class="col-xs-12 col-lg-2">
        <?php if ($model->name){ ?>
            <?= $form->field($model, 'second_name'); ?>
        <?php 
            }else{ ?>
             <?= $form->field($model, 'second_name')->textInput(['value' => "-" ]); ?>
        <?php
            }
        ?>
        <?php if ($model->name){ ?>
            <?= $form->field($model, 'name'); ?>
        <?php 
            }else{ ?>
             <?= $form->field($model, 'name')->textInput(['value' => "-" ]); ?>
        <?php
            }
        ?>
        
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'mail') ?>
        <?= $form->field($model, 'phone') ?>
      
        <?= $form->field($model, 'template_id')->dropDownList([ '' => 'по умолчанию',  '2' => 'ВК: link_vk & place', '3'=>'ВК: загловок&ссылка + розыгрыш билетов', '99' => 'Скидка / Культурная разморозка',]) ?>
        <?= $form->field($model, 'sex')->dropDownList([ '1' => 'мужской', '0' => 'женский', '2' => 'Группа', '3'=>'Рабочий аккаунт']); ?>
        <?= $form->field($model, 'sendmail')->dropDownList([ '0' => 'Нет', '1' => 'Да']);?>
        <?= $form->field($model, 'vk_id')->textInput() ?>
        
        <?= $form->field($model, 'birthday')->textInput(['placeholder' => "1980-05-28"]) ?>
    </div>
    <div class="col-xs-12 col-lg-2">
        <?= $form->field($model, 'user_id')->textInput() ?>
        
        <?php $froms = Froms::find()->all(); 
        $items = ArrayHelper::map($froms,'id','url');
        $params = ['prompt' => 'Выберите...']; ?>
        <?= $form->field($model, 'froms_id')->dropDownList($items,$params);?>
        
        <?= $form->field($model, 'discount') ?>
        <?= $form->field($model, 'inside')->textInput() ?>
        <?= $form->field($model, 'site')->textInput() ?>
    </div>
    <div class="col-xs-12 col-lg-3">

        

        <?= $form->field($model, 'fb_id')->textInput() ?>
        <?= $form->field($model, 'link_tt')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_ok')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_fb')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'utm_source')->textInput() ?>
        <?= $form->field($model, 'utm_medium')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'utm_campaign')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'utm_content')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'utm_term')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'deleted')->dropDownList([ '0' => 'Нет', '1' => 'Да']);?>
    </div>
    <div class="col-xs-12 col-lg-12">
        <?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
    </div>
</div>



<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-success pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
