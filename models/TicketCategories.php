<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "ticket_categories".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property string $name
 * @property float $price
 * @property int $is_active
 * @property int $sort_order
 * @property string $created_at
 *
 * @property Biblioevents $biblioevent
 * @property Tickets[] $tickets
 */
class TicketCategories extends ActiveRecord
{
    public static function tableName()
    {
        return 'ticket_categories';
    }

    public function rules()
    {
        return [
            [['biblioevent_id', 'name', 'price'], 'required'],
            [['biblioevent_id', 'is_active', 'sort_order'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['biblioevent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Biblioevents::className(), 'targetAttribute' => ['biblioevent_id' => 'id']],
        ];
    }

    public function getBiblioevent()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'biblioevent_id']);
    }

    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['ticket_category_id' => 'id']);
    }
}
