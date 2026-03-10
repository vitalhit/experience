<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>

<?= $event_id ?>

Спасибо, за покупку билета!


Здравствуйте, <?= $name ?> <?= $second_name ?>!

Спасибо за регистрацию на событие <?= $buying_event ?> | <?= $date ?>

<?
if ($buying_seats) {
	foreach ($buying_seats as $buying_seat) {						
		echo '№'.$buying_seat[0];
		echo 'Цена'.$buying_seat[1];
		echo 'Кол-во'.$buying_seat[2];
		echo 'Сумма'.$buying_seat[1]*$buying_seat[2];
	}
}
?>