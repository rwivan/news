<?php

namespace app\controllers;

use app\models\User;
use dektrium\user\controllers\AdminController as AdminControllerBase;
use dektrium\user\Mailer;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Class AdminController.
 * Контроллер для управления пользоватями.
 *
 * @package app\controllers
 */
class AdminController extends AdminControllerBase
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'          => ['post'],
                    'confirm'         => ['post'],
                    'resend-password' => ['post'],
                    'block'           => ['post'],
                    'switch'          => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['User.Admin'],
                    ],
                ],
            ],
        ];
    }

    public function handlerChangePassword(AfterSaveEvent $event)
    {
        if (isset($event->changedAttributes['password_hash'])) {
            \Yii::$container->get(Mailer::class)
                ->sendGeneratedPassword($event->sender, $event->sender->password);
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_BEFORE_UPDATE, function(Event $event){
            Event::on(User::class, User::EVENT_AFTER_UPDATE, [$this, 'handlerChangePassword']);
        });
        $this->on(self::EVENT_AFTER_UPDATE, function(Event $event){
            Event::off(User::class, User::EVENT_AFTER_UPDATE, [$this, 'handlerChangePassword']);
        });
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);

        if ($user->load(\Yii::$app->request->post())) {
            $this->trigger(self::EVENT_BEFORE_CREATE, $event);
            if ($user->createByAdmin()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
                $this->trigger(self::EVENT_AFTER_CREATE, $event);
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                } else {
                    return $this->redirect(['update', 'id' => $user->id]);
                }
            }
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'user' => $user,
            ]);
        } else {
            return $this->render('create', [
                'user' => $user,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $event = $this->getUserEvent($user);

        if ($user->load(\Yii::$app->request->post())) {
            $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
            if ($user->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
                $this->trigger(self::EVENT_AFTER_UPDATE, $event);
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                } else {
                    return $this->refresh();
                }
            }
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'user' => $user,
            ]);
        } else {
            return $this->render('_account', [
                'user' => $user,
            ]);
        }
    }
}
