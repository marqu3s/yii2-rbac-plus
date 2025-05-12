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
    ['' => Yii::t('yii', '(not set)')],
    array_combine($rulesNames, $rulesNames)
);

$authManager = Yii::$app->authManager;
$roles = $authManager->getRoles();
ArrayHelper::multisort($roles, 'description', SORT_ASC);
$permissions = $authManager->getPermissions();
ArrayHelper::multisort($permissions, 'description', SORT_ASC);
?>

<div class="auth-item-form">
    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>
        <input type="hidden" name="Role[permissions]" value="">
        <input type="hidden" name="Role[childRoles]" value="">

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

        <h4>
            <?= Yii::t('rbac', 'Select the child roles for the role') ?>.
        </h4>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td style="width:1px"></td>
                    <td><b><?= Yii::t('rbac', 'Description') ?></b></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td>
                            <?= Html::checkbox(
                                'Role[childRoles][]',
                                in_array($role->description, $model->childRoles),
                                [
                                    'value' => $role->name,
                                ]
                            ) ?>
                        </td>
                        <td><?= $role->description ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <?= Html::a(Yii::t('rbac', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('rbac', 'Save'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            ]) ?>
        </div>

        <br><br>

        <h4>
            <?= Yii::t('rbac', 'Select the permissions for the role') ?>.
        </h4>
        
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
                            <?= Html::checkbox(
                                'Role[permissions][]',
                                in_array($permission->description, $model->permissions),
                                [
                                    'value' => $permission->name,
                                ]
                            ) ?>
                        </td>
                        <td><?= $permission->description ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 

        <div class="form-group">
            <?= Html::a(Yii::t('rbac', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('rbac', 'Save'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            ]) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
