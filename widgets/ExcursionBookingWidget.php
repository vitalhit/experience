<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\models\Biblioevents;
use app\models\TicketCategories;
use app\models\Guide;
use app\models\Events;

/**
 * Виджет бронирования экскурсий.
 * Отображает календарь, сеансы, категории билетов и форму контактов.
 */
class ExcursionBookingWidget extends Widget
{
    /** @var int ID biblioevent */
    public $biblioevent_id;

    /** @var int ID landing (если передан — biblioevent_id берётся из biblioevents.landing_id) */
    public $landing_id;

    /** @var string|null Начальная выбранная дата Y-m-d */
    public $date = null;

    public $viewPath = '@app/widgets/views';

    private $biblioevent;
    private $ticketCategories;
    private $guides;
    private $availableDates = [];

    public function init()
    {
        parent::init();

        if (!$this->biblioevent_id && $this->landing_id) {
            $this->biblioevent_id = $this->getBiblioeventIdFromLanding();
        }

        $this->loadData();
    }

    private function loadData()
    {
        $this->biblioevent = Biblioevents::findOne($this->biblioevent_id);
        if (!$this->biblioevent) {
            return;
        }

        $this->ticketCategories = TicketCategories::find()
            ->where(['biblioevent_id' => $this->biblioevent_id, 'is_active' => 1])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $this->guides = Guide::find()
            ->innerJoin('biblioevent_guide', 'biblioevent_guide.guide_id = guide.id')
            ->where(['biblioevent_guide.biblioevent_id' => $this->biblioevent_id])
            ->orderBy(['biblioevent_guide.sort_order' => SORT_ASC])
            ->all();

        $maxDate = date('Y-m-d', strtotime('+1 year'));
        $dates = Events::find()
            ->select(['date'])
            ->where(['event_id' => $this->biblioevent_id, 'status' => 1])
            ->andWhere('DATE(date) >= DATE(NOW())')
            ->andWhere('DATE(date) <= :max', [':max' => $maxDate])
            ->orderBy(['date' => SORT_ASC])
            ->column();
        $this->availableDates = array_map(function ($d) {
            return date('Y-m-d', is_numeric($d) ? $d : strtotime($d));
        }, $dates);
        $this->availableDates = array_unique($this->availableDates);
    }

    public function run()
    {
        if (!$this->biblioevent) {
            return '';
        }

        return $this->render('booking', [
            'biblioevent' => $this->biblioevent,
            'ticketCategories' => $this->ticketCategories,
            'guides' => $this->guides,
            'selectedDate' => $this->date,
            'widgetId' => $this->getId(),
            'availableDates' => $this->availableDates,
        ]);
    }

    private function getBiblioeventIdFromLanding()
    {
        // Biblioevents имеет landing_id — ищем по нему
        $b = Biblioevents::find()
            ->where(['landing_id' => $this->landing_id])
            ->select('id')
            ->scalar();
        return $b ?: null;
    }
}
