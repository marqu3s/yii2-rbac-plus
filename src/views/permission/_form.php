<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$rules = Yii::$app->authManager->getRules();
$rulesNames = array_keys($rules);
$rulesDatas = array_merge(
    ['' => Yii::t('rbac', '(not use)')],
    array_combine($rulesNames, $rulesNames)
);

$authManager = Yii::$app->authManager;
$permissions = $authManager->getPermissions();
?>
<div class="auth-item-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 1]) ?>
        <?= $form->field($model, 'ruleName')->dropDownList($rulesDatas) ?>

        <div class="form-group field-role-permissions">
            <label class="control-label" for="role-permissions">Permissions</label>
            <input type="hidden" name="Role[permissions]" value="">
            <div id="role-permissions">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 20px;"></th>
                            <th style="width:240px">Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $permission): ?>
                            <?php if ($permission->name === $model->name) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <input <?= in_array(
                                        $permission->name,
                                        (array) $model->permissions
                                    )
                                        ? 'checked'
                                        : '' ?> type="checkbox" name="Role[permissions][]" value="<?= $permission->name ?>">
                                </td>
                                <td><?= $permission->name ?></td>
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
