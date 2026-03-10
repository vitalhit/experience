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

    
    <div class="btn btn-success js_popup js_allimglanding" tag="allimglanding" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anons')->textInput(['maxlength' => true]) ?>


    <div class="mini_header mt0"><span>2 БЛОК - ТЕКСТ</span></div>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    
    <p>Для кого/клиентам</p>
    <?= $form->field($model, 'info_client')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    <p>Программа</p>
    <?= $form->field($model, 'info_program')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    <?= $form->field($model, 'info_program_show')->dropDownList([
                        '' => 'Показывать',
                        '-1' => 'Скрыть',
                    ]);?>


    <p>О тарифах/стоимости</p>
    <?= $form->field($model, 'info_tariff')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>

    <p> текст в конце</p>
    <?= $form->field($model, 'text2')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
    
    <p>Об выступающих</p>
    <?= $form->field($model, 'info_band')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>

    <?= $form->field($model, 'info_program_show')->dropDownList([
                        '' => 'Показывать',
                        '-1' => 'Скрыть',
                        '2' => 'Показывать текст и подгружать бенды'
                    ]);?>

   
    <p>Информация организатора</p>
    <?= $form->field($model, 'info_org')->widget(CKEditor::className(),[
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
<p style="color: red;">Чтобы удалить фотографию, нужно сделать следующее: нажать на иконку «карзина» <span class="glyphicon glyphicon-trash"></span> рядом с соответсвующей фотографией и обязательно «Сохранить» страницу. Для сохранения страницы — нажмите кнопку «Сохранить» в самом низу.</p>
    <?= $form->field($model, 'imagetitle')->textInput(['maxlength' => true]) ?>



    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image1"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image1) { echo '<img src="/uploads/users/'.$model->image1.'" class="img-responsive">';}
            echo $form->field($model, 'image1')->fileInput(); ?>
    <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="1" oid="<?php echo $model->id; ?>">выбрать существующую</div> 


            <?= $form->field($model, 'image1title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image1link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image1author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage img-responsive" tag="image2"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image2) { echo '<img src="/uploads/users/'.$model->image2.'" class="img-responsive">';}
            echo $form->field($model, 'image2')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="2" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image2title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image2link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image2author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image3"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image3) { echo '<img src="/uploads/users/'.$model->image3.'" class="img-responsive">';}
            echo $form->field($model, 'image3')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="3" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image3title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image3link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image3author')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image4"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image4) { echo '<img src="/uploads/users/'.$model->image4.'" class="img-responsive">';}
            echo $form->field($model, 'image4')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="4" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image4title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image4link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image4author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image5"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image5) { echo '<img src="/uploads/users/'.$model->image5.'" class="img-responsive">';}
            echo $form->field($model, 'image5')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="5" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image5title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image5link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image5author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image6"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image6) { echo '<img src="/uploads/users/'.$model->image6.'" class="img-responsive">';}
            echo $form->field($model, 'image6')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="6" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image6title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image6link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image6author')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image7"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image7) { echo '<img src="/uploads/users/'.$model->image7.'" class="img-responsive">';}
            echo $form->field($model, 'image7')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="7" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image7title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image7link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image7author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image8"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image8) { echo '<img src="/uploads/users/'.$model->image8.'" class="img-responsive">';}
            echo $form->field($model, 'image8')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="8" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image8title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image8link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image8author')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image9"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image9) { echo '<img src="/uploads/users/'.$model->image9.'" class="img-responsive">';}
            echo $form->field($model, 'image9')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="9" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image9title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image9link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image9author')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <div class="delimage" tag="image10"><span class="glyphicon glyphicon-trash"></span></div>
            <?php if ($model->image10) { echo '<img src="/uploads/users/'.$model->image10.'" class="img-responsive">';}
            echo $form->field($model, 'image10')->fileInput(); ?>
            <div class="btn btn-success js_popup js_allimglandingimg" tag="allimglandingimg" imageid="10" oid="<?php echo $model->id; ?>">выбрать существующую</div> 

            <?= $form->field($model, 'image10title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image10link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image10author')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

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

    <div class="mini_header mt0"><span>Дополнительные настройки</span></div>

    <?= $form->field($model, 'imagept')->textInput(['maxlength' => true,  'placeholder'=>'Число от 1 до 100. Если лица на фото по середине, то 40']) ?>

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


<div class="popup popup1000 allimglanding"></div>
<div class="popup popup1000 allimglandingimg"></div>
<div class="overlay"></div>