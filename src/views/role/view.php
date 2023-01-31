<?php

use marqu3s\rbacplus\models\Role;

$permissions = Role::getPermissions($model->name);
$roles = Role::getRoles($model->name);

$firstRole = '';
$rowsRoles = [];
foreach ($roles as $role) {
    if (empty($firstRole)) {
        $firstRole = $role->description;
    } else {
        $rowsRoles[] = '<tr><td>' . $role->description . '</td></tr>';
    }
}

$firstPermission = '';
$rowsPermissions = [];
foreach ($permissions as $permission) {
    if (empty($firstPermission)) {
        $firstPermission = $permission->description;
    } else {
        $rowsPermissions[] = '<tr><td>' . $permission->description . '</td></tr>';
    }
}
?>
<br>
<div class="permistion-item-view">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th><?= $model->attributeLabels()['name'] ?></th>
                <td><?= $model->name ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['description'] ?></th>
                <td><?= $model->description ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['ruleName'] ?></th>
                <td><?= $model->ruleName == null
                    ? '<span class="text-danger">' . Yii::t('rbac', '(not use)') . '</span>'
                    : $model->ruleName ?>
                </td>
            </tr>
            <tr>
                <th rowspan="<?= count($roles) > 0 ? count($roles) : 1 ?>" >
                    <?= $model->attributeLabels()['roles'] ?>
                </th>
                <td><?= $firstRole ?></td>
            </tr>
            <?= implode('', $rowsRoles) ?>
            <tr>
                <th rowspan="<?= count($permissions) > 0 ? count($permissions) : 1 ?>" >
                    <?= $model->attributeLabels()['permissions'] ?>
                </th>
                <td><?= $firstPermission ?></td>
            </tr>
            <?= implode('', $rowsPermissions) ?>
        </tbody>
    </table>
</div>
