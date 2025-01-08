<?php

namespace app\backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\User;
use yii\helpers\Url;

class UserCest
{
    public function _before(FunctionalTester $I){
    // Realiza o login antes de cada teste
    $I->amOnPage(Url::toRoute('/site/login'));
    $I->fillField('LoginForm[username]', 'artur');
    $I->fillField('LoginForm[password]', 'artur123');
    $I->click('LOGIN'); // Clica no botão de login
    $I->amOnPage('/');
    }

    public function criarCliente(FunctionalTester $I)
    {
        $I->click('Administrar Contas');
        $I->click('#criar-cliente');


        $I->fillField('UserForm[username]', 'TesteTests');
        $I->fillField('UserForm[email]', 'TesteTests@gmail.com');
        $I->fillField('UserForm[password]', 'TesteTests');
        $I->fillField('UserForm[nome]', 'Teste Tests');
        $I->fillField('UserForm[telefone]', '123466789');
        $I->fillField('UserForm[nif]', '123466789');
        $I->fillField('UserForm[rua]', 'Rua Teste');
        $I->fillField('UserForm[codpostal]', '1234-567');

        // Submete o formulário
        $I->click('Save');

        // Verifica se o usuário foi criado
        $I->seeRecord(User::className(), ['username' => 'TesteTests']);
    }
}