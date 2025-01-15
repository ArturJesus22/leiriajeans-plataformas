<?php

namespace common\tests\unit;

use common\fixtures\IvaFixture;
use common\models\Iva;
use Codeception\Test\Unit;

class IvaTest extends Unit
{
    protected $tester;

    public function _fixtures()
    {
        return [
            'iva' => [
                'class' => IvaFixture::class,
                'dataFile' => codecept_data_dir() . 'iva.php',
            ],
        ];
    }

    public function testCreateIvaSuccessfully()
    {
        $iva = new Iva();
        $iva->percentagem = "23.5";
        $iva->descricao = 'Desconto Natal';
        $iva->status = 1;

        $this->assertTrue($iva->validate());
        verify($iva->save())->true();

        // Verificar se o Iva foi guardado
        $savedIva = Iva::findOne($iva->id);
        $this->assertNotNull($savedIva);
        $this->assertEquals("23.5", $savedIva->percentagem);
        $this->assertEquals('Desconto Natal', $savedIva->descricao);
    }

    public function testCreateIvaWithInvalidData()
    {
        // Criando um Iva com dados inválidos
        $iva = new Iva();
        $iva->percentagem = "sadads"; // Percentagem não pode ser nula
        $iva->descricao = str_repeat('a', 256); // Descrição longa
        $iva->status = "dsadsa"; // Status inválido

        // Validar o Iva
        $this->assertFalse($iva->validate());
        // Imprimir os erros
        var_dump($iva->errors);
        $this->assertArrayHasKey('percentagem', $iva->errors);
        $this->assertArrayHasKey('descricao', $iva->errors);
        $this->assertArrayHasKey('status', $iva->errors);
    }


    public function testUpdateIva()
    {
        // Atualizar Iva existente
        $iva = Iva::findOne(1);
        $iva->percentagem = "19.0";
        $iva->descricao = 'IVA reduzido';
        $iva->save();

        // Verificar se a atualização foi bem-sucedida
        $this->assertEquals(19.0, $iva->percentagem);
        $this->assertEquals('IVA reduzido', $iva->descricao);
    }

    public function testDeleteIva()
    {
        $iva = Iva::findOne(1);
        $iva->delete();

        // verificar se o Iva foi apagado
        $deletedIva = Iva::findOne(1);
        $this->assertNull($deletedIva);
    }

}
