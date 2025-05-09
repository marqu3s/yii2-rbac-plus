<?php

use kartik\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;

$get = $_GET;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '40px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'login',
        'label' => Yii::$app->getModule('rbac')->userModelLoginFieldLabel,
        'value' => function (IdentityInterface $model) {
            $attr = Yii::$app->getModule('rbac')->userModelLoginField;
            return $model->$attr;
        },
        'vAlign' => 'middle',
        'width' => '180px',
    ],
    [
        'attribute' => 'role',
        'label' => 'Roles',
        'format' => 'html',
        'vAlign' => 'middle',
        'value' => function (IdentityInterface $model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $roles = [];
            foreach ($authManager->getRolesByUser($model->{$idField}) as $role) {
                $roles[] = $role->description;
            }
            if (count($roles) == 0) {
                return null;
            } else {
                return implode('<br>', $roles);
            }
        },
        'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
        'width' => '250px',
    ],
    [
        'label' => Yii::t(
            'rbac',
            'Permissions inherited from roles or attached directly to the user'
        ),
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function (IdentityInterface $model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $roles = [];
            foreach ($authManager->getPermissionsByUser($model->{$idField}) as $role) {
                $roles[] = $role->description;
            }
            if (count($roles) == 0) {
                return null;
            }
            if (count($roles) > 3) {
                $visibleRoles = array_slice($roles, 0, 3);
                $hiddenRoles = array_slice($roles, 3);

                $html = implode('<br>', $visibleRoles);
                $html .=
                    '<br><button type="button" class="btn btn-link btn-xs" onclick="$(this).next().toggle(); $(this).hide();">' .
                    Yii::t('rbac', 'Show {count} more', ['count' => count($hiddenRoles)]) .
                    '</button>';
                $html .= '<div style="display:none">' . implode('<br>', $hiddenRoles) . '</div>';
                return $html;
            }
            return implode('<br>', $roles);
        },
        'width' => '350px',
    ],
    [
        'class' => '\kartik\grid\BooleanColumn',
        'attribute' => 'userActive',
        'label' => Yii::$app->getModule('rbac')->userModelActiveFieldLabel,
        'value' => function (IdentityInterface $model) {
            $activeField = Yii::$app->getModule('rbac')->userModelActiveField;
            return $model->{$activeField};
        },
        'filter' => Yii::$app->getModule('rbac')->userModelActiveFieldFilterOptions,
        'vAlign' => 'middle',
        'width' => '90px',
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update}',
        'header' => Yii::t('rbac', 'Actions'),
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            $params = ['assignment'];
            $params['id'] = $key;
            foreach (Yii::$app->request->get('AssignmentSearch', []) as $key => $value) {
                $params['AssignmentSearch[' . $key . ']'] = $value;
            }
            return Url::to($params);
        },
        'updateOptions' => [
            'title' => Yii::t('rbac', 'Update'),
            'data-toggle' => 'tooltip',
        ],
        'width' => '80px',
    ],
];
