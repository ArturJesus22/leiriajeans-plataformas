<?php

namespace app\backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\Produto;
use common\models\SignupForm;
use common\models\User;
use common\models\UserForm;
use yii\helpers\Url;

class UserCest
{

    public FunctionalTester $tester;

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ],
        ];
    }

    public function _before(FunctionalTester $I){
        // Realiza o login antes de cada teste
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('LoginForm[username]', 'artur');
        $I->fillField('LoginForm[password]', 'artur123');
        $I->click('LOGIN'); // Clica no botão de login
        $I->see('Dashboard');
    }

    public function criarCliente(FunctionalTester $I)
    {
        $I->see('Dashboard');
        $I->click('Administrar contas');
        $I->click('#criar-cliente');

        $I->fillField('SignupForm[username]', 'TesteTests');
        $I->fillField('SignupForm[email]', 'TesteTests@gmail.com');
        $I->fillField('SignupForm[password]', 'TesteTests');
        $I->fillField('SignupForm[nome]', 'Teste Tests');
        $I->fillField('SignupForm[telefone]', '123466789');
        $I->fillField('SignupForm[nif]', '123466789');
        $I->fillField('SignupForm[rua]', 'Rua Teste');
        $I->fillField('SignupForm[codigopostal]', '1234-567');

        // Submete o formulário
        $I->click('Signup');
    }

    public function updateCliente(FunctionalTester $I) {

        $I->see('Dashboard');
        $I->click('Administrar contas');

        $I->click('#editar-cliente');

        $I->fillField('User[username]', 'TesteTests2');
        $I->fillField('User[email]', 'TesteTests@gmail.com');
        $I->fillField('UserForm[nome]', 'Teste Tests');
        $I->fillField('UserForm[telefone]', '123466789');
        $I->fillField('UserForm[nif]', '123466789');
        $I->fillField('UserForm[rua]', 'Rua Teste');
        $I->fillField('UserForm[codpostal]', '1234-567');

        // Submete o formulário
        $I->click('Save');
    }
}
