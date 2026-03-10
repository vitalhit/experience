<?php

use yii\db\Migration;

/**
 * Добавляет поле default_tickets_per_session в biblioevents
 */
class m240311_100000_add_default_tickets_to_biblioevents extends Migration
{
    public function safeUp()
    {
        $this->addColumn('biblioevents', 'default_tickets_per_session', $this->integer()->defaultValue(25));
    }

    public function safeDown()
    {
        $this->dropColumn('biblioevents', 'default_tickets_per_session');
    }
}
