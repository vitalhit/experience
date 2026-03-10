<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Places;
use app\models\Cities;
use app\models\Categoryevents;
use dosamigos\fileupload\FileUpload;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (!empty($company)) { echo $form->field($model, 'company_id')->hiddenInput(['value' => $company['id']])->label(false);} ?>
    
    <div class="col-xs-12 col-lg-3">
        <?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive">';}?>
    </div>
    <div class="clearfix">...</div>
    <h4> Код счетчика</h4>
    <div class="col-xs-12 col-lg-6">
        <?php echo $form->field($model, 'counter_head')->textarea(['rows' => 5])?>
    </div>
    <div class="col-xs-12 col-lg-6">
        <?php echo $form->field($model, 'counter_body')->textarea(['rows' => 5]) ?>
    </div>
    <h4> Сторонняя кнопка(убирает кнопки «Купить билет» с igoevent.com)</h4>
    <div class="col-xs-12 col-lg-6">
        <?php echo $form->field($model, 'button_head')->textarea(['rows' => 5])?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <?php echo $form->field($model, 'button_code')->textarea(['rows' => 5]) ?>
    </div>

     <div class="col-xs-6 col-lg-6">
            <?php 
            echo $form->field($model, 'event_status')->dropDownList([
             '' => 'Выберите ...',
            '0' => 'Ничего не делать| 0',
            '1' => 'Выводить дату в шапке | 1 ',
            '2' => 'Выводить дату в шапке и сторонюю кнопки из дат | 2',
            '-1' => 'Убрать кнопку «Оставить заявку» | -1',
           
        ]);
            ?> 
        </div>
        
    
</div>


<div class="form-group">
    <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $('.steps .event').addClass('active');
</script>