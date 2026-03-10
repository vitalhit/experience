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
    
    <div class="col-xs-12 col-lg-4">
        <?php if (!$model->isNewRecord) {
            if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive"><a href="https://crm.igoevent.com/admin/img/update?id='.($model->img->id??Null).'">edit img('.($model->img->id??Null).')</a>';}
            
            echo $form->field($model, 'image')->fileInput();
            
            ?>
            <div class="btn btn-success js_popup js_allimg" tag="allimg" oid="<?php echo $model->id ?>">выбрать существующую</div>
        <?php } ?>

       
    </div>

    <div class="col-xs-12 col-lg-4">
        <?php
        
        echo $form->field($model, 'name')->textInput();

        // echo $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']);
        echo $form->field($model, 'city')->hiddenInput()->label(false);
        echo $form->field($model, 'place_id')->hiddenInput()->label(false);
        
        if (!$model->isNewRecord) {
            echo $form->field($model, 'city')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите ...']);

            echo  $form->field($model, 'place_id')->dropDownList(ArrayHelper::map(Places::find()->andwhere('status > 0')->where('city = '.$model->city)->all(), 'id', 'name','type'), ['prompt' => 'Выберите ...']);
        }
           
        echo $form->field($model, 'status')->dropDownList([
            '1' => 'Работает|1',
            '2' => 'Для igoigo.ru|2',
            '7' => 'Доступно по ссылке|7',
            '0' => 'Скрытое|0',
            '-1' => 'Удаленное(после сохранения исчезает из списка событий)|-1'
        ]);
        ?>
    </div>
    
    <div class="col-xs-12 col-lg-4">
        <?php
        echo $form->field($model, 'category_id')->dropDownList($category, ['prompt' => 'Выберите ...']);
        echo $form->field($model, 'age')->dropDownList([
            '0+' => '0+',
            '1+' => '1+',
            '2+' => '2+',
            '3+' => '3+',
            '4+' => '4+',
            '5+' => '5+',
            '6+' => '6+',
            '7+' => '7+',
            '8+' => '8+',
            '9+' => '9+',
            '10+' => '10+',
            '11+' => '11+',
            '12+' => '12+',
            '13+' => '13+',
            '14+' => '14+',
            '15+' => '15+',
            '16+' => '16+',
            '17+' => '17+',
            '18+' => '18+',
            '19+' => '19+',
            '20+' => '20+',
            '21+' => '21+'
        ]);
        ?>
    </div>
        <div class="col-xs-12 col-lg-12">
            <div class="mini_header mt0"><span>Описание события</span></div>

            <?= $form->field($model, 'info')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label();?> 

            <?= $form->field($model, 'info_reg_after')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label();?>


        </div>
        <div class="col-xs-6 col-lg-3">
            <?php if (!$model->id) {

                echo $form->field($model, 'alias')->textInput(['readonly' => 'readonly']);  
            } else {
                echo $form->field($model, 'alias')->textInput(); 
            }
            ?>

        </div>
        <div class="col-xs-6 col-lg-3">
            <?php echo $form->field($model, 'button_default')->textInput(['placeholder' => "при создание даты"]); ?>
        </div>
        <div class="col-xs-6 col-lg-3">
            <?php echo $form->field($model, 'button_text')->textInput(['placeholder' => "Оставить заявку(по умолчанию)"]); ?> 

        </div>
        <div class="col-xs-6 col-lg-3">
            <?php echo $form->field($model, 'link_buy')->textInput(['placeholder' => "сайт, сторонний)"]); ?> 
        </div>

       <div class="col-xs-12 col-lg-12">
        <div class="mini_header mt0 "><span>Автоматизация работы с ВКонтакте </span></div>
    </div>


    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'link_vk')->textInput(); ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'link_tg')->textInput(); ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'link_vk_cc')->textInput(['placeholder' => "Короткая ссылка"]);?> 
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'vksend')->textInput( ['pattern' =>'\d+(,\d+)?(,\d+)?(,\d+)?(,\d+)?',  'placeholder' => '90794,222,33,44,55 — числа через запятую без пробелов(не больше пяти чисел)']); ?>
    </div>

    <div class="col-xs-12 col-lg-12">
        <div class="mini_header mt0 "><span>Автоматизация работы с площадками </span></div>
    </div>

    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'link_kg')->textInput(['placeholder' => "https://kudago.com/.."]);?> 
    </div>
    <div class="col-xs-12 col-lg-12">
        <div class="mini_header mt0 "><span>Настройка промо страниц</span></div>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'link_bot')->textInput(['placeholder' => "https://.."]);?> 
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'redirect_time')->textInput(['placeholder' => "5 секунд по умолчанию"]);?> 
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'template_id')->dropDownList([
            '' => 'Шаблон формы по умолчанию',
            '1' => '| 1',
            '2' => '| 2',
            '3' => 'форма предзаписи для курсов| 3'
        ]); ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'landing_type')->dropDownList([
            '' => 'Стандартная промо страница | ',
            '2' => 'Красно-розовый/для курсов | 2',
            '20211107' => 'Красно-розовый/ исходник | 20211107'
        ]);
        ?>
    </div>
    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'show_date')->dropDownList([
            '1' => 'Показывать | 1  ',
            '0' => 'Скрыть | 0',
        ]);
        ?>
    </div>

    <div class="col-xs-6 col-lg-6">
        <?php echo $form->field($model, 'band_id')->textInput(['placeholder' => "Band's Id"]);?> 
    </div>


</div>
<div class="clear"></div>

<div class="form-group">
    <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $('.steps .event').addClass('active');
</script>


<div class="popup popup1000 allimg"></div>
<div class="overlay"></div>
