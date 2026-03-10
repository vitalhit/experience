<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
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
        <?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive">';}
        echo $form->field($model, 'image')->fileInput(); 
        ?>   
    <div class="btn btn-success js_popup js_allimgband" tag="allimgband" oid="<?php echo $model->id ?>">выбрать существующую</div> <!-- кнопка -->         

       <?php
        // echo $form->field($model, 'video')->textInput(); // не нужно
        echo $form->field($model, 'video1')->textInput();
        echo $form->field($model, 'video2')->textInput();
        echo $form->field($model, 'video3')->textInput();
        echo $form->field($model, 'link_photo')->textInput();
        ?>  
        
        <?= $form->field($model, 'serv_info')->textarea(['rows' => 2]); ?>
    </div>




    <div class="col-xs-12 col-lg-4">
        <?php
        // echo $form->field($model, 'category_id')->dropDownList($category, ['prompt' => 'Выберите ...']);
        echo $form->field($model, 'name')->textInput();
        echo $form->field($model, 'anons')->textInput();
        echo $form->field($model, 'eng_name')->textInput();

        echo $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']);
        echo $form->field($model, 'alias')->textInput();
        echo $form->field($model, 'phone')->textInput();
        echo $form->field($model, 'phone2')->textInput(); ?>
    </div>
    <div class="col-xs-12 col-lg-4">
        <?php
        echo $form->field($model, 'link_site')->textInput();
        echo $form->field($model, 'link_tg')->textInput();
        echo $form->field($model, 'link_vk')->textInput();
        
        echo $form->field($model, 'link_fb')->textInput();
        echo $form->field($model, 'link_insta')->textInput();
        echo $form->field($model, 'link_yt')->textInput();
        
        echo $form->field($model, 'link_yandex')->textInput();
        echo $form->field($model, 'link_apple')->textInput();
        echo $form->field($model, 'link_spotify')->textInput();
        echo $form->field($model, 'link_vk_artist')->textInput();
        ?>

        <?php echo $form->field($model, 'status')->dropDownList([
            '1' => 'Выводить в bands (1)',
            '2' => 'Выводить в crm/events/..(2)',
            '7' => 'Доступно по ссылке(7)',
            '0' => 'Скрытое(0)'
        ]);
        ?>
        
    </div>
</div>
     <p>Info Top</p>
    <?= $form->field($model, 'info_top')->widget(CKEditor::className(),[
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
    <p>Биография</p>
    <?= $form->field($model, 'biography')->widget(CKEditor::className(),[
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
    <p>Дизайн</p>
    <?= $form->field($model, 'info_design')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>

    <p>Описание для страницы события(фестиваля)</p>
    <?= $form->field($model, 'info_event')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    
    <?= $form->field($model, 'link_vk_audio')->textarea(['rows' => 2]); ?>


<div class="form-group">
    <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $('.steps .event').addClass('active');
</script>

<div class="popup popup1000 allimgband"></div>
<div class="overlay"></div>