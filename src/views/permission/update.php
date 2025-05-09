<?php

use marqu3s\rbacplus\models\Permission;
use yii\web\View;

/** @var View $this */
/** @var Permission $model */

$title = Yii::t('rbac', 'Update Permission Item');
$this->title = $title;
?>
<div class="auth-item-update">
    <h3><?= $title ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
