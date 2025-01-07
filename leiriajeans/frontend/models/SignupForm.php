<?php

namespace frontend\models;

use common\models\UserForm;
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
            [['nome', 'rua'], 'required','message'=>'Este campo é obrigatório'],
            [['nome', 'rua'], 'string', 'max' => 255],


            // Código Postal
            ['codigopostal', 'required', 'message' => 'O campo Código Postal é obrigatório.'],
            ['codigopostal', 'string', 'max' => 255, 'tooLong' => 'O Código Postal não pode ter mais que 255 caracteres.'],

            // Localidade
            ['localidade', 'required', 'message' => 'O campo Localidade é obrigatório.'],
            ['localidade', 'string', 'max' => 255, 'tooLong' => 'A Localidade não pode ter mais que 255 caracteres.'],

            // Rua
            ['rua', 'required', 'message' => 'O campo Rua é obrigatório.'],
            ['rua', 'string', 'max' => 255, 'tooLong' => 'A Rua não pode ter mais que 255 caracteres.'],


            [['telefone'], 'string', 'max' => 9, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            ['telefone', 'unique', 'targetClass' => '\common\models\UserForm', 'message' => 'This telefone has already been taken.'],
            [['telefone'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números .'],

            [['nif'], 'string', 'max' => 10, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            ['nif', 'unique', 'targetClass' => '\common\models\UserForm', 'message' => 'This NIF has already been taken.'],
            [['nif'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números.'],


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
        $userData = new UserForm();

        //User
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->role = $this->role;

        $user->save();

        //userData
        $userData->nome = $this->nome;
        $userData->codpostal = $this->codigopostal;
        $userData->localidade = $this->localidade;
        $userData->rua = $this->rua;
        $userData->telefone = $this->telefone;
        $userData->nif = $this->nif;
        $userData->user_id = $user->id;

        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole($this->role);
        $auth->assign($userRole, $user->id);

        $userData->save();

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
