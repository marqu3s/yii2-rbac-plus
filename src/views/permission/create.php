<?php

use marqu3s\rbacplus\models\AuthItem;
use yii\web\View;

/** @var View $this */
/** @var AuthItem $model */
?>
<br>
<div class="auth-item-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
