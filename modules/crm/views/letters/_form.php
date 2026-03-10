 <?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Letters */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="letters-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-lg-6">
            <?php if (!empty($biblioevent)) {
                echo '<input type="hidden" id="biblioevent" class="form-control" name="Letters[biblioevent_id]" value="'.$biblioevent->id.'">';
            } 
            ?>
            <?= $form->field($model, 'biblioevent_id')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'type')->dropDownList([
                '1' => 'После регистрации',
            ]);?>
            
            <?= $form->field($model, 'status')->dropDownList([
                '1' => 'Активно',
                '0' => 'Скрыто',
            ]);?>
        </div>

        <div class="col-xs-12 col-lg-6">
            <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group clear">
        <input type="submit" name="new" class="btn btn-info" value="Сохранить и создать еще письмо">
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $('.steps .lett').addClass('active');
</script>