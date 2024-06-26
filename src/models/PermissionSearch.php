<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\rbac\Item;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class PermissionSearch extends AuthItemSearch
{
    public function __construct($config = [])
    {
        parent::__construct($item = null, $config);
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Permission name');
        return $labels;
    }

    protected function getType()
    {
        return Item::TYPE_PERMISSION;
    }
}
