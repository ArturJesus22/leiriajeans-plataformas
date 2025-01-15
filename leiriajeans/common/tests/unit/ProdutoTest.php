<?php


namespace common\tests\unit;
use common\models\Produto;


use common\tests\UnitTester;

class ProdutoTest extends \Codeception\Test\Unit
{
    protected $tester;

    public function testCriarProduto()
    {
        // Criar um novo produto com dados válidos
        $produto = new Produto();
        $produto->nome = 'Calças';
        $produto->descricao = 'Baggy';
        $produto->preco = 100;
        $produto->stock = 200;
        $produto->cor_id = 1;

        // Validar e guardar o produto
        $isValid = $produto->validate();
        $this->assertTrue($isValid, 'Produto não validado corretamente. Erros: ' . json_encode($produto->errors));

        $this->assertTrue($produto->save(), 'Produto não foi guardado na base de dados');

        // Verificar se o produto foi realmente guardado na base de dados
        $this->tester->seeRecord(Produto::className(), ['nome' => 'Calças']);
    }

    public function testEditarProduto()
    {
        // Criar o produto inicial
        $produto = new Produto();
        $produto->nome = 'Calças';
        $produto->descricao = 'Baggy';
        $produto->preco = 100;
        $produto->stock = 200;
        $produto->cor_id = 1;
        $produto->save();

        // Editar o produto
        $produto->nome = 'Camisola';
        $produto->descricao = 'Riscas';
        $produto->preco = 120;
        $produto->stock = 150;
        $produto->save();

        // Verificar se o produto foi atualizado corretamente
        $this->tester->seeRecord(Produto::className(), ['nome' => 'Camisola']);
        $this->tester->seeRecord(Produto::className(), ['descricao' => 'Riscas']);
        $this->tester->seeRecord(Produto::className(), ['preco' => 120]);
        $this->tester->seeRecord(Produto::className(), ['stock' => 150]);
    }
}
