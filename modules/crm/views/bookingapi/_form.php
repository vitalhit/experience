<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\TasksStatus;
use app\models\Users;
use app\models\Companies;
use app\models\CompanyUser;


/* @var $this yii\web\View */
/* @var $model app\models\Bookingapi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bookingapi-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <?= $form->field($model, 'company_id')->hiddenInput(['value' => Yii::$app->user->identity->company_active])->label(false) ?>

        <div class="col-xs-12 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>
                </div><div class="col-xs-12 col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div><div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'thirdname')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <?= $form->field($model, 'message')->textarea(['rows' => 2]) ?>
            <?= $form->field($model, 'link_photo')->textInput(['maxlength' => true]) ?>              
            <?= $form->field($model, 'result')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'info')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'info_wish')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'info_goal')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'info_job')->textarea(['rows' => 1]) ?>
                

            <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
        </div>

        <div class="col-xs-12 col-md-2">            
                     
            <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link_site')->textInput(['maxlength' => true]) ?>              
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link_vk')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link_fb')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-md-2">

            <?php $user_ids = CompanyUser::find()->select('user_id')->where(['company_id' => Companies::getCompanyId()])->asArray()->all();
            $items_user = Users::Map($user_ids);
            echo $form->field($model, 'owner_id')->dropDownList($items_user, ['prompt' => 'Выберите ...']);?>
            <div class="clear"></div>
            <?php $tasksstatus = TasksStatus::find()->all(); 
            $items_stat = ArrayHelper::map($tasksstatus,'id','name');?>
            <?= $form->field($model, 'status_id')->dropDownList($items_stat) ?>
            <div class="clear"></div>
            <?= $form->field($model, 'time')->textInput() ?>
            <?= $form->field($model, 'brand_id')->textInput() ?>
            <div class="clear"></div>
            <label>Дата закрытия</label>
            <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'close_time',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD H:m',
                        //'minDate' => '2015-08-10',
                        //'maxDate' => '2015-09-10',
                        ],
                    ]);?>
            <div class="clear"></div>
            <?php  echo '<img width="100%" src="/uploads/booking/'.$model['image'].'">';
            //echo $model['image'];
            ?>
        </div>

        <div class="col-xs-12 col-md-2">
            <?= $form->field($model, 'biblioevent_id')->textInput() ?>  
            <?= $form->field($model, 'event_id')->textInput() ?>  
            <?php 
            if( !empty($lists) ){
                $form->field($model, 'list_id')->dropDownList(
                ArrayHelper::map($lists, 'id', 'name'),
                ['prompt' => 'Выберите список']
                ); 
                }else{ 
                    echo $form->field($model, 'list_id')->textInput();  
                }
            ?>
            
            <?= $form->field($model, 'serv_info')->textarea(['rows' => 3]) ?>            
            <?= $form->field($model, 'event_finance_id')->textInput() ?>
        </div>

        <div class="clear"></div>


<!--         <div class="col-xs-12 col-md-3">            
            <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>
        </div> -->

        <div class="col-xs-12 col-md-1">            
            <?= $form->field($model, 'sale')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-1">            
            <?= $form->field($model, 'role')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'sex')->dropDownList([
                '0' => 'неизвестно',
                '1' => 'Мужской',
                '2' => 'Женский'
            ])?>
        </div>


        <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'vk_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'fb_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'in_id')->textInput(['maxlength' => true]) ?>
        </div>
        
        <!-- <div class="col-xs-12 col-md-2">            
            <?= $form->field($model, 'birthday')->textInput(['maxlength' => true]) ?>
        </div> -->

        

        <div class="clear"></div>
        <div class="col-xs-12 col-md-2">
            <?= $form->field($model, 'from_url')->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-2"> 
            <?= $form->field($model, 'utm_source')->textInput() ?> 
        </div>
        <div class="col-xs-12 col-md-2"> 
            <?= $form->field($model, 'utm_medium')->textInput() ?> 
        </div>
        <div class="col-xs-12 col-md-2"> 
            <?= $form->field($model, 'utm_campaign')->textInput() ?> 
        </div>
        <div class="col-xs-12 col-md-2"> 
            <?= $form->field($model, 'utm_content')->textInput() ?> 
        </div>
        <div class="col-xs-12 col-md-2"> 
            <?= $form->field($model, 'utm_term')->textInput() ?> 
        </div>
        
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="clear"></div>
<div class=row>
    <?php if($model->image){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image1){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image1; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image2){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image2; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image3){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image3; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image4){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image4; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image5){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image5; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image6){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image6; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image7){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image7; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image8){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image8; ?>'>
    </div>
    <?php } ?>
    
    <?php if($model->image9){ ?>
    <div class="col-xs-12 col-md-2">
        <img width="100%" src='https://<?= $model->domain; ?>/uploads/booking/<?= $model->image9; ?>'>
    </div>
    <?php } ?>
    <div class="clear"></div>

    <div class="col-xs-12 col-md-12">
        <?php if($model->image){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image; ?><br>
        <?php } ?>
        <?php if($model->image1){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image1; ?><br>
        <?php } ?>
        <?php if($model->image2){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image2; ?><br>
        <?php } ?>
        <?php if($model->image3){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image3; ?><br>
        <?php } ?>
        <?php if($model->image4){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image4; ?><br>
        <?php } ?>
        <?php if($model->image5){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image5; ?><br>
        <?php } ?>
        <?php if($model->image6){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image6; ?><br>
        <?php } ?>
        <?php if($model->image7){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image7; ?><br>
        <?php } ?>
        <?php if($model->image8){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image8; ?><br>
        <?php } ?>
        <?php if($model->image9){ ?>
        https://<?= $model->domain; ?>/uploads/booking/<?= $model->image9; ?><br>
        <?php } ?>
    </div>
</div>

