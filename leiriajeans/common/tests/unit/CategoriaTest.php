<?php

namespace common\tests\unit;

use common\models\Categoria;
use Codeception\Test\Unit;
use common\fixtures\CategoriaFixture;

class CategoriaTest extends Unit
{
    protected $tester;

    public function _fixtures()
    {
        return [
            'categoria' => [
                'class' => CategoriaFixture::class,
                'dataFile' => codecept_data_dir() . 'categoria.php',
            ],
        ];
    }

    public function testCreateCategoriaSuccessfully()
    {
        // Criar uma categoria válida
        $categoria = new Categoria();
        $categoria->sexo = "Homem";
        $categoria->tipo = "Calças";

        // Validar e guardar
        $this->assertTrue($categoria->validate());
        $this->assertTrue($categoria->save());

        // Verificar se a categoria foi guardada corretamente
        $savedCategoria = Categoria::findOne($categoria->id);
        $this->assertNotNull($savedCategoria);
        $this->assertEquals($categoria->sexo, $savedCategoria->sexo);
        $this->assertEquals($categoria->tipo, $savedCategoria->tipo);
    }

    public function testCreateCategoriaWithInvalidData()
    {
        // Criação de uma categoria com dados inválidos
        $categoria = new Categoria();
        $categoria->sexo = str_repeat("a", 11); // Excede o limite de 10 caracteres
        $categoria->tipo = str_repeat("a", 21); // Excede o limite de 20 caracteres

        // Validando
        $this->assertFalse($categoria->validate());

        // Verificando os erros de validação
        $this->assertArrayHasKey('sexo', $categoria->errors);
        $this->assertArrayHasKey('tipo', $categoria->errors);
    }

    public function testCreateCategoriaWithEmptyData()
    {
        $categoria = new Categoria();
        $categoria->sexo = "";
        $categoria->tipo = "";

        // Validar
        $this->assertFalse($categoria->validate());

        // Verificar os erros de validação
        $this->assertArrayHasKey('sexo', $categoria->errors);
        $this->assertArrayHasKey('tipo', $categoria->errors);
    }

    public function testCreateCategoriaWithValidData()
    {
        // Criar com dados válidos
        $categoria = new Categoria();
        $categoria->sexo = "Mulher";
        $categoria->tipo = "Camisas";

        // Validar e guardar
        $this->assertTrue($categoria->validate());
        $this->assertTrue($categoria->save());

        // Verificar se a categoria foi guardada corretamente
        $savedCategoria = Categoria::findOne($categoria->id);
        $this->assertNotNull($savedCategoria);
        $this->assertEquals($categoria->sexo, $savedCategoria->sexo);
        $this->assertEquals($categoria->tipo, $savedCategoria->tipo);
    }
}
