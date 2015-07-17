<?php

namespace johnitvn\rbacplus\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Html;
use johnitvn\rbacplus\models\AssignmentSearch;
use johnitvn\rbacplus\models\AssignmentForm;

/**
 * Description of ManagerController
 *
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0.0
 * @property \johnitvn\rbacplus\Module $rbacModule
 */
class AssignmentController extends Controller {

    protected $rbacModule;

    public function init() {
        parent::init();
        $this->rbacModule = Yii::$app->getModule('rbac');
    }

    public function actionIndex() {
        $searchModel = new AssignmentSearch;
        $dataProvider = $searchModel->search();
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'idField' => $this->rbacModule->userModelIdField,
                    'usernameField' => $this->rbacModule->userModelLoginField,
        ]);
    }

    public function actionAssignment($id) {
        $model = call_user_func($this->rbacModule->userModelClassName . '::findOne', $id);
        $formModel = new AssignmentForm($id);
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isPost) {
                $formModel->load(Yii::$app->request->post());
                $formModel->save();
            }
            return [
                'title' => $model->{$this->rbacModule->userModelLoginField},
                'content' => $this->renderPartial('assignment', [
                    'model' => $model,
                    'formModel' => $formModel,
                ]),
                'footer' => Html::button(Yii::t('rbac', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button(Yii::t('rbac', 'Save'), ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            return $this->render('assignment', [
                        'model' => $model,
            ]);
        }
    }

}
