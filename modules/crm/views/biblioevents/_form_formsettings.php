<?php
/// форма для информация после регистрации на событие


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
    

    
        <div class="clear"></div>
        
    
        <div class="col-xs-12 col-lg-12">
            

            <div class="mini_header mt0"><span>Информация после регистрации</span></div>
           
            <?= $form->field($model, 'info_reg_after')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
            )->label(false);?>


            
         </div>
        
        <div class="col-xs-6 col-lg-3">
            <?php echo $form->field($model, 'button_text')->textInput(['placeholder' => "Оставить заявку(по умолчанию)"]); ?> 

        </div>
        <div class="col-xs-6 col-lg-6">
            <?php 
            echo $form->field($model, 'event_status')->dropDownList([
             '' => 'Выберите ...',
            '0' => 'Ничего не делать',
            '1' => 'Выводить дату в шапке',
           
        ]);
            ?> 
        </div>
        
        
        <div class="col-xs-12 col-lg-12">
        <div class="mini_header mt0 "><span>Настройка промо страниц</span></div>
        </div>
        <div class="col-xs-6 col-lg-6">
            <?php echo $form->field($model, 'link_bot')->textInput(['placeholder' => "https://.."]);?> 
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
</div>
<div class="clear"></div>

<div class="form-group">
    <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right submit']) ?>
</div>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $('.steps .event').addClass('active');
</script>



<div class="overlay"></div>