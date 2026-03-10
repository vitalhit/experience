<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

if (isset($event->biblioevents->places->alias)) {
	$alias = $event->biblioevents->places->alias;
} else {
	$alias = $event->biblioevents->places->id;
}

?>


<table align="center" cellpadding="0" cellspacing="0" style="border:none;font-size:14px;line-height:19px;margin:0px auto;max-width:600px;width:100%">
	<tbody>
		<tr>
			<td colspan="2" style="padding:30px 0 20px 100px;background-image:url(https://igoevent.com/images/lettertop.jpg);">
				<h2 style="font-size:21px;margin:0px 0px 10px;color:#fff;text-align:center;"><?= $person->name ?> <?= $person->second_name ?>! </h2>
				<p style="font-size:16px;margin:0px 0px 10px;color:#fff;text-align:center;">Спасибо за покупку билета</p>
				<p style="font-size:16px;margin:0px 0px 10px;color:#fff;text-align:center;"><b><?= $event->biblioevents->name ?></b></p>
				<div style="float:none; clear: both; height: 10px;"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p style="font-size:16px;margin:0px 0px 10px">
					<?php if (!empty($event->letter)) {
						echo $event->letter;
					}else{
						echo $event->biblioevents->letterBuy->text;
					}?>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php foreach ($tickets as $ticket) { ?>
					<a href="https://igoevent.com/site/ticket?barcode=<?= $ticket->barcode ?>" style="color:#fff;background: red;padding: 15px 20px 15px 50px;background: #f00 url('https://igoevent.com/web/images/printico.png') no-repeat 15px center;background-size:20px;border-radius: 30px;margin:20px auto;display:block;width:140px;" target="_blank">ВАШ БИЛЕТ №<?= $ticket->id ?></a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php foreach ($tickets as $ticket) { ?>
					<h3>Информация о билете:</h3>
					<p>Билет №: <b><?= $ticket->id ?></b></p>
					<p>Название: <b><?= $ticket->seats->name ?></b></p>
					<p>Стоимость: <b><?= $ticket->money ?> р</b></p>
					<p>Количество: <b>1</b></p>
					<p>Штрихкод: <b><?= $ticket->barcode ?></b></p>
					<hr>
				<?php } ?>

				<p style="font-size:12px;margin-bottom: 20px;">Актуальная информация о билете и событии в вашем личном кабинете на Igoevent.com</p>


				<hr>
<!--
				<h3>Информация о событии:</h3>
				<p>Название: <b><?= $event->biblioevents->name ?></b></p>
				<p>Дата и время: <b><?= date("d.m.Y H:i", strtotime($event->date)) ?></b></p>
				<p>Место: <b><a href="http://igoevent.com/<?= $event->biblioevents->cities->alias; ?>/place/<?= $alias; ?>?utm_source=email&utm_medium=vitalhit&utm_campaign=tickets&utm_content=place-way&utm_term=<?= $event->biblioevents->places->id; ?>"><?= $event->biblioevents->places->name; ?></a></b></p>
				<p>Адрес: <b><?= $event->biblioevents->places->address; ?></b></p>
				<p>Как добраться: <b><?= $event->biblioevents->places->path; ?></b></p>
-->
				<p style="font-size:12px;margin-bottom: 20px;">Билет подтверждает ваше право участвовать в мероприятии. Будьте готовы предъявить его организаторам в электронном и/или бумажном виде.<br> Само письмо не является билетом! Ваш билет доступен по ссылкам выше! 3</p>

			</td>
		</tr>

		
		
	</tbody>
</table>