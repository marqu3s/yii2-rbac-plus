<?php

use kartik\grid\GridView;
use marqu3s\rbacplus\models\AssignmentSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var AssignmentSearch $searchModel */
/** @var string $idField */
/** @var string $usernameField */

$this->title = Yii::t('rbac', 'User Roles Assignment');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">
    <?= GridView::widget([
        'id' => 'crud-datatable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => require __DIR__ . '/_columns.php',
        'pjax' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'toggleDataOptions' => [
            'all' => [
                'icon' => 'resize-full',
                'class' => 'btn btn-default',
                'label' => Yii::t('rbac', 'All'),
                'title' => Yii::t('rbac', 'Show all data'),
            ],
            'page' => [
                'icon' => 'resize-small',
                'class' => 'btn btn-default',
                'label' => Yii::t('rbac', 'Page'),
                'title' => Yii::t('rbac', 'Show first page data'),
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        [''],
                        [
                            'data-pjax' => 1,
                            'class' => 'btn btn-default',
                            'title' => Yii::t('rbac', 'Reload Grid'),
                        ]
                    ) . '{toggleData}',
            ],
        ],
        'panel' => [
            'type' => 'primary',
            'heading' => '<i class="glyphicon glyphicon-list"></i> ' . $this->title,
            'before' =>
                '<em>' .
                Yii::t(
                    'rbac',
                    '* Resize table columns just like a spreadsheet by dragging the column edges.'
                ) .
                '</em>',
            'after' => false,
        ],
    ]) ?>
</div>
