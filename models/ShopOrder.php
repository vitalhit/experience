<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property string $fio
 * @property string $phone
 * @property string $mail
 * @property string $address
 * @property string $items
 * @property string $comment
 * @property float $total
 * @property string $currency
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class ShopOrder extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const CURRENCY_RUB = 'rub';
    const CURRENCY_USD = 'usd';
    const CURRENCY_EUR = 'eur';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'phone', 'mail', 'total', 'currency'], 'required'],
            [['items', 'comment'], 'string'],
            [['total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['fio'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['mail'], 'string', 'max' => 100],
            [['mail'], 'email'],
            [['address'], 'string', 'max' => 500],
            [['currency'], 'string', 'max' => 3],
            [['status'], 'string', 'max' => 20],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_PROCESSING, self::STATUS_COMPLETED, self::STATUS_CANCELLED]],
            ['currency', 'in', 'range' => [self::CURRENCY_RUB, self::CURRENCY_USD, self::CURRENCY_EUR]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'mail' => 'Email',
            'address' => 'Адрес',
            'items' => 'Товары',
            'comment' => 'Комментарий',
            'total' => 'Сумма',
            'currency' => 'Валюта',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

   
    /**
     * Получить статус в читаемом формате
     */
    public function getStatusLabel()
    {
        $statuses = [
            self::STATUS_NEW => 'Новый',
            self::STATUS_PROCESSING => 'В обработке',
            self::STATUS_COMPLETED => 'Завершен',
            self::STATUS_CANCELLED => 'Отменен',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Получить валюту в читаемом формате
     */
    public function getCurrencyLabel()
    {
        $currencies = [
            self::CURRENCY_RUB => 'Рубль',
            self::CURRENCY_USD => 'Доллар',
            self::CURRENCY_EUR => 'Евро',
        ];
        
        return $currencies[$this->currency] ?? $this->currency;
    }

    public static function findByEmail($email)
    {
        return self::find()
            ->where(['mail' => $email])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }


}