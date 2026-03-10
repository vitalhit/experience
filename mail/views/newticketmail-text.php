n<?php

/** @var $this \yii\web\View */
/** @var $link string */
/** @var $paramExample string */

?>
<?= $person->name ?> <?= $person->second_name ?>!
Спасибо покупку билета
<?= $event->biblioevents->name ?>

<a href="https://igoevent.com/site/ticket?barcode=<?= $ticket->barcode ?>" style="color:#fff;background: red;padding: 15px 20px 15px 50px;background: #f00 url('https://igoevent.com/web/images/printico.png') no-repeat 15px center;background-size:20px;border-radius: 30px;margin:20px auto;display:block;width:90px;" target="_blank">ВАШ БИЛЕТ</a>

Информация о билете:
Билет №: <?= $ticket->id ?>
Название: <?= $ticket->seats->name ?>
Стоимость: <?= $ticket->money ?> р
Количество: 1
Штрихкод: <?= $ticket->barcode ?>

Актуальная информация о билете и событии в вашем личном кабинете на Igoevent.com

<hr>
<!--
Информация о событии:
Название: <?= $event->biblioevents->name ?>
Дата и время: <?= date("d.m.Y H:i", strtotime($event->date)) ?>
Место: <a href="http://igoevent.com/<?= $event->biblioevents->cities->alias; ?>/place/<?= $alias; ?>?utm_source=email&utm_medium=vitalhit&utm_campaign=tickets&utm_content=place-way&utm_term=<?= $event->biblioevents->places->id; ?>"><?= $event->biblioevents->places->name; ?></a>
Адрес: <?= $event->biblioevents->places->address; ?>
Как добраться: <?= $event->biblioevents->places->path; ?>
-->

Билет подтверждает ваше право участвовать в мероприятии. Будьте готовы предъявить его организаторам в электронном и/или бумажном виде.<br> Само письмо не является билетом! Ваш билет доступен по ссылкам выше! 2