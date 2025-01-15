<?php

namespace app\backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use yii\helpers\Url;


/**
 * Class LoginCest
 */
class CategoriaCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ],
        ];
    }
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('LoginForm[username]', 'artur');
        $I->fillField('LoginForm[password]', 'artur123');
        $I->click('LOGIN');
        $I->see('Dashboard');
    }
    /**
     * @param FunctionalTester $I
     */

    public function criarCategoria(FunctionalTester $I)
    {
        $I->click('Categoria');

        $I->click('#criar-categoria');
        $I->fillField('Categoria[sexo]', 'Homem');
        $I->fillField('Categoria[tipo]', 'Camisas');

        $I->click('Save');

        $I->seeRecord('common\models\Categoria', ['sexo'=>'Homem']);
    }

    public function editarCategoria(FunctionalTester $I)
    {
        $I->click('Categoria');

        $I->click('#criar-categoria');

        $I->fillField('Categoria[sexo]', 'Mulher');
        $I->fillField('Categoria[tipo]', 'Camisas');

        $I->click('Save');

        $I->click('Update');

        $I->fillField('Categoria[sexo]', 'Mulher');
        $I->fillField('Categoria[tipo]', 'Camisolas');

        $I->click('Save');

        $I->seeRecord('common\models\Categoria', ['sexo'=>'Mulher', 'tipo'=>'Camisolas']);
    }




}
