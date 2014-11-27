<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \mdm\admin\models\ChangePasswordForm */

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-change-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to change password:</p>

    <div class="row">
        <div class="col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'form-change-password']); ?>
                <?= $form->field($model, 'oldPassword') ?>
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                <?= $form->field($model, 'retypePassword')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
