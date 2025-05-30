<?php
use kartik\grid\GridView;
use marqu3s\rbacplus\models\RuleSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var RuleSearch $searchModel */
/** @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('rbac', 'Rules Manager');
$this->params['breadcrumbs'][] = $this->title;
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
