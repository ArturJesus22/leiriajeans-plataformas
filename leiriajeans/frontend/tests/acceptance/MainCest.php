<?php


namespace frontend\tests\Acceptance;

use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class MainCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/leiriajeans-plataformas/leiriajeans/frontend/web/site/index');
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->click('#login');
        $I->fillField('LoginForm[username]', 'artur');
        $I->fillField('LoginForm[password]', 'artur123');
        $I->click('login-button');
        $I->wait('2');
        $I->click('Produtos');
        $I->scrollTo('#addproduto');
        $I->wait('2');
        $I->click('#addproduto');
        $I->wait('2');
        $I->click('Proceder');
        $I->wait('1');
        $I->acceptPopup('OK');
        $I->wait('2');
        $I->click('.form-check-input');
        $I->wait('1');
        $I->fillField('mbway-phone','913888333');
        $I->click('.form-check-input1');
        $I->wait('1');
        $I->click('Confirmar');
        $I->acceptPopup('OK');
        $I->wait('3');
        $I->click('Produtos');
        $I->scrollTo('#addproduto');
        $I->wait('1');
        $I->click('Ver');
        $I->wait('2');
        $I->scrollTo('footer');
        $I->wait('2');
        $I->fillField('Avaliacao[comentario]','Muito bom!');
        $I->wait('1');
        $I->click('.star[data-value="5"]');
        $I->wait('1');
        $I->scrollTo('footer');
        $I->click('enviar-avaliacao');
        $I->wait('3');
    }
}
