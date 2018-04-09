<?php

namespace app\commands;

use dektrium\user\models\User;
use yii\base\Event;
use yii\console\Controller;
use yii\rbac\BaseManager;

class InitContorller extends Controller
{
    /**
     * Метод выполняет начальную подготовку данных.
     *
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        /** @var BaseManager $authManager*/
        $authManager = \Yii::$app->authManager;

        $authManager->removeAll();

        $admin = $authManager->createRole('admin');
        $admin->description = 'Админ';
        $authManager->add($admin);

        $editor = $authManager->createRole('editor');
        $editor->description = 'Редактор';
        $authManager->add($editor);

        $reader = $authManager->createRole('reader');
        $reader->description = 'Читатель';
        $authManager->add($reader);

        $authorRule = new \app\rbac\AuthorRule();
        $authManager->add($authorRule);

        $newsCreate = $authManager->createPermission('News.Create');
        $newsCreate->description = 'Создание новости';
        $authManager->add($newsCreate);

        $newsUpdate = $authManager->createPermission('News.Update');
        $newsUpdate->description = 'Редактирование новости';
        $authManager->add($newsUpdate);

        $newsUpdateOwner = $authManager->createPermission('News.Update.Owner');
        $newsUpdateOwner->description = 'Редактирование собственной новости';
        $newsUpdateOwner->ruleName = $authorRule->name;
        $authManager->add($newsUpdateOwner);
        $authManager->addChild($newsUpdateOwner, $newsUpdate);

        $newsActive = $authManager->createPermission('News.Active');
        $newsActive->description = 'Активировать новости';
        $authManager->add($newsActive);

        $newsActiveOwner = $authManager->createPermission('News.Active.Owner');
        $newsActiveOwner->description = 'Активировать собственые новости';
        $newsActiveOwner->ruleName = $authorRule->name;
        $authManager->add($newsActiveOwner);
        $authManager->addChild($newsActiveOwner, $newsActive);

        $newsDelete = $authManager->createPermission('News.Delete');
        $newsDelete->description = 'Удаление новости';
        $authManager->add($newsDelete);

        $newsDeleteOwner = $authManager->createPermission('News.Delete.Owner');
        $newsDeleteOwner->description = 'Удаление собственной новости';
        $newsDeleteOwner->ruleName = $authorRule->name;
        $authManager->add($newsDeleteOwner);
        $authManager->addChild($newsDeleteOwner, $newsDelete);

        $newsView = $authManager->createPermission('News.View');
        $newsView->description = 'Просмотр новости';
        $authManager->add($newsView);

        $newsViewAll = $authManager->createPermission('News.View.All');
        $newsViewAll->description = 'Может видеть заблокированные новости';
        $authManager->add($newsViewAll);

        $newsViewOwner = $authManager->createPermission('News.View.Owner');
        $newsViewOwner->description = 'Может видеть свои заблокированные новости';
        $authManager->add($newsViewOwner);

        $authManager->addChild($admin, $newsCreate);
        $authManager->addChild($admin, $newsUpdate);
        $authManager->addChild($admin, $newsActive);
        $authManager->addChild($admin, $newsDelete);
        $authManager->addChild($admin, $newsView);
        $authManager->addChild($admin, $newsViewAll);

        $authManager->addChild($editor, $newsCreate);
        $authManager->addChild($editor, $newsUpdateOwner);
        $authManager->addChild($editor, $newsActiveOwner);
        $authManager->addChild($editor, $newsDeleteOwner);
        $authManager->addChild($editor, $newsView);
        $authManager->addChild($editor, $newsViewOwner);

        $authManager->addChild($reader, $newsView);

        $userAdmin = $authManager->createPermission('User.Admin');
        $userAdmin->description = 'Управление пользователями';
        $authManager->add($userAdmin);
        $authManager->addChild($admin, $userAdmin);

        Event::on(User::class, User::AFTER_CREATE, function(Event $event) use ($authManager, $admin) {
            $authManager->assign($admin, $event->sender->id);
        });

        \Yii::$app->runAction('user/delete', ['interactive' => false, 'admin']);
        \Yii::$app->runAction('user/create', ['admin@news.localhost', 'admin', '123456']);

        Event::off(User::class, User::AFTER_CREATE);
        Event::on(User::class, User::AFTER_CREATE, function(Event $event) use ($authManager, $editor) {
            $authManager->assign($editor, $event->sender->id);
        });

        \Yii::$app->runAction('user/delete', ['interactive' => false, 'editor']);
        \Yii::$app->runAction('user/create', ['editor@news.localhost', 'editor', '123456']);

        Event::off(User::class, User::AFTER_CREATE);
        Event::on(User::class, User::AFTER_CREATE, function(Event $event) use ($authManager, $reader) {
            $authManager->assign($reader, $event->sender->id);
        });

        \Yii::$app->runAction('user/delete', ['interactive' => false, 'reader']);
        \Yii::$app->runAction('user/create', ['reader@news.localhost', 'reader', '123456']);

        $this->off(User::AFTER_CREATE);
    }
}
