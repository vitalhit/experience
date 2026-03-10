<?php

use yii\db\Migration;

/**
 * Создаёт таблицу experience_orders
 */
class m240311_100002_create_experience_orders_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('experience_orders', [
            'id' => $this->primaryKey(),
            'order_number' => $this->string(20)->notNull()->unique(),
            'company_id' => $this->integer()->notNull(),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'prepayment_amount' => $this->decimal(10, 2)->notNull(),
            'prepayment_percent' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-experience_orders-company_id',
            'experience_orders',
            'company_id',
            'companies',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-experience_orders-company_id', 'experience_orders');
        $this->dropTable('experience_orders');
    }
}
