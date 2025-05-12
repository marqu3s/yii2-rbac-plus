<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\rbac\Item;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class RoleSearch extends AuthItemSearch
{
    public function __construct($config = [])
    {
        parent::__construct($item = null, $config);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Role name');
        $labels['permissions'] = Yii::t('rbac', 'Permissions');
        $labels['childRoles'] = Yii::t('rbac', 'Child roles');
        return $labels;
    }

    /**
     * @inheritdoc
     */
    protected function getType()
    {
        return Item::TYPE_ROLE;
    }
}
