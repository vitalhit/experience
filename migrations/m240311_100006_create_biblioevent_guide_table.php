<?php

use yii\db\Migration;

/**
 * Создаёт таблицу biblioevent_guide (связь M:N)
 */
class m240311_100006_create_biblioevent_guide_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('biblioevent_guide', [
            'biblioevent_id' => $this->integer()->notNull(),
            'guide_id' => $this->integer()->notNull(),
            'is_main' => $this->tinyInteger(1)->defaultValue(0),
            'sort_order' => $this->integer()->defaultValue(0),
        ]);

        $this->addPrimaryKey('pk-biblioevent_guide', 'biblioevent_guide', ['biblioevent_id', 'guide_id']);

        $this->addForeignKey(
            'fk-biblioevent_guide-biblioevent_id',
            'biblioevent_guide',
            'biblioevent_id',
            'biblioevents',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-biblioevent_guide-guide_id',
            'biblioevent_guide',
            'guide_id',
            'guide',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-biblioevent_guide-biblioevent_id', 'biblioevent_guide');
        $this->dropForeignKey('fk-biblioevent_guide-guide_id', 'biblioevent_guide');
        $this->dropPrimaryKey('pk-biblioevent_guide', 'biblioevent_guide');
        $this->dropTable('biblioevent_guide');
    }
}
