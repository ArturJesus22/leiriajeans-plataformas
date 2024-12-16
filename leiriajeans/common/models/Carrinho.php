<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Carrinho extends ActiveRecord
{
    public static function tableName()
    {
        return 'carrinho';
    }

    public function rules()
    {
        return [
            [['userdata_id', 'total', 'ivatotal'], 'required'],
            [['userdata_id'], 'integer'],
            [['ivatotal', 'total'], 'number'],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserForm::class, 'targetAttribute' => ['userdata_id' => 'id']],
        ];
    }

    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    public function getUserdata()
    {
        return $this->hasOne(UserForm::class, ['id' => 'userdata_id']);
    }
}
