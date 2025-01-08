<?php

namespace common\tests\unit;

use common\fixtures\UserFixture;
use common\models\LoginForm;
use common\models\User;
use Codeception\Test\Unit;
use yii;

class LoginFormTest extends Unit
{
    protected $tester;

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }
    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => 'artur',
            'password' => 'artur123',
        ]);

        verify($model->login())->true();
        verify($model->errors)->arrayHasNotKey('password');
        verify(Yii::$app->user->isGuest)->false();
    }
    public function testLoginWithInvalidUsername()
    {
        // Criar o modelo LoginForm com um nome de utilizador inválido
        $loginForm = new LoginForm();
        $loginForm->username = 'invalidUser';
        $loginForm->password = 'validPassword';

        // Verificar se o login falha
        $this->assertFalse($loginForm->login(), "Login não deveria ser bem-sucedido com um nome de utilizador inválido.");
    }

    public function testLoginWithInvalidPassword()
    {
        // Criar um utiizador válido para testes
        $user = new User();
        $user->username = 'validUser';
        $user->password_hash = Yii::$app->security->generatePasswordHash('validPassword');
        $user->save();

        // Criar o modelo LoginForm com a password inválida
        $loginForm = new LoginForm();
        $loginForm->username = 'validUser';
        $loginForm->password = 'invalidPassword';

        // Verificar se o login falha
        $this->assertFalse($loginForm->login(), "Login não deveria ser bem-sucedido com uma password inválida.");
    }

    public function testLoginWithEmptyData()
    {
        // Criar o modelo LoginForm sem dados
        $loginForm = new LoginForm();

        // Validar o modelo e verificar se os campos obrigatórios falham
        $this->assertFalse($loginForm->validate(), "O modelo deveria falhar com dados vazios.");
        $this->assertArrayHasKey('username', $loginForm->errors, "Erro esperado para 'username'.");
        $this->assertArrayHasKey('password', $loginForm->errors, "Erro esperado para 'password'.");
    }




}