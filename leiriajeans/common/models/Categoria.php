<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $id
 * @property string|null $sexo
 * @property string|null $tipo
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sexo', 'tipo'], 'required'],
            [['sexo'], 'string', 'max' => 10],
            [['tipo'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sexo' => 'Sexo',
            'tipo' => 'Tipo',
        ];
    }
}
