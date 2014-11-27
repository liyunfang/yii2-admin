<?php

namespace mdm\admin\models;

use Yii;

/**
 * ChangePasswordForm
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ChangePasswordForm extends \yii\base\Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    public function rules()
    {
        return[
            ['newPassword', 'required'],
            ['newPassword', 'string', 'min' => 6],
            
            ['retypePassword', 'required'],
            ['retypePassword', 'compare', 'compareAttribute' => 'newPassword'],
            
            ['oldPassword', 'required'],
            ['oldPassword', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }
    
    public function changePassword()
    {
        if($this->validate()){
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            return $user->save();
        }
        return false;
    }
}