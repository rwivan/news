<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;
use yii\db\Expression;

/**
 * NewsSearch represents the model behind the search form of `app\models\News`.
 */
class NewsSearch extends News
{
    public $text;
    public $create_date_from;
    public $create_date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['is_active'], 'boolean'],
            [['title', 'text'], 'safe'],
            [['create_date_from', 'create_date_to'], 'date', 'format' => 'Y-m-d']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'create_date_from' => Yii::t('app', 'Create Date From'),
                'create_date_to' => Yii::t('app', 'Create Date To'),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (! \Yii::$app->user->can('News.View.All')) {
            if (\Yii::$app->user->can('News.View.Owner')) {
                $query->andWhere([
                    'OR',
                    ['is_active' => 1],
                    ['creator_id' => \Yii::$app->user->id],
                ]);
            } else {
                $query->andWhere('is_active = 1');
            }
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
            'id' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere([
                'OR',
                ['like', 'short', $this->text],
                ['like', 'full', $this->text],
            ]);

        if (! empty($this->create_date_from)) {
            $query->andFilterWhere([
                '>=', new Expression('date(create_date)'), $this->create_date_from,
            ]);
        }
        if (! empty($this->create_date_to)) {
            $query->andFilterWhere([
                '<', new Expression('date(create_date)'), $this->create_date_to,
            ]);
        }

        return $dataProvider;
    }
}
