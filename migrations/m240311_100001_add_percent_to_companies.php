<?php

use yii\db\Migration;

/**
 * Добавляет поле percent в companies (если отсутствует)
 */
class m240311_100001_add_percent_to_companies extends Migration
{
    public function safeUp()
    {
        $tableSchema = $this->db->getTableSchema('companies');
        if ($tableSchema && !isset($tableSchema->columns['percent'])) {
            $this->addColumn('companies', 'percent', $this->integer()->defaultValue(25));
        }
    }

    public function safeDown()
    {
        $tableSchema = $this->db->getTableSchema('companies');
        if ($tableSchema && isset($tableSchema->columns['percent'])) {
            $this->dropColumn('companies', 'percent');
        }
    }
}
