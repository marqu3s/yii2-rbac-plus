<?php

use marqu3s\rbacplus\models\Role;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var Role $model */

$rules = Yii::$app->authManager->getRules();
$rulesNames = array_keys($rules);
$rulesDatas = array_merge(
    ['' => Yii::t('rbac', '(not set)')],
    array_combine($rulesNames, $rulesNames)
);

$authManager = Yii::$app->authManager;
$permissions = $authManager->getPermissions();
ArrayHelper::multisort($permissions, 'description', SORT_ASC);
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
                        'A unique name for the role. This is what you will use to check if a user has this role.'
                    )
                ) ?>
            <?= $form
                ->field($model, 'description')
                ->textarea(['rows' => 2])
                ->hint(Yii::t('rbac', 'A description about this role.')) ?>
            <?= $form->field($model, 'ruleName')->dropDownList($rulesDatas) ?>
        </div>

        <div class="form-group field-role-permissions">
            <label class="control-label" for="role-permissions">
                <?= Yii::t('rbac', 'Permissions for this role') ?>
            </label>
            <input type="hidden" name="Role[permissions]" value="">
            <div id="role-permissions">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td style="width:1px"></td>
                            <td><b><?= Yii::t('rbac', 'Description') ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $permission): ?>
                            <tr>
                                <td>
                                    <input <?= in_array(
                                        $permission->name,
                                        (array) $model->permissions
                                    )
                                        ? 'checked'
                                        : '' ?> type="checkbox" name="Role[permissions][]" value="<?= $permission->name ?>">
                                </td>
                                <td><?= $permission->description ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>           
            </div>
            <div class="help-block"></div>        
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::a(Yii::t('rbac', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton(Yii::t('rbac', 'Save'), [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                ]) ?>
            </div>
        <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
