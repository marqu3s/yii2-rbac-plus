<?php

use marqu3s\rbacplus\models\AuthItem;
use yii\web\View;

/** @var View $this */
/** @var AuthItem $model */
?>
<div class="auth-item-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
