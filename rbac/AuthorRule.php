<?php

namespace app\rbac;

use yii\rbac\Rule;

/**
 * Class AuthorRule.
 * Правило для автора. Может редактировать только своё.
 *
 * @package app\rbac
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return ! empty($params['model']) ? $params['model']->creator_id == $user : false;
    }
}
