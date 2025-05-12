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
    public $childRoles = [];

    public function init()
    {
        parent::init();

        if (!$this->isNewRecord) {
            $permissions = [];
            foreach (static::getPermissions($this->item->name) as $permission) {
                $permissions[] = $permission->description;
            }

            sort($permissions);
            $this->permissions = $permissions;

            $childRoles = [];
            foreach (static::getChildRoles($this->item->name) as $childRole) {
                if ($childRole->type == Item::TYPE_PERMISSION) {
                    continue;
                }

                $childRoles[] = $childRole->description;
            }

            sort($childRoles);
            $this->childRoles = $childRoles;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [[['permissions', 'childRoles'], 'safe']]);
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

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Role name');
        $labels['permissions'] = Yii::t('rbac', 'Permissions');
        $labels['childRoles'] = Yii::t('rbac', 'Child roles');
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

    public static function getChildRoles($name)
    {
        $authManager = Yii::$app->authManager;
        $children = $authManager->getChildren($name);
        $roles = [];
        foreach ($children as $child) {
            if ($child->type == Item::TYPE_ROLE) {
                $roles[] = $child;
            }
        }

        return $roles;
    }
}
