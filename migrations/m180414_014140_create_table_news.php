<?php

use yii\db\Migration;

/**
 * Class m180414_014140_create_table_news
 */
class m180414_014140_create_table_news extends Migration
{
    protected $tableName = '{{%news}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->comment('Идентификатор'),
            'title' => $this->string(255)->comment('Заголовок'),
            'file' => $this->string(255)->comment('Полный путь к файлу'),
            'short' => $this->text()->comment('Краткий текст'),
            'full' => $this->text()->comment('Полный текст'),
            'is_active' => $this->boolean()->notNull()->defaultValue(0)->comment('Статус (активен, неактивен)'),
            'create_date' => $this->dateTime()->notNull()->defaultExpression('now()')->comment('Дата создания'),
            'creator_id' => $this->integer()->comment('Создатель'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
