<?php

namespace common\models;

use backend\models\AuthAssignment;
use Yii;

/**
 * This is the model class for table "userdata".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $codpostal
 * @property string|null $localidade
 * @property string|null $rua
 * @property string|null $nif
 * @property string|null $telefone
 * @property int|null $user_id
 *
 * @property Avaliacao[] $avaliacaos
 * @property Carrinho[] $carrinhos
 * @property User $user
 */
class UserForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //NOME & RUA
            [['nome', 'rua'], 'required','message'=>'Este campo é obrigatório'],
            [['nome', 'rua'], 'string', 'max' => 255],

            //telefone
            [['telefone'], 'string', 'max' => 10, 'min' => 9, 'tooShort' => 'TELEFONE INVÁLIDO (Precisa no mínimo 9 digitos)', 'tooLong' => 'TELEFONE INVÁLIDO (Máximo 9 digitos)'],
            [['telefone'], 'unique'],

            //codpostal
            [['codpostal'], 'required','message'=>'Este campo é obrigatório'],
            [['codpostal'], 'string', 'max' => 8],

            //localidade
            [['localidade'], 'required','message'=>'Este campo é obrigatório'],
            [['localidade'], 'string', 'max' => 100],

            //nif
            [['nif'], 'string', 'max' => 10, 'min' => 9, 'tooShort' => 'NIF INVÁLIDO (Precisa no mínimo 9 digitos)', 'tooLong' => 'NIF INVÁLIDO (Máximo 9 digitos)'],
            [['nif'], 'unique'],
            [['nif'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites somente números.'],

            [['nif', 'telefone'], 'string', 'max' => 15],
            [['nif'], 'unique'],

            //userid
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'codpostal' => 'Código postal',
            'localidade' => 'Localidade',
            'rua' => 'Rua',
            'nif' => 'Nif',
            'telefone' => 'Telefone',
            'user_id' => 'User ID',
        ];
    }



    /**
     * Gets query for [[Avaliacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacaos()
    {
        return $this->hasMany(Avaliacao::class, ['userdata_id' => 'id']);
    }

    /**
     * Gets query for [[Carrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasMany(Carrinho::class, ['userdata_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id']);
    }

    public function atualizarUser()
    {

        //$userData= UserForm::findOne(['id' => $this->id]);

        // $userData->nome = $this->nome;
        // $userData->codpostal = $this->codpostal;
        // $userData->localidade = $this->localidade;
        // $userData->rua = $this->rua;
        // $userData->nif = $this->nif;
        // $userData->telefone = $this->telefone;


        //return $userData->save();
    }
}
