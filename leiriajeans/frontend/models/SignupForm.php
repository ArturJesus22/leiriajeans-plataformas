<?php

namespace frontend\models;

use common\models\UsersForm;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    //userData
     public $nome;
     public $morada;
     public $codigopostal;
     public $localidade;
     public $rua;
     public $telefone;
     public $nif;
     public $user_id;
     public $role = 'cliente';





    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        $userData = new UsersForm();
        $userData->nome = $this->nome;
        $userData->codpostal = $this->codigopostal;
        $userData->localidade = $this->localidade;
        $userData->rua = $this->rua;
        $userData->telefone = $this->telefone;
        $userData->nif = $this->nif;

        //UserRole
        $this->id = $user->id;
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->role);
        $auth->assign($role, $user->id);

        $userData->user_id = $user->id;

        return $user->save() && $userData->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
