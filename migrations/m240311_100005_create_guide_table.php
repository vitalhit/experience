<?php

use yii\db\Migration;

/**
 * Создаёт таблицу guide
 */
class m240311_100005_create_guide_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('guide', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->notNull()->unique(),
            'description' => $this->text(),
            'photo' => $this->string(255),
            'rating' => $this->decimal(3, 2)->defaultValue(0.00),
            'experience_start_year' => $this->integer()->comment('Год начала работы'),
            'tours_count' => $this->integer()->defaultValue(0),
            'guides_count' => $this->integer()->defaultValue(1)->comment('Размер команды'),
            'is_active' => $this->tinyInteger(1)->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-guide-person_id',
            'guide',
            'person_id',
            'persons',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-guide-person_id', 'guide');
        $this->dropTable('guide');
    }
}
