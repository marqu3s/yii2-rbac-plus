<?php
use marqu3s\rbacplus\models\AssignmentForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var AssignmentForm $formModel */

$title = Yii::t('rbac', 'User Roles Assignment');
$this->title = $title;

/** Get the auth manager */
$authManager = Yii::$app->authManager;
$roles = $authManager->getRoles();
$permissions = $authManager->getPermissions();

$backUrl = ['index'];
foreach (Yii::$app->request->get('AssignmentSearch', []) as $key => $value) {
    $backUrl['AssignmentSearch[' . $key . ']'] = $value;
}
?>
<div class="user-assignment-form">
    <h3><?= $title ?></h3>
    
    <div class="form-group">
        <?= Html::a(Yii::t('rbac', 'Back'), $backUrl, ['class' => 'btn btn-default']) ?>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>
        <?= Html::activeHiddenInput($formModel, 'userId') ?>
        <input type="hidden" name="AssignmentForm[roles]" value="">
        <input type="hidden" name="AssignmentForm[permissions]" value="">
        <br>
        
        <h4>
            <?= Yii::t('rbac', 'Select the roles for the user') ?>.
        </h4>
        
        <table class="table table-striped table-bordered detail-view">
            <thead>
                <tr>
                    <th style="width:1px"></th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>            
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <?php
                        $checked = true;
                        if (
                            $formModel->roles == null ||
                            !is_array($formModel->roles) ||
                            count($formModel->roles) == 0
                        ) {
                            $checked = false;
                        } elseif (!in_array($role->name, $formModel->roles)) {
                            $checked = false;
                        }
                        ?>
                        <td>
                            <?= Html::checkbox('AssignmentForm[roles][]', $checked, [
                                'value' => $role->name,
                            ]) ?>
                        </td>
                        <td><?= $role->description ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <?= Html::a(Yii::t('rbac', 'Cancel'), $backUrl, ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <br><br>

        <h4>
            <?= Yii::t('rbac', 'Select the permissions for the user') ?>.
        </h4>
        
        <table class="table table-striped table-bordered detail-view">
            <thead>
                <tr>
                    <th style="width:1px"></th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>            
                <?php foreach ($permissions as $permission): ?>
                    <tr>
                        <?php
                        $checked = true;
                        if (
                            $formModel->permissions == null ||
                            !is_array($formModel->permissions) ||
                            count($formModel->permissions) == 0
                        ) {
                            $checked = false;
                        } elseif (!in_array($permission->name, $formModel->permissions)) {
                            $checked = false;
                        }
                        ?>
                        <td>
                            <?= Html::checkbox('AssignmentForm[permissions][]', $checked, [
                                'value' => $permission->name,
                            ]) ?>
                        </td>
                        <td><?= $permission->description ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <?= Html::a(Yii::t('rbac', 'Cancel'), $backUrl, ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
        
    <?php ActiveForm::end(); ?>
</div>

