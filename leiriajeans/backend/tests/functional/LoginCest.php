<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\User;
use Yii;
use yii\helpers\Url;

/**
 * Class LoginCest
 */
class LoginCest
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
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
        ];
    }
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
    }
    /**
     * @param FunctionalTester $I
     */

    public function loginVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', '');
        $I->fillField('LoginForm[password]', '');
        $I->click('LOGIN');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function credenciaisErradas(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->fillField('LoginForm[username]', 'aaaaaaa');
        $I->fillField('LoginForm[password]', 'aaaaaaa');
        $I->click('LOGIN');
        $I->see('Incorrect username or password.');
    }

    public function validarLoginAdmin(FunctionalTester $I)
    {
        $I->fillField('LoginForm[username]', 'artur');
        $I->fillField('LoginForm[password]', 'artur123');
        $I->click('LOGIN');
        $I->see('Dashboard');
    }
}
