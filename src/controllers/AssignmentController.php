<?php

namespace marqu3s\rbacplus\controllers;

use marqu3s\rbacplus\models\AssignmentForm;
use marqu3s\rbacplus\models\AssignmentSearch;
use marqu3s\rbacplus\Module;
use Yii;
use yii\web\Controller;

/**
 * AssignmentController is controller for manager user assignment
 *
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class AssignmentController extends Controller
{
    /**
     * The current rbac module
     * @var Module $rbacModule
     */
    protected $rbacModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->rbacModule = Yii::$app->getModule('rbac');
    }

    /**
     * Show list of user for assignment.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AssignmentSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'idField' => $this->rbacModule->userModelIdField,
            'usernameField' => $this->rbacModule->userModelLoginField,
        ]);
    }

    /**
     * Assignment roles to user
     *
     * @param mixed $id The user id
     *
     * @return mixed
     */
    public function actionAssignment($id)
    {
        $model = call_user_func($this->rbacModule->userModelClassName . '::findOne', $id);
        $formModel = new AssignmentForm($id);
        $request = Yii::$app->request;

        if ($request->isPost) {
            $formModel->load(Yii::$app->request->post());
            $formModel->validate();

            if ($formModel->save()) {
                $params = ['index'];
                foreach (Yii::$app->request->get('AssignmentSearch', []) as $key => $value) {
                    $params['AssignmentSearch[' . $key . ']'] = $value;
                }

                return $this->redirect($params);
            }
        }

        return $this->render('assignment', [
            'model' => $model,
            'formModel' => $formModel,
        ]);
    }
}
