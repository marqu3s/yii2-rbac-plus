<?php
use kartik\grid\GridView;
use marqu3s\rbacplus\models\RoleSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var RoleSearch $searchModel */
/** @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('rbac', 'Roles Manager');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
    $('body').on('click', '.btn-show-more', function() {
        $(this).next().toggle(); $(this).hide();
    });
JS;
$this->registerJs($js);
?>
<div class="auth-item-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require __DIR__ . '/_columns.php',
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
                            Yii::t('rbac', 'Permissions'),
                            ['permission/index'],
                            [
                                'title' => Yii::t('rbac', 'Permissions Management'),
                                'class' => 'btn btn-default',
                                'data-pjax' => 0,
                            ]
                        ) .
                        Html::a(
                            Yii::t('rbac', 'Assignments'),
                            ['assignment/index'],
                            [
                                'title' => Yii::t('rbac', 'Assignments Management'),
                                'class' => 'btn btn-default',
                                'data-pjax' => 0,
                            ]
                        ) .
                        Html::a(
                            '<i class="glyphicon glyphicon-plus"></i>',
                            ['create'],
                            [
                                'role' => 'modal-remote',
                                'title' => Yii::t('rbac', 'Create new rule'),
                                'class' => 'btn btn-default',
                                'data-pjax' => 0,
                            ]
                        ) .
                        Html::a(
                            '<i class="glyphicon glyphicon-repeat"></i>',
                            [''],
                            [
                                'data-pjax' => 1,
                                'class' => 'btn btn-default',
                                'title' => Yii::t('rbac', 'Reload Grid'),
                            ]
                        ) .
                        '{toggleData}' .
                        '{export}',
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
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
</div>
