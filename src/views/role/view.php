<?php

use marqu3s\rbacplus\models\Role;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Role $model */

$title = Yii::t('rbac', 'Role Item');
$this->title = $title;

$permissions = Role::getPermistions($model->name);
$first = '';
$rows = [];
foreach ($permissions as $permission) {
    if (empty($first)) {
        $first = $permission->name;
    } else {
        $rows[] = '<tr><td>' . $permission->name . '</td></tr>';
    }
}
?>
<div class="permission-item-view">
    <h3><?= $title ?></h3>

    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
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
                <td>
                    <?= $model->ruleName == null
                        ? '<span class="text-danger">' . Yii::t('yii', '(not use)') . '</span>'
                        : $model->ruleName ?>
                </td>
            </tr>
            <tr>
                <th rowspan="<?= count($permissions) ?>" >
                    <?= $model->attributeLabels()['permissions'] ?>
                </th>
                <td><?= $first ?></td>
            </tr>
            <?= implode('', $rows) ?>
        </tbody>
    </table>
</div>
