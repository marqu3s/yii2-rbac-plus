<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\rbac\Item;

/**
 * Description of Permistion
 *
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class Permission extends AuthItem
{
    public $permissions = [];

    public function init()
    {
        parent::init();

        if (!$this->isNewRecord) {
            foreach (static::getPermissions($this->item->name) as $permission) {
                $this->permissions[] = $permission->name;
            }
        }
    }

    protected function getType()
    {
        return Item::TYPE_PERMISSION;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Permission name');
        return $labels;
    }

    public static function find($name)
    {
        $authManager = Yii::$app->authManager;
        $item = $authManager->getPermission($name);
        return new self($item);
    }

    public static function getPermissions($name)
    {
        $authManager = Yii::$app->authManager;
        return $authManager->getPermissionsByRole($name);
    }
}
