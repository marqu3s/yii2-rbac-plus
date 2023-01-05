<?php

use marqu3s\rbacplus\models\Role;
use yii\web\View;

/** @var View $this */
/** @var Role $model */
?>
<br>
<div class="auth-item-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
