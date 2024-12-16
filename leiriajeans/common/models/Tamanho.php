<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tamanho".
 *
 * @property int $id
 * @property string $nome
 */
class Tamanho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tamanho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
        ];
    }

    public function getProdutos()
    {
        return $this->hasMany(Produto::class, ['id' => 'produto_id'])
            ->viaTable('produto_tamanho', ['tamanho_id' => 'id']);
    }

}