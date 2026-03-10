<?php
use yii\widgets\ActiveForm;
?>


<?php if($model->image): ?>
    <img src="/web/uploads/<?= $model->image?>" alt="класс">
<?php endif; ?>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'image')->fileInput() ?>
<button>Загрузить</button>
<?php ActiveForm::end() ?>

