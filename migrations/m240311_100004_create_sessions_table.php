<?php

use yii\db\Migration;

/**
 * Создаёт таблицу sessions
 */
class m240311_100004_create_sessions_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('sessions', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'time_start' => $this->time()->notNull(),
            'time_end' => $this->time()->notNull(),
            'max_tickets' => $this->integer()->notNull()->defaultValue(25),
            'booked_tickets' => $this->integer()->notNull()->defaultValue(0),
            'price_multiplier' => $this->decimal(3, 2)->defaultValue(1.00),
            'last_calc_at' => $this->timestamp()->null(),
            'status' => $this->tinyInteger(1)->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-sessions-event_id',
            'sessions',
            'event_id',
            'events',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-sessions-event_id', 'sessions');
        $this->dropTable('sessions');
    }
}
