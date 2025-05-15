<?php

use common\components\Html;
use kartik\grid\ActionColumn;
use marqu3s\rbacplus\models\Permission;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;

$get = $_GET;

$allRoles = Yii::$app->authManager->getRoles();
$allRoles = ArrayHelper::map($allRoles, 'name', 'description');
asort($allRoles);

$allPermissions = Yii::$app->authManager->getPermissions();
$allPermissions = ArrayHelper::map($allPermissions, 'name', 'description');
asort($allPermissions);

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
        'encodeLabel' => false,
        'label' =>
            Yii::t('rbac', 'Roles') .
            ' <span title="' .
            Yii::t('rbac', 'Filter only direct roles') .
            '" data-toggle="tooltip" data-placement="bottom"><i class="glyphicon glyphicon-info-sign"></i></span>',
        'format' => 'html',
        'vAlign' => 'middle',
        'value' => function (IdentityInterface $model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $userId = $model->{$idField};
            $rolesList = $childRolesList = [];
            foreach ($authManager->getRolesByUser($userId) as $role) {
                $rolesList[$role->name] = $role->description;
            }
            foreach ($rolesList as $roleName => $roleDescription) {
                $childrenList = $authManager->getChildRoles($roleName);
                foreach ($childrenList as $childName => $childRole) {
                    if ($childName == $roleName) {
                        continue;
                    }
                    $childRolesList[$childName] = $childRole->description;
                }
            }
            $childRolesList = array_unique($childRolesList);
            asort($childRolesList);
            if (count($rolesList) == 0 && count($childRolesList) == 0) {
                return null;
            } else {
                $html = '';
                if (count($rolesList)) {
                    $html .=
                        '<small><strong>' .
                        Yii::t('rbac', 'Direct roles') .
                        '</strong></small><br>';
                    $html .= implode('<br>', $rolesList);
                }
                if (count($childRolesList)) {
                    $html .= '<br><br>';
                    $html .=
                        '<small><strong>' .
                        Yii::t('rbac', 'Inherited roles') .
                        '</strong></small><br>';
                    $html .= implode('<br>', $childRolesList);
                }
                return $html;
            }
        },
        'filter' => $allRoles,
        'width' => '250px',
    ],
    [
        'attribute' => 'permission',
        'encodeLabel' => false,
        'label' =>
            Yii::t('rbac', 'Permissions') .
            ' <span title="' .
            Yii::t('rbac', 'Filter only direct permissions') .
            '" data-toggle="tooltip" data-placement="bottom"><i class="glyphicon glyphicon-info-sign"></i></span>',
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function (IdentityInterface $model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $directPermissions = $inheritedPermissions = [];
            foreach (Permission::getDirectPermissionsByUser($model->{$idField}) as $permission) {
                $directPermissions[] = $permission->description;
            }
            $directPermissions = array_unique($directPermissions);
            sort($directPermissions);
            $roles = $authManager->getRolesByUser($model->{$idField});
            foreach ($roles as $role) {
                $permissionsFromRole = $authManager->getPermissionsByRole($role->name);
                foreach ($permissionsFromRole as $permission) {
                    $inheritedPermissions[] = $permission->description;
                }
            }
            $inheritedPermissions = array_unique($inheritedPermissions);
            sort($inheritedPermissions);
            $html = '';
            if (count($directPermissions) > 0) {
                $html .=
                    '<small><strong>' .
                    Yii::t('rbac', 'Direct permissions') .
                    '</strong></small><br>';
                if (count($directPermissions) > 3) {
                    $visible = array_slice($directPermissions, 0, 3);
                    $hidden = array_slice($directPermissions, 3);
                    $html .= implode('<br>', $visible);
                    $html .= Html::button(
                        Yii::t('rbac', 'Show {count} more', ['count' => count($hidden)]),
                        [
                            'class' => 'btn btn-link btn-xs btn-show-more',
                        ]
                    );
                    $html .= '<div style="display:none">' . implode('<br>', $hidden) . '</div>';
                } else {
                    $html .= implode('<br>', $directPermissions);
                }
            }
            if (count($inheritedPermissions) > 0) {
                $html .= count($directPermissions) > 0 ? '<br><br>' : '';
                $html .=
                    '<small><strong>' .
                    Yii::t('rbac', 'Inherited permissions from roles') .
                    '</strong></small><br>';
                if (count($inheritedPermissions) > 3) {
                    $visible = array_slice($inheritedPermissions, 0, 3);
                    $hidden = array_slice($inheritedPermissions, 3);
                    $html .= implode('<br>', $visible);
                    $html .= Html::button(
                        Yii::t('rbac', 'Show {count} more', ['count' => count($hidden)]),
                        [
                            'class' => 'btn btn-link btn-xs btn-show-more',
                        ]
                    );
                    $html .= '<div style="display:none">' . implode('<br>', $hidden) . '</div>';
                } else {
                    $html .= implode('<br>', $inheritedPermissions);
                }
            }
            return $html;
        },
        'filter' => $allPermissions,
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
