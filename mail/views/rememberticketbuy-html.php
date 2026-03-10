<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>


<table align="center" cellpadding="0" cellspacing="0" style="border:none;font-size:16px;line-height:19px;margin:0px auto;max-width:600px;width:100%">
	<tbody>
		<tr>
			<td colspan="2">
				<p>Здравствуйте, <?= $name ?> <?= $second_name ?>! </p>
				<p>Вчера вы зарегистрировались на событие <b><?= $ticket->events->biblioevents->name ?></b>, но пока не оплатили свое участие.</p>
				<p>Подскажите, пожалуйста, с чем это связано? Вы просто не успели это сделать или возникли какие-то сложности с оплатой?</p>

				<p>
					Вы можете проверить срок брони и оплатить ваш билет по этой ссылке: 
					<b><a href="https://igoevent.com/site/pay?order_id=<?= $ticket->order_id ?>&message_id=<?= $message->id ?>">Проверить бронь!</a></b>
				</p>

				<p>Если у вас возникают проблемы с оплатой или любые другие технические сложности, вам поможет наша служба поддержки по адресу support@igoevent.com</p>

				<p>Напоминаем вам, что только предварительная оплата гарантирует вам участие в мероприятии, а также, зачастую, более низкую цену билета и лучшие места в зале!</p>

				<h3>Информация о событии:</h3>
				<p>Название: <b><?= $ticket->events->biblioevents->name ?></b></p>
				<p>Дата и время: <b><?= date("d.m.Y H:i", strtotime($ticket->events->date)) ?></b></p>
				<p>Место: <b><?= $ticket->events->biblioevents->places->name; ?></b></p>
				<p>Адрес: <b><?= $ticket->events->biblioevents->places->address; ?></b></p>
				<p>Как добраться: <b><?= $ticket->events->biblioevents->places->path; ?></b></p>			
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<p>
					<?php if (!empty($ticket->events->letter)) {
						echo $ticket->events->letter;
					}else{
						echo $ticket->events->biblioevents->letterBuy->text;
					}?>
				</p>
			</td>
		</tr>
		
	</tbody>
</table>