<?php
/* @var $this yii\web\View */
/* @var $biblioevent_id int */
/* @var $date string|null */

use app\widgets\ExcursionBookingWidget;

echo ExcursionBookingWidget::widget([
    'biblioevent_id' => $biblioevent_id,
    'date' => $date,
]);
