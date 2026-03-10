<?php

use yii\db\Migration;

/**
 * Добавляет поля для бронирования экскурсий в tickets
 */
class m240311_100007_add_booking_fields_to_tickets extends Migration
{
    public function safeUp()
    {
        $tableSchema = $this->db->getTableSchema('tickets');

        if ($tableSchema && !isset($tableSchema->columns['session_id'])) {
            $this->addColumn('tickets', 'session_id', $this->integer());
            $this->createIndex('idx-tickets-session_id', 'tickets', 'session_id');
        }
        if ($tableSchema && !isset($tableSchema->columns['ticket_category_id'])) {
            $this->addColumn('tickets', 'ticket_category_id', $this->integer());
        }
        if ($tableSchema && !isset($tableSchema->columns['quantity'])) {
            $this->addColumn('tickets', 'quantity', $this->integer()->defaultValue(1));
        }
        if ($tableSchema && !isset($tableSchema->columns['price'])) {
            $this->addColumn('tickets', 'price', $this->decimal(10, 2));
        }
        if ($tableSchema && !isset($tableSchema->columns['customer_name'])) {
            $this->addColumn('tickets', 'customer_name', $this->string(100));
        }
        if ($tableSchema && !isset($tableSchema->columns['customer_email'])) {
            $this->addColumn('tickets', 'customer_email', $this->string(100));
        }
        if ($tableSchema && !isset($tableSchema->columns['customer_phone'])) {
            $this->addColumn('tickets', 'customer_phone', $this->string(20));
        }
        if ($tableSchema && !isset($tableSchema->columns['comment'])) {
            $this->addColumn('tickets', 'comment', $this->text());
        }
        if ($tableSchema && !isset($tableSchema->columns['experience_order_id'])) {
            $this->addColumn('tickets', 'experience_order_id', $this->integer());
            $this->createIndex('idx-tickets-experience_order_id', 'tickets', 'experience_order_id');
            $this->addForeignKey(
                'fk-tickets-experience_order_id',
                'tickets',
                'experience_order_id',
                'experience_orders',
                'id'
            );
        }
    }

    public function safeDown()
    {
        $tableSchema = $this->db->getTableSchema('tickets');

        if ($tableSchema && isset($tableSchema->columns['experience_order_id'])) {
            $this->dropForeignKey('fk-tickets-experience_order_id', 'tickets');
            $this->dropIndex('idx-tickets-experience_order_id', 'tickets');
            $this->dropColumn('tickets', 'experience_order_id');
        }
        if ($tableSchema && isset($tableSchema->columns['comment'])) {
            $this->dropColumn('tickets', 'comment');
        }
        if ($tableSchema && isset($tableSchema->columns['customer_phone'])) {
            $this->dropColumn('tickets', 'customer_phone');
        }
        if ($tableSchema && isset($tableSchema->columns['customer_email'])) {
            $this->dropColumn('tickets', 'customer_email');
        }
        if ($tableSchema && isset($tableSchema->columns['customer_name'])) {
            $this->dropColumn('tickets', 'customer_name');
        }
        if ($tableSchema && isset($tableSchema->columns['price'])) {
            $this->dropColumn('tickets', 'price');
        }
        if ($tableSchema && isset($tableSchema->columns['quantity'])) {
            $this->dropColumn('tickets', 'quantity');
        }
        if ($tableSchema && isset($tableSchema->columns['ticket_category_id'])) {
            $this->dropColumn('tickets', 'ticket_category_id');
        }
        if ($tableSchema && isset($tableSchema->columns['session_id'])) {
            $this->dropIndex('idx-tickets-session_id', 'tickets');
            $this->dropColumn('tickets', 'session_id');
        }
    }
}
