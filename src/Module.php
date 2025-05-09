<?php

namespace marqu3s\rbacplus;

use Yii;
use yii\base\Module as BaseModule;

/**
 * Description of Module
 *
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class Module extends BaseModule
{
    /**
     * @var string $userModelClassName The user model class.
     * Default it will get from `Yii::$app->getUser()->identityClass`
     */
    public $userModelClassName;

    /**
     * @var string $userModelIdField the id field name of user model.
     * Default is id
     */
    public $userModelIdField = 'id';

    /**
     * @var string $userModelLoginField the login field name of user model.
     * Default is username
     */
    public $userModelLoginField = 'username';

    /**
     * @var string $userModelActiveField the active field name of user model.
     * Default is active
     */
    public $userModelActiveField = 'active';

    /**
     * @var array $userModelActiveFieldFilterOptions the array of active field's filter options of user model.
     * Default is [1 => 'Active', 0 => 'Inactive']
     */
    public $userModelActiveFieldFilterOptions = [1 => 'Active', 0 => 'Inactive'];

    /**
     * @var string $userModelLoginFieldLabel The login field's label of user model.
     * Default is Username
     */
    public $userModelLoginFieldLabel = 'Username';

    /**
     * @var string $userModelActiveFieldLabel The active field's label of user model.
     * Default is User Active
     */
    public $userModelActiveFieldLabel = 'User Active';

    /**
     * @var array|null $userModelExtraDataColumns the array of extra columns of user model want to
     * show in assignment index view.
     */
    // public $userModelExtraDataColumns;

    /**
     * Callback before create controller
     * @var callable
     */
    public $beforeCreateController = null;

    /**
     * Callback before create action
     * @var callable
     */
    public $beforeAction = null;

    /**
     * Initilation module
     * @return void
     */
    public function init()
    {
        parent::init();

        if ($this->userModelClassName == null) {
            if (Yii::$app->has('user')) {
                $this->userModelClassName = Yii::$app->user->identityClass;
            } else {
                throw new yii\base\Exception(
                    'You must config user compoment both console and web config'
                );
            }
        }

        if ($this->userModelLoginFieldLabel == null) {
            $model = new $this->userModelClassName();
            $this->userModelLoginFieldLabel = $model->getAttributeLabel($this->userModelLoginField);
        }

        if ($this->userModelActiveFieldLabel == null) {
            $model = new $this->userModelClassName();
            $this->userModelActiveFieldLabel = $model->getAttributeLabel(
                $this->userModelActiveField
            );
        }
    }

    public function createController($route)
    {
        if (
            $this->beforeCreateController !== null &&
            !call_user_func($this->beforeCreateController, $route)
        ) {
            return false;
        }
        return parent::createController($route);
    }

    public function beforeAction($action)
    {
        if ($this->beforeAction !== null && !call_user_func($this->beforeAction, $action)) {
            return false;
        }

        return parent::beforeAction($action);
    }
}
