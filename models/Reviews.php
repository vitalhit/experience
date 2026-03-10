<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property int $event_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $photos
 * @property bool $verified
 * @property int $likes
 * @property int $replies_count
 * @property string $status
 *
 * @property Event $event
 * @property BiblioEvent $biblioevent
 * @property User $user
 */
class Review extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['biblioevent_id', 'event_id', 'user_id', 'rating'], 'required'],
            [['biblioevent_id', 'event_id', 'user_id', 'rating', 'likes', 'replies_count'], 'integer'],
            [['comment', 'photos'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['verified'], 'boolean'],
            [['status'], 'in', 'range' => ['pending', 'approved', 'rejected']],
            [['rating'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'biblioevent_id' => 'BiblioEvent ID',
            'event_id' => 'Event ID',
            'user_id' => 'User ID',
            'rating' => 'Rating',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'photos' => 'Photos',
            'verified' => 'Verified',
            'likes' => 'Likes',
            'replies_count' => 'Replies Count',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['id' => 'event_id']);
    }

    /**
     * Gets query for [[BiblioEvent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiblioEvent()
    {
        return $this->hasOne(BiblioEvent::class, ['id' => 'biblioevent_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}