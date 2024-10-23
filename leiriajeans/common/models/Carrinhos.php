<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinho".
 *
 * @property int $id
 * @property int|null $userdata_id
 * @property int|null $metodopagamento_id
 * @property int|null $produto_id
 * @property float|null $ivatotal
 * @property float|null $total
 *
 * @property LinhasCarrinhos[] $linhacarrinhos
 * @property Produto $produto
 * @property Userdata $userdata
 */
class Carrinhos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrinho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userdata_id', 'metodopagamento_id', 'produto_id'], 'integer'],
            [['ivatotal', 'total'], 'number'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userdata::class, 'targetAttribute' => ['userdata_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userdata_id' => 'Userdata ID',
            'metodopagamento_id' => 'Metodopagamento ID',
            'produto_id' => 'Produto ID',
            'ivatotal' => 'Ivatotal',
            'total' => 'Total',
        ];
    }

    /**
     * Gets query for [[Linhacarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhacarrinhos()
    {
        return $this->hasMany(LinhasCarrinhos::class, ['carrinho_id' => 'id']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    /**
     * Gets query for [[Userdata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserdata()
    {
        return $this->hasOne(Userdata::class, ['id' => 'userdata_id']);
    }
}
