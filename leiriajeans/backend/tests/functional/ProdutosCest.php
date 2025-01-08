<?php

namespace backend\tests\functional;

use common\fixtures\UserFixture;
use common\models\Produto;
use yii\helpers\Url;
use backend\tests\FunctionalTester;
class ProdutosCest
{
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
    }

    public function criarProduto(FunctionalTester $I)
    {
        $I->click('Produto');
        $I->click('#criar-produto');
        $I->fillField('Produto[nome]', 'calcas');
        $I->fillField('Produto[descricao]', 'baggy');
        $I->fillField('Produto[preco]', '100');
        $I->fillField('Produto[stock]', '200');
        $I->selectOption('Produto[cor_id]', '1');
        $I->selectOption('Produto[iva_id]', '23');
        $I->selectOption('Produto[categoria_id]', 'Mulher - Calças');
        $I->click('Save');
        $I->seeRecord(Produto::className(), ['nome'=>'calças']);
    }
    public function editarProduto(FunctionalTester $I)
    {
        $I->click('Produto');
        $I->click('#criar-produto');
        $I->fillField('Produto[nome]', 'calcas');
        $I->fillField('Produto[descricao]', 'baggy');
        $I->fillField('Produto[preco]', '100');
        $I->fillField('Produto[stock]', '200');
        $I->selectOption('Produto[cor_id]', '1');
        $I->selectOption('Produto[iva_id]', '23');
        $I->selectOption('Produto[categoria_id]', 'Mulher - Calças');
        $I->click('Save');

        $I->click('Update');

        $I->click('Produto');
        $I->click('#criar-produto');
        $I->fillField('Produto[nome]', 'camisola');
        $I->fillField('Produto[descricao]', 'riscas');
        $I->fillField('Produto[preco]', '100');
        $I->fillField('Produto[stock]', '200');
        $I->selectOption('Produto[cor_id]', '1');
        $I->selectOption('Produto[iva_id]', '23');
        $I->selectOption('Produto[categoria_id]', 'Mulher - Calças');
        $I->click('Save');
        $I->seeRecord(Produto::className(), ['nome'=>'camisola']);
    }

}
