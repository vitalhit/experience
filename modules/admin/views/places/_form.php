<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Places;
use app\models\Cities;
use app\models\Companies;
use mihaildev\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model app\models\Places */
/* @var $form yii\widgets\ActiveForm */
?>
 
<div class="places-form">

    <?php $form = ActiveForm::begin(); ?>

<!--
    <?= $form->field($model, 'yandex')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'map')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>
-->


<div class="row">
    <div class="col-xs-12 col-lg-3">
        <small><?php if ($model->foto) { echo '<img src="https://igoevent.com/uploads/places/'.$model->foto.'" class="img-responsive">';}
        echo $form->field($model, 'foto')->fileInput(); ?>
        <?php if ($model->map) { echo '<img src="https://igoevent.com/uploads/places/'.$model->map.'" class="img-responsive">';}
        echo $form->field($model, 'map')->fileInput(); ?>
        <?php if ($model->shema) { echo '<img src="https://igoevent.com/uploads/places/'.$model->shema.'" class="img-responsive">';}
        echo $form->field($model, 'shema')->fileInput(); ?>
        <?php if ($model->foto_street) { echo '<img src="https://igoevent.com/uploads/places/'.$model->foto_street.'" class="img-responsive">';}
        echo $form->field($model, 'foto_street')->fileInput(); ?>
        <?php if ($model->foto_stage) { echo '<img src="https://igoevent.com/uploads/places/'.$model->foto_stage.'" class="img-responsive">';}
        echo $form->field($model, 'foto_stage')->fileInput(); ?>
        <?php if ($model->foto_hall) { echo '<img src="https://igoevent.com/uploads/places/'.$model->foto_hall.'" class="img-responsive">';}
        echo $form->field($model, 'foto_hall')->fileInput(); ?>
        <?php if ($model->foto_seats) { echo '<img src="https://igoevent.com/uploads/places/'.$model->foto_seats.'" class="img-responsive">';}
        echo $form->field($model, 'foto_seats')->fileInput(); ?>
        </small>

        
       
    </div>
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 'true']) ?>
        
        <?= $form->field($model, 'name_before')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->orderBy(['name' => SORT_DESC])->all(), 'id', 'name'), ['prompt' => 'Выберите ...']) ?>
        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'postcode')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'metro')->textInput(['maxlength' => true]) ?>
        
        <hr>
        <?= $form->field($model, 'no_address')->dropDownList(['' => 'Есть','1' => 'Нет', '3' => 'Онлайн']);?>
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'gps')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'favour')->dropDownList(['1' => 'Избранное','0' => 'Нет']);?>
        <?= $form->field($model, 'menu')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'priority')->textInput(['maxlength' => true]) ?>
        
        
        
        

    </div>
    <div class="col-xs-12 col-lg-3">
        

        
        
        <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_vk')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_fb')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_ok')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_yt')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_gmap')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_vk_map')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_rider')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_photo')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'numseats')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'numseats_max')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'just_sitting')->dropDownList([
                        '' => 'стоячия места есть',
                        '1' => 'Только сидя(1)',
                        ]);?>
        <?= $form->field($model, 'soldout')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'standing')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'standing_max')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'standing_building')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'numseats_vip')->textInput(['maxlength' => true]) ?>
         <?= $form->field($model, 'numseats_dance_floor')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'info_dance_floor') ?>
        <?= $form->field($model, 'info_for_eventer')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-lg-3">
        <?= $form->field($model, 'serv_info')->textarea(['rows' => 5]) ?>
        <?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map(Companies::find()->orderBy('id')->all(), 'id', 'name'), ['prompt' => 'Выберите ...']) ?>
        
        <?= $form->field($model, 'status')->dropDownList([
                        '1' => 'Активно: отображается в выборе мест(1)',
                        '0' => 'Скрытая: не отобржает в выборе мест(0)',
                        '-1' => 'Скрытая: даже не 0, а -1(-1)',
                        '2' => 'Активно: есть выбор,нет в places/ (2)',
                        ]);?>
        <?= $form->field($model, 'closed')->dropDownList([
                        '0' => 'Заведение работает / work (0)',
                        '1' => 'Заведение закрыто / closed (1)',
                        
                    ]);?>
        <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'video_map')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'video_car')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'css')->textarea(['rows' => 30]) ?>
        <?= $form->field($model, 'scheme_width')->textInput(['placeholder' => '>560, если схема широкая']) ?>
    </div>
    <div class="clear"></div>
    <div class="col-xs-12 col-lg-6">

        <?= $form->field($model, 'pathshort')->textInput(['maxlength' => true]) ?>
        <p>Как добраться</p>
        <?= $form->field($model, 'path')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
        )->label(false);?>
        <p>Описание/анотация</p>
        <?= $form->field($model, 'description')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
        )->label(false);?>
        
            <p>Info</p>
        <?= $form->field($model, 'info')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label(false);?>
        <p>Info for Event Page</p>
        <?= $form->field($model, 'info_event')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label(false);?>
        

         <?= $form->field($model, 'info_ticket')->textarea(['rows' => 3]) ?>
    </div>
    <div class="col-xs-12 col-lg-6">
         <p>Райдер</p>
        <?= $form->field($model, 'rider')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label(false);?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
        </div>

    </div>
</div>  

<?php ActiveForm::end(); ?>

</div>