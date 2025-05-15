<?php

use common\components\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rbac\Item;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '40px',
    ],
    [
        'attribute' => 'name',
        'label' => $searchModel->attributeLabels()['name'],
        'vAlign' => 'middle',
        'width' => '250px',
    ],
    [
        'attribute' => 'description',
        'label' => $searchModel->attributeLabels()['description'],
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'childRoles',
        'label' => $searchModel->attributeLabels()['childRoles'],
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function ($model) {
            $am = Yii::$app->authManager;
            $childRoles = $am->getChildren($model->name);
            foreach ($childRoles as $i => $child) {
                if ($child->type != Item::TYPE_ROLE) {
                    unset($childRoles[$i]);
                }
            }
            $childRoles = ArrayHelper::getColumn($childRoles, 'description');
            $childRoles = array_unique($childRoles);
            asort($childRoles);
            if (count($childRoles) > 0) {
                if (count($childRoles) > 3) {
                    $visible = array_slice($childRoles, 0, 3);
                    $hidden = array_slice($childRoles, 3);
                    $html = implode('<br>', $visible);
                    $html .= Html::button(
                        Yii::t('rbac', 'Show {count} more', ['count' => count($hidden)]),
                        [
                            'class' => 'btn btn-link btn-xs btn-show-more',
                        ]
                    );
                    $html .= '<div style="display:none">' . implode('<br>', $hidden) . '</div>';
                    return $html;
                } else {
                    return implode('<br>', $childRoles);
                }
            } else {
                return null;
            }
        },
        'width' => '250px',
    ],
    [
        'attribute' => 'permissions',
        'label' => Yii::t('rbac', 'Associated Permissions'),
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function ($model) {
            $am = Yii::$app->authManager;
            $permissions = $am->getPermissionsByRole($model->name);
            $permissions = ArrayHelper::getColumn($permissions, 'description');
            $permissions = array_unique($permissions);
            asort($permissions);
            if (count($permissions) > 0) {
                if (count($permissions) > 3) {
                    $visible = array_slice($permissions, 0, 3);
                    $hidden = array_slice($permissions, 3);
                    $html = implode('<br>', $visible);
                    $html .= Html::button(
                        Yii::t('rbac', 'Show {count} more', ['count' => count($hidden)]),
                        [
                            'class' => 'btn btn-link btn-xs btn-show-more',
                        ]
                    );
                    $html .= '<div style="display:none">' . implode('<br>', $hidden) . '</div>';
                    return $html;
                } else {
                    return implode('<br>', $permissions);
                }
            } else {
                return null;
            }
        },
    ],
    [
        'attribute' => 'ruleName',
        'label' => $searchModel->attributeLabels()['ruleName'],
        'vAlign' => 'middle',
        'width' => '140px',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'name' => $key]);
        },
        'viewOptions' => [
            'title' => Yii::t('rbac', 'View'),
            'data-toggle' => 'tooltip',
        ],
        'updateOptions' => [
            'title' => Yii::t('rbac', 'Update'),
            'data-toggle' => 'tooltip',
        ],
        'deleteOptions' => [
            'title' => Yii::t('rbac', 'Delete'),
            'data-confirm' => Yii::t('rbac', 'Are you sure want to delete this item?'),
            'data-toggle' => 'tooltip',
        ],
    ],
];
