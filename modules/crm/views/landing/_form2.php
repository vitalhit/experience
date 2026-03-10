<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Landing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="landing-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false); ?>

    <div class="row">
        <div class="col-xs-12 col-lg-3 pull-right">
            <?= $form->field($model, 'status')->dropDownList(['1' => 'Активен','0' => 'Выключен'])->label(false) ?>
        </div>
    </div>

    <div class="clear"></div>

    <div class="mini_header mt0"><span>ПЕРВЫЙ ЭКРАН</span></div>
    <div class="delimage" tag="image"><span class="glyphicon glyphicon-trash"></span></div>

    <?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive">';}


    echo $form->field($model, 'image')->fileInput(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anons')->textInput(['maxlength' => true]) ?>


    <div class="mini_header mt0"><span>2 БЛОК - ТЕКСТ</span></div>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>

    <div class="mini_header mt0"><span>3 БЛОК - ВИДЕО</span></div>

    <?= $form->field($model, 'videotitle')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'video1')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'video2')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'video3')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="mini_header mt0"><span>4 БЛОК - ФОТО</span></div>

    <?= $form->field($model, 'imagetitle')->textInput(['maxlength' => true]) ?>

    
    <div class="mini_header mt0"><span>5 БЛОК - СоцСети</span></div>

    <div class="row">
        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_kg')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_site')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_vk')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_fb')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_yt')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-3">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="mini_header mt0"><span>5 БЛОК - SEO</span></div>

    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'seotitle')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'seodesc')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'seokey')->textarea(['rows' => 3]) ?>
        </div>
    </div>


    <div class="mini_header mt0"><span>6 БЛОК - OG</span></div>

    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'ogtitle')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'ogdescription')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="ogimage"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->ogimage) { echo '<img src="/uploads/users/'.$model->ogimage.'" class="img-responsive">';}
            echo $form->field($model, 'ogimage')->fileInput(); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image1')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image2')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image3')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image4')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image5')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image6')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image7')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image8')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image9')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'image10')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'bg_color')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="mini_header mt0"></div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">
    $(document).ready(function() {
        // Удаляем картинку
        $(document).on('click', ".delimage", function(){
            var bloimg = $(this).next('img');
            var img = $(this).attr('tag');
            var landing_id = $("#landing-id").val();
            console.log(img);
            console.log(landing_id);

            $.ajax({
                type: 'get',
                url: "/crm/landing/delimage",
                data: {'img': img, 'landing_id': landing_id},
                response: 'text',
                success: function(data){
                    $(bloimg).remove();
                }
            })
        });
    });
</script>