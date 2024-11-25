<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Carrinhos extends ActiveRecord
{
    public static function tableName()
    {
        return 'carrinho';
    }

    public function rules()
    {
        return [
            [['userdata_id', 'produto_id', 'total', 'ivatotal'], 'required'],
            [['userdata_id', 'produto_id'], 'integer'],
            [['ivatotal', 'total'], 'number'],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersForm::class, 'targetAttribute' => ['userdata_id' => 'id']],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id']],
        ];
    }

    public function getProduto()
    {
        return $this->hasOne(Produtos::class, ['id' => 'produto_id']);
    }

    public function getUserdata()
    {
        return $this->hasOne(UsersForm::class, ['id' => 'userdata_id']);
    }
}
