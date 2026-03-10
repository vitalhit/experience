<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'date')->hiddenInput(['value' => $date])->label(false) ?>
    <?= $form->field($model, 'task_id')->hiddenInput(['value' => $task_id])->label(false) ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*,.pages, .numbers,.pdf,.doc,.docx,.xml,.jpg,.jpeg,.png,.gif,.xls,.xlsx']) ?>

    <?= $form->field($model, 'info')->textInput() ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>