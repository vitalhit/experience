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
				<p>Вчера вы забронировали аренду через сервис igoevent.com, но пока не оплатили.</p>
				<p>Подскажите, пожалуйста, с чем это связано? Вы просто не успели это сделать или возникли какие-то сложности с оплатой?</p>

				<p>
					Вы можете проверить и оплатить вашу бронь по этой ссылке: 
					<b><a href="https://igoevent.com/site/payrent?order_id=<?= $rent->order_id ?>&message_id=<?= $message->id ?>">Проверить бронь!</a></b>
				</p>

				<p>Если у вас возникают проблемы с оплатой или любые другие технические сложности, вам поможет наша служба поддержки по адресу support@igoevent.com</p>

				<p>Напоминаем вам, что только оплата гарантирует вам возможность воспользоваться залом в удобное для вас время!</p>			
			</td>
		</tr>
	</tbody>
</table>