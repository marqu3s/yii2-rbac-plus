<?php

namespace marqu3s\rbacplus\models;

use marqu3s\rbacplus\models\Permission;
use Yii;
use yii\base\Model;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class AssignmentForm extends Model
{
    public $authManager;
    public $userId;
    public $roles = [];
    public $permissions = [];

    /**
     *
     * @param mixed $userId The id of user use for assign
     * @param array $config
     */
    public function __construct($userId, $config = [])
    {
        parent::__construct($config);
        $this->userId = $userId;
        $this->authManager = Yii::$app->authManager;
        foreach ($this->authManager->getRolesByUser($userId) as $role) {
            $this->roles[] = $role->name;
        }
        $permissions = Permission::getDirectPermissionsByUser($userId);
        foreach ($permissions as $permission) {
            $this->permissions[] = $permission->name;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['userId'], 'required'], [['roles', 'permissions'], 'default']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('rbac', 'User ID'),
            'roles' => Yii::t('rbac', 'Roles'),
            'permissions' => Yii::t('rbac', 'Permissions'),
        ];
    }

    /**
     * Save assignment data
     * @return boolean whether assignment save success
     */
    public function save()
    {
        $this->authManager->revokeAll($this->userId);

        if (is_array($this->roles) && count($this->roles) > 0) {
            foreach ($this->roles as $role) {
                $this->authManager->assign($this->authManager->getRole($role), $this->userId);
            }
        }

        if (is_array($this->permissions) && count($this->permissions) > 0) {
            foreach ($this->permissions as $permission) {
                $this->authManager->assign(
                    $this->authManager->getPermission($permission),
                    $this->userId
                );
            }
        }

        return true;
    }
}
