<?php

use marqu3s\rbacplus\models\Role;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Role $model */

$title = Yii::t('rbac', 'Role Item');
$this->title = $title;

$firstChildRole = '';
$rowsChildRole = [];
foreach ($model->childRoles as $childRole) {
    if (empty($firstChildRole)) {
        $firstChildRole = $childRole;
    } else {
        $rowsChildRole[] = '<tr><td>' . $childRole . '</td></tr>';
    }
}

$firstPermission = '';
$rowsPermission = [];
foreach ($model->permissions as $permission) {
    if (empty($firstPermission)) {
        $firstPermission = $permission;
    } else {
        $rowsPermission[] = '<tr><td>' . $permission . '</td></tr>';
    }
}
?>
<div class="permission-item-view">
    <h3><?= $title ?></h3>

    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(
            Yii::t('rbac', 'Edit'),
            ['update', 'name' => $model->name],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th style="width: 25%"><?= $model->attributeLabels()['name'] ?></th>
                <td><?= $model->name ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['description'] ?></th>
                <td><?= $model->description ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['ruleName'] ?></th>
                <td><?= $model->ruleName ?></td>
            </tr>
            <tr>
                <th rowspan="<?= count($model->childRoles) > 0 ? count($model->childRoles) : 1 ?>">
                    <?= $model->attributeLabels()['childRoles'] ?>
                </th>
                <td><?= $firstChildRole ?></td>
            </tr>
            <?= implode('', $rowsChildRole) ?>
            <tr>
                <th rowspan="<?= count($model->permissions) > 0
                    ? count($model->permissions)
                    : 1 ?>">
                    <?= $model->attributeLabels()['permissions'] ?>
                </th>
                <td><?= $firstPermission ?></td>
            </tr>
            <?= implode('', $rowsPermission) ?>
        </tbody>
    </table>
</div>
