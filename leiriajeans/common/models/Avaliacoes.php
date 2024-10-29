<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "avaliacao".
 *
 * @property int $id
 * @property string|null $comentario
 * @property string|null $data
 * @property int|null $rating
 * @property int|null $userdata_id
 * @property int|null $linhafatura_id
 *
 * @property Linhasfaturas $linhafatura
 * @property UsersForm $userdata
 */
class Avaliacoes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avaliacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comentario'], 'string'],
            [['data'], 'safe'],
            [['rating', 'userdata_id', 'linhafatura_id'], 'integer'],
            [['linhafatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Linhasfaturas::class, 'targetAttribute' => ['linhafatura_id' => 'id']],
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
            'comentario' => 'Comentario',
            'data' => 'Data',
            'rating' => 'Rating',
            'userdata_id' => 'Userdata ID',
            'linhafatura_id' => 'Linhafatura ID',
        ];
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafatura()
    {
        return $this->hasOne(Linhafatura::class, ['id' => 'linhafatura_id']);
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
