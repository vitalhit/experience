<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
Добрый день, <?= $person->name ?> <?= $person->second_name ?>!
Спасибо за покупку билета на событие <?= $event->biblioevents->name ?> | <?= date("d.m.Y H:i", strtotime($event->date)) ?>

<?php foreach ($tickets as $ticket) { ?>
	<h3>Информация о билете:</h3>
	<p>Билет №: <b><?= $ticket->id ?></b></p>
	<p>Название: <b><?= $ticket->seats->name ?></b></p>
	<p>Стоимость: <b><?= $ticket->money ?> р</b></p>
	<p>Количество: <b>1</b></p>
	<p>Штрихкод: <b><?= $ticket->barcode ?></b></p>
	<a href="https://igoevent.com/site/ticket?barcode=<?= $ticket->barcode ?>" style="color:#fff;background: red;padding: 15px 20px 15px 50px;background: #f00 url('https://igoevent.com/web/images/printico.png') no-repeat 15px center;background-size:20px;border-radius: 30px;margin:20px auto;display:block;width:90px;" target="_blank">ВАШ БИЛЕТ №<?= $ticket->id ?></a>
	<hr>
	<?php } ?>