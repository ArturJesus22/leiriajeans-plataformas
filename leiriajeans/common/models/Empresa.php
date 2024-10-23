<?php


namespace common\models;
/**
 * This is the model class for table "empresa".
 *
 * @property int $id
 * @property string $designacao
 * @property string $email
 * @property string|null $telefone
 * @property string $nif
 * @property string|null $rua
 * @property string|null $codigopostal
 * @property string|null $localidade
 * @property float|null $capitalsocial
 */
class Empresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['designacao', 'email', 'nif'], 'required'],
            [['capitalsocial'], 'number'],
            [['designacao', 'rua'], 'string', 'max' => 255],
            [['email', 'localidade'], 'string', 'max' => 100],
            [['telefone', 'nif'], 'string', 'max' => 15],
            [['codigopostal'], 'string', 'max' => 10],
            [['email'], 'unique'],
            [['nif'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'designacao' => 'Designacao',
            'email' => 'Email',
            'telefone' => 'Telefone',
            'nif' => 'Nif',
            'rua' => 'Rua',
            'codigopostal' => 'Codigopostal',
            'localidade' => 'Localidade',
            'capitalsocial' => 'Capitalsocial',
        ];
    }
}
