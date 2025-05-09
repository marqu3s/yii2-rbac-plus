<?php

use marqu3s\rbacplus\models\Role;
use yii\web\View;

/** @var View $this */
/** @var Role $model */

$title = Yii::t('rbac', 'Update Role Item');
$this->title = $title;
?>
<div class="auth-item-update">
    <h3><?= $title ?></h3>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
