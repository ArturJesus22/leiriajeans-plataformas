<?php


namespace common\tests\Unit;

use common\fixtures\UserFixture;
use common\tests\UnitTester;

class IvaTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ],
        ];
    }

    protected function _before()
    {

    }

    // tests
    public function testSomeFeature()
    {

    }
}
