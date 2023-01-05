<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\rbac\Item;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class Role extends AuthItem
{
    public $permissions = [];
    public $roles = [];

    public function init()
    {
        parent::init();

        if (!$this->isNewRecord) {
            foreach (static::getPermissions($this->item->name) as $permission) {
                $this->permissions[] = $permission->name;
            }

            foreach (static::getRoles($this->item->name) as $role) {
                $this->roles[] = $role->name;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [[['permissions', 'roles'], 'safe']]);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['default'][] = 'permissions';
        return $scenarios;
    }

    protected function getType()
    {
        return Item::TYPE_ROLE;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRole($this->item->name);
        if (!$insert) {
            $authManager->removeChildren($role);
        }

        if (is_array($this->permissions)) {
            foreach ($this->permissions as $permissionName) {
                $permission = $authManager->getPermission($permissionName);
                $authManager->addChild($role, $permission);
            }
        }

        if (is_array($this->roles)) {
            foreach ($this->roles as $roleName) {
                $role1 = $authManager->getRole($roleName);
                $authManager->addChild($role, $role1);
            }
        }
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Role name');
        $labels['permissions'] = Yii::t('rbac', 'Permissions');
        $labels['roles'] = Yii::t('rbac', 'Roles');
        return $labels;
    }

    public static function find($name)
    {
        $authManager = Yii::$app->authManager;
        $item = $authManager->getRole($name);
        return new self($item);
    }

    public static function getPermissions($name)
    {
        $authManager = Yii::$app->authManager;
        return $authManager->getPermissionsByRole($name);
    }

    public static function getRoles($name)
    {
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getChildRoles($name);
        foreach ($roles as $i => $role) {
            if ($role->name == $name) {
                unset($roles[$i]);
            }
        }

        return $roles;
    }
}
