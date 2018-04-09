<?php

use dektrium\user\models\Profile;
use yii\db\Migration;

/**
 * Class m180414_014427_add_extra_fields_for_profile
 */
class m180414_014427_add_extra_fields_for_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Profile::tableName(), 'send_email', $this->boolean()->defaultValue(true)->notNull());
        $this->addColumn(Profile::tableName(), 'send_web', $this->boolean()->defaultValue(true)->notNull());
        $this->addColumn(Profile::tableName(), 'lasted_news_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Profile::tableName(), 'send_email');
        $this->dropColumn(Profile::tableName(), 'send_web');
        $this->dropColumn(Profile::tableName(), 'lasted_news_date');
    }
}
