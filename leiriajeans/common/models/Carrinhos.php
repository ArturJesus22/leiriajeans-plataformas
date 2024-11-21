<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "carrinho".
 *
 * @property int $id
 * @property int|null $userdata_id
 * @property int|null $produto_id
 * @property float|null $ivatotal
 * @property float|null $total
 *
 * @property Linhascarrinhos[] $linhacarrinhos
 * @property Produtos $produto
 * @property UsersForm $userdata
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
            [['userdata_id', 'produto_id'], 'integer'],
            [['ivatotal', 'total'], 'number'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersForm::class, 'targetAttribute' => ['userdata_id' => 'id']],
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
        return $this->hasMany(Linhascarrinhos::class, ['carrinho_id' => 'id']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produtos::class, ['id' => 'produto_id']);
    }

    /**
     * Gets query for [[Userdata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserdata()
    {
        return $this->hasOne(UsersForm::class, ['id' => 'userdata_id']);
    }
}
