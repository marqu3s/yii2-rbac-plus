<?php

use marqu3s\rbacplus\models\Permission;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Permission $model */

$title = Yii::t('rbac', 'Permission Item');
$this->title = $title;
?>
<div class="permission-item-view">
    <h3><?= $title ?></h3>
    
    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(
            Yii::t('rbac', 'Update'),
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
                <td>
                    <?= $model->ruleName == null
                        ? '<span class="text-muted">' . Yii::t('yii', '(not set)') . '</span>'
                        : $model->ruleName ?>
                </td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['data'] ?></th>
                <td>
                    <?= $model->data == null
                        ? '<span class="text-muted">' . Yii::t('yii', '(not set)') . '</span>'
                        : $model->data ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
