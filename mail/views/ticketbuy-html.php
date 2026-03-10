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
			<td colspan="2">
				<p style="font-size:16px;margin:0px 0px 10px">Добрый день! <?= $name ?> <?= $second_name ?>! </p>
				<p style="font-size:16px;margin:0px 0px 10px">Спасибо за регистрацию на событие <b><?= $event->biblioevents->name ?> | <?= date("d.m.Y H:i", strtotime($event->date)) ?></b></p>
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
				<? if ($buying_seats) {				
					foreach ($buying_seats as $buying_seat) {	
						echo '<div style="overflow: hidden;">
								<div style="width:60%; height:50px; float:left; background: #eee; padding: 2%;">
									<div style="width:100%; float:none; height:20px; margin-bottom: 10px;"><b>Билет № '.$buying_seat[0].'</b></div>
									<div style="width:30%; float:left;">Цена '.$buying_seat[1].' руб.</div>
									<div style="width:30%; float:left;">Кол-во '.$buying_seat[2].'</div>
									<div style="width:30%; float:left;">Сумма '.$buying_seat[1]*$buying_seat[2].' руб.</div>
								</div>
								<div style="width:14%;text-align:center;height:50px;float:left;background:#326eaa;padding:2%;">
									<a href="https://igoevent.com/site/ticket?id='.$buying_seat[3].'" style="color: #fff;"><img src="https://igoevent.com/images/printico.png" style="margin:auto;display:block;">распечатать</a>
								</div>
								<div style="width:14%;text-align:center;height:50px;float:left;background:#326eaa;padding:2%;">
									<a href="https://igoevent.com/site/ticket?id='.$buying_seat[3].'" style="color: #fff;"><img src="https://igoevent.com/images/viewico.png" style="margin:auto;display:block;">посмотреть</a>
								</div>
							</div>';
					}
				}?>

				<p style="font-size:12px">Билеты подтверждают ваше право участвовать в мероприятии. Будьте готовы предъявить их организаторам в электронном и/или бумажном виде.<br> Cамо письмо не является билетом! Ваши билеты доступны по ссылкам выше!</p>

				<h3>Информация о событии:</h3>
				<p>Название: <b><?= $event->biblioevents->name ?></b></p>
				<p>Дата и время: <b><?= date("d.m.Y H:i", strtotime($event->date)) ?></b></p>
				<p>Место: <b><a href="https://igoevent.com/<?= $event->biblioevents->places->city->alias; ?>/place/<?= $alias; ?> ?utm_source=email&utm_medium=vitalhit&utm_campaign=tickets&utm_content=place-way&utm_term=<?= $event->biblioevents->places->id; ?>"><?= $event->biblioevents->places->name; ?></a></b></p>
				<p>Адрес: <b><?= $event->biblioevents->places->address; ?></b></p>
				<p>Как добраться: <b><?= $event->biblioevents->places->path; ?></b></p>			
			</td>
		</tr>


		
	</tbody>
</table>