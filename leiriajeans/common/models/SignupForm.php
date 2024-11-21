<?php

namespace common\models;

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
            //SignupForm Default
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

            //UserData
            ['nome', 'required'],
            ['nome', 'string', 'max' => 255],

            ['codigopostal', 'required'],
            ['codigopostal', 'string', 'max' => 255],

            ['localidade', 'required'],
            ['localidade', 'string', 'max' => 255],

            ['rua', 'required'],
            ['rua', 'string', 'max' => 255],

            ['telefone', 'required'],
            ['telefone', 'string', 'max' => 255],

            ['nif', 'required'],
            ['nif', 'string', 'max' => 255],

            ['role', 'required'],
            ['role', 'string', 'max' => 255],

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
        $userData = new UsersForm();

        //User
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        //userData
        $userData->nome = $this->nome;
        $userData->codpostal = $this->codigopostal;
        $userData->localidade = $this->localidade;
        $userData->rua = $this->rua;
        $userData->telefone = $this->telefone;
        $userData->nif = $this->nif;

        $user->save();


        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole($this->role);
        $auth->assign($userRole, $user->id);
        $userData->user_id = $user->id;

        $userData->save();
        var_dump($userData->getErrors());

        return $this->sendEmail($user);
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
