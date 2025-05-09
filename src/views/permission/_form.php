<?php
use marqu3s\rbacplus\models\Permission;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var Permission $model */

$rules = Yii::$app->authManager->getRules();
$rulesNames = array_keys($rules);
$rulesDatas = array_merge(
    ['' => Yii::t('rbac', '(not set)')],
    array_combine($rulesNames, $rulesNames)
);
?>
<div class="auth-item-form">
    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>

        <div class="well">
            <?= $form
                ->field($model, 'name')
                ->textInput(['maxlength' => true])
                ->hint(
                    Yii::t(
                        'rbac',
                        'A unique name for the permission. This is what you will use to check if a user has this permission.'
                    )
                ) ?>
            <?= $form
                ->field($model, 'description')
                ->textarea(['rows' => 2])
                ->hint(
                    Yii::t('rbac', 'A description of what this permission allows the user to do.')
                ) ?>
            <?= $form->field($model, 'ruleName')->dropDownList($rulesDatas) ?>
        </div>

        <?php if (!Yii::$app->request->isAjax): ?>
            <div class="form-group">
                <?= Html::a(Yii::t('rbac', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton(Yii::t('rbac', 'Save'), [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                ]) ?>
            </div>
        <?php endif; ?>

    <?php ActiveForm::end(); ?>
</div>
