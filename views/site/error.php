<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

	<h1>
	<?php if ($name == 'Forbidden (#403)') {
			echo 'У вас нет прав!';
		}elseif($name == 'Not Found (#404)'){
			echo 'Нет такой страницы!';
		}else{
			echo Html::encode($this->title);
		}?>
	</h1>

	<div class="alert alert-danger">
		<?= nl2br(Html::encode($message)) ?>
	</div>

</div>
