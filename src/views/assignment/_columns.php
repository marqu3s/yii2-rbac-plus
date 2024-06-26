<?php

use yii\helpers\Url;

$columns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '40px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '70px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'login',
        'value' => function ($model) {
            $attr = Yii::$app->getModule('rbac')->userModelLoginField;
            return $model->$attr;
        },
        'vAlign' => 'middle',
        'width' => '250px',
    ],
    [
        'label' => 'Roles',
        'content' => function ($model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $roles = [];
            foreach ($authManager->getRolesByUser($model->{$idField}) as $role) {
                $roles[] = $role->name;
            }
            if (count($roles) == 0) {
                return Yii::t('yii', '(not set)');
            } else {
                return implode(', ', $roles);
            }
        },
    ],
    [
        'label' => 'All Permissions',
        'content' => function ($model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $roles = [];
            foreach ($authManager->getPermissionsByUser($model->{$idField}) as $role) {
                $roles[] = $role->name;
            }
            if (count($roles) == 0) {
                return Yii::t('yii', '(not set)');
            } else {
                return implode(', ', $roles);
            }
        },
    ],
];

$extraColums = \Yii::$app->getModule('rbac')->userModelExtraDataColumls;
if ($extraColums !== null) {
    // If extra colums exist merge and return
    $columns = array_merge($columns, $extraColums);
}
$columns[] = [
    'class' => 'kartik\grid\ActionColumn',
    'template' => '{update}',
    'header' => Yii::t('rbac', 'Assignment'),
    'dropdown' => false,
    'vAlign' => 'middle',
    'urlCreator' => function ($action, $model, $key, $index) {
        return Url::to(['assignment', 'id' => $key]);
    },
    'updateOptions' => [
        'role' => 'modal-remote',
        'title' => Yii::t('rbac', 'Update'),
        'data-toggle' => 'tooltip',
    ],
];
return $columns;
