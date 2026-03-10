<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>

Здравствуйте, <?= $name ?> <?= $second_name ?>! 
Вчера вы зарегистрировались на событие <?= $ticket->events->biblioevents->name ?>, но пока не оплатили свое участие.
Подскажите, пожалуйста, с чем это связано? Вы просто не успели это сделать или возникли какие-то сложности с оплатой?

Вы можете проверить срок брони и оплатить ваш билет по этой ссылке: <a href="https://igoevent.com/site/pay?order_id=<?= $ticket->order_id ?>&message_id=<?= $message->id ?>">Проверить бронь!</a>

Если у вас возникают проблемы с оплатой или любые другие технические сложности, вам поможет наша служба поддержки по адресу support@igoevent.com

Напоминаем вам, что только предварительная оплата гарантирует вам участие в мероприятии, а также, зачастую, более низкую цену билета и лучшие места в зале!

Информация о событии:
Название: <?= $ticket->events->biblioevents->name ?>
Дата и время: <?= date("d.m.Y H:i", strtotime($ticket->events->date)) ?>
Место: <?= $ticket->events->biblioevents->places->name; ?>
Адрес: <?= $ticket->events->biblioevents->places->address; ?>
Как добраться: <?= $ticket->events->biblioevents->places->path; ?>			


<?php if (!empty($ticket->events->letter)) {
	echo $ticket->events->letter;
}else{
	echo $ticket->events->biblioevents->letterBuy->text;
}?>
