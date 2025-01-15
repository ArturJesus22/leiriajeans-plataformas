<?php


namespace backend\tests\Functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\Iva;
use yii\helpers\Url;

class IvaCest
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

    public function _before(FunctionalTester $tester)
    {
        $tester->amOnPage(Url::toRoute('/site/login'));
        $tester->fillField('LoginForm[username]', 'artur');
        $tester->fillField('LoginForm[password]', 'artur123');
        $tester->click('LOGIN');
        $tester->see('Dashboard');
    }

    // tests
    public function criarIva(FunctionalTester $tester)
    {
        $tester->click('IVAS');
        $tester->click('#criar-iva');
        $tester->fillField('Iva[percentagem]', '24');
        $tester->fillField('Iva[descricao]', 'pascoa');
        $tester->selectOption('Iva[status]', 'Ativo');
        $tester->click('Save');

        $tester->seeRecord(Iva::className(), [
            'percentagem' => '24',  // Verifica o campo 'percentagem' com o valor '24'
            'descricao' => 'pascoa'   // Verifica o campo 'descricao' com o valor 'pascoa'
        ]);
    }

    public function editarIva(FunctionalTester $tester)
    {
        $tester->click('IVAS');
        $tester->click('#criar-iva');
        $tester->fillField('Iva[percentagem]', '24');
        $tester->fillField('Iva[descricao]', 'pascoa');
        $tester->selectOption('Iva[status]', 'Ativo');
        $tester->click('Save');

        $tester->click('Update');

        $tester->click('IVAS');
        $tester->click('#criar-iva');
        $tester->fillField('Iva[percentagem]', '30');
        $tester->fillField('Iva[descricao]', 'natal');
        $tester->selectOption('Iva[status]', 'Ativo');
        $tester->click('Save');

        $tester->seeRecord(Iva::className(), [
            'percentagem' => '30',  // Verifica o campo 'percentagem' com o valor '30'
            'descricao' => 'natal'   // Verifica o campo 'descricao' com o valor 'natal'
        ]);
    }
}
