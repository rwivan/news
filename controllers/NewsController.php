<?php

namespace app\controllers;

use app\helpers\SubscribeHelper;
use dektrium\user\filters\AccessRule;
use Yii;
use app\models\News;
use app\models\NewsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * @var News
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'active' => ['POST'],
                    'inactive' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['News.View'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['News.Create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['News.Update'],
                        'roleParams' => ['model' => $this->getNews()],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['active', 'inactive'],
                        'roles' => ['News.Active'],
                        'roleParams' => ['model' => $this->getNews()],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['News.Delete'],
                        'roleParams' => ['model' => $this->getNews()],
                    ],
                ],
            ],
        ];
    }

    public function getNews()
    {
        if (isset(\Yii::$app->request->queryParams['id'])) {
            return $this->findModel(\Yii::$app->request->queryParams['id']);
        }
        return null;
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post())) {
            $model->fileInstance = UploadedFile::getInstance($model, 'file');
            if ($model->save()) {
                SubscribeHelper::sendNews($model);
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->fileInstance = UploadedFile::getInstance($model, 'file');
            if ($model->save()) {
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActive($id)
    {
        $model = $this->findModel($id);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model->is_active = 1;
        if ($model->save()) {
            return [
                'success' => true,
                'is_active' => $model->is_active,
            ];
        }
        return ['success' => false];
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionInactive($id)
    {
        $model = $this->findModel($id);

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model->is_active = 0;
        if ($model->save()) {
            return [
                'success' => true,
                'is_active' => $model->is_active,
            ];
        }
        return ['success' => false];
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $condition = ['id' => $id];
        if (! \Yii::$app->user->can('News.View.All')) {
            if (\Yii::$app->user->can('News.View.Owner')) {
                $condition[] = [
                    'OR',
                    ['is_active' => 1],
                    ['creator_id' => \Yii::$app->user->id],
                ];
            } else {
                $condition['is_active'] = 1;
            }
        }

        if ($this->model === null) {
            $this->model = News::findOne($condition);
        }
        if($this->model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $this->model;
    }
}
