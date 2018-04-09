<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id Идентификатор
 * @property string $title Заголовок
 * @property string $file Полный путь к файлу
 * @property string $short Краткий текст
 * @property string $full Полный текст
 * @property int $is_active Статус (активен, неактивен)
 * @property string $create_date Дата создания
 * @property int $creator_id Создатель
 */
class News extends \yii\db\ActiveRecord
{
    public $fileInstance;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short', 'full'], 'string'],
            [['create_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['is_active'], 'boolean'],
            [['fileInstance'], 'file', 'extensions' => ['jpg', 'jpeg', 'png'], 'maxSize' => 2 * 1024 * 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'file' => Yii::t('app', 'File'),
            'short' => Yii::t('app', 'Short'),
            'full' => Yii::t('app', 'Full'),
            'is_active' => Yii::t('app', 'Is Active'),
            'create_date' => Yii::t('app', 'Create Date'),
            'creator_id' => Yii::t('app', 'Creator ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }

    /**
     * Автор новости
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result) {
            if ($this->isNewRecord) {
                $this->creator_id = \Yii::$app->user->id;
                $this->create_date = new Expression('now()');
            }
            if ($this->fileInstance instanceof UploadedFile) {
                $name = '/upload/' . bin2hex(random_bytes(16)) . '.' . $this->fileInstance->extension;
                $this->fileInstance->saveAs(\Yii::getAlias('@webroot' . $name));
                $this->file = $name;
            }
        }
        return $result;
    }
}
