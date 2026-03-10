<?php

use yii\db\Migration;

/**
 * Создаёт таблицу ticket_categories
 */
class m240311_100003_create_ticket_categories_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('ticket_categories', [
            'id' => $this->primaryKey(),
            'biblioevent_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'is_active' => $this->tinyInteger(1)->defaultValue(1),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-ticket_categories-biblioevent_id',
            'ticket_categories',
            'biblioevent_id',
            'biblioevents',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-ticket_categories-biblioevent_id', 'ticket_categories');
        $this->dropTable('ticket_categories');
    }
}
