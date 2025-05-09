<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '40px',
    ],
    [
        'attribute' => 'name',
        'label' => $searchModel->attributeLabels()['name'],
        'width' => '300px',
    ],
    [
        'attribute' => 'description',
        'label' => $searchModel->attributeLabels()['description'],
    ],
    [
        'attribute' => 'ruleName',
        'label' => $searchModel->attributeLabels()['ruleName'],
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
