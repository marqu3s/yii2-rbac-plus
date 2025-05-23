<?php

namespace marqu3s\rbacplus\controllers;

use marqu3s\rbacplus\models\Role;
use marqu3s\rbacplus\models\RoleSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RoleController is controller for manager role
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class RoleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param string $name
     * @return mixed
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Creates a new Role model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Role(null);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => Yii::t('rbac', 'Create new {0}', ['Role']),
                    'content' => $this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button(Yii::t('rbac', 'Close'), [
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]) .
                        Html::button(Yii::t('rbac', 'Save'), [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                //                \johnitvn\userplus\Helper::dump($model);
                return [
                    'forceReload' => 'true',
                    'title' => Yii::t('rbac', 'Create new {0}', ['Role']),
                    'content' =>
                        '<span class="text-success">' .
                        Yii::t('rbac', 'Have been create new {0} success', ['Role']) .
                        '</span>',
                    'footer' =>
                        Html::button(Yii::t('rbac', 'Close'), [
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]) .
                        Html::a(
                            Yii::t('rbac', 'Create More'),
                            ['create'],
                            ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                        ),
                ];
            } else {
                return [
                    'title' => Yii::t('rbac', 'Create new {0}', ['Role']),
                    'content' => $this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button(Yii::t('rbac', 'Close'), [
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]) .
                        Html::button(Yii::t('rbac', 'Save'), [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Role model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($name);

        if ($request->isPost && $model->load($request->post())) {
            $model->validate();
            if ($model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Delete an existing Role model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @return mixed
     */
    public function actionDelete($name)
    {
        $request = Yii::$app->request;
        $this->findModel($name)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => 'true'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Role::find($name)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('rbac', 'The requested page does not exist.'));
        }
    }
}
