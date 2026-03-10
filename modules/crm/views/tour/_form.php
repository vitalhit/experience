<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Cities;
use app\models\Companies;
use dosamigos\fileupload\FileUpload;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'company_id')->hiddenInput(['value' => Companies::getCompanyId()])->label(false) ?>
    
    <div class="col-xs-12 col-lg-4">
        <?php if ($model->image) { echo '<img src="'.$model->image.'" class="img-responsive">';}
        echo $form->field($model, 'image')->textInput(); 
        echo $form->field($model, 'image1')->textInput(); 
        echo $form->field($model, 'image2')->textInput(); 
        echo $form->field($model, 'poster')->textInput(['placeholder' => 'https://... — ссылка на афишу / картинка до 700 Кбайт /']); 

        ?>      
        <?php echo $form->field($model, 'status')->dropDownList([
            '0' => 'Планы',
            '1' => 'Работает',
            '7' => 'Доступно по ссылке',
            '100' => 'Завершено',
            '-1' => 'Удален',
        ]);
        echo $form->field($model, 'link_buy')->textInput(); 
        echo $form->field($model, 'link_vk')->textInput();
        echo $form->field($model, 'link_fb')->textInput();
        echo $form->field($model, 'link_insta')->textInput();
        echo $form->field($model, 'link_yt')->textInput();


        ?>



    </div>

    




    <div class="col-xs-12 col-lg-4">
        <?php
        // echo $form->field($model, 'category_id')->dropDownList($category, ['prompt' => 'Выберите ...']);
        echo $form->field($model, 'name')->textInput();
        echo $form->field($model, 'anons')->textInput();
        echo $form->field($model, 'eng_name')->textInput();

        echo $form->field($model, 'city')->textInput(['placeholder' => 'Тур / Москва ... ']);
        echo $form->field($model, 'alias')->textInput();
        echo $form->field($model, 'url')->textInput(); ?>       
    </div>
    <div class="col-xs-12 col-lg-4">
        <?php
        echo $form->field($model, 'video1')->textInput();
        echo $form->field($model, 'video2')->textInput();
        echo $form->field($model, 'video3')->textInput();
        ?>
        <label class="control-label" for="events-date">Дата для сортировки</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'date',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD',
                        ],
                    ]);?>

        <?php
        echo $form->field($model, 'date_title')->textInput();
        ?>
        <label class="control-label" for="events-date">Дата начала</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'date_start',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD ',
                        ],
                    ]);?>

        
        <label class="control-label" for="events-date">Дата конца</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'date_end',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD',
                        ],
                    ]);?>
        <?php
        echo $form->field($model, 'title_second')->textInput();
        ?>
                    
        
    </div>
</div>
    <p>Info</p>
    <?= $form->field($model, 'info')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>

    <p>Райдер</p>
    <?= $form->field($model, 'rider')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    <p>link_vk_audio</p>
    <?= $form->field($model, 'link_vk_audio')->textarea(['rows' => 2]); ?>


<div class="form-group">
    <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $('.steps .event').addClass('active');
</script>


<div class="overlay"></div>