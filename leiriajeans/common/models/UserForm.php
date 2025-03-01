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
 * @property Avaliacao[] $avaliacao
 * @property Carrinho[] $carrinho
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
            //Nome só aceita letras
            [['rua'], 'required','message'=>'Este campo é obrigatório'],
            [['rua'], 'string', 'max' => 255],

            [['nome'], 'required','message'=>'Este campo é obrigatório'],
            [['nome'], 'string', 'max' => 255],
            [['nome'], 'match', 'pattern' => '/^[a-zA-Z\s]*$/', 'message' => 'Só são aceites letras.'],


            // Código Postal
            ['codpostal', 'required', 'message' => 'O campo Código Postal é obrigatório.'],
            ['codpostal', 'string', 'max' => 255, 'tooLong' => 'O Código Postal não pode ter mais que 255 caracteres.'],

            // Localidade
            ['localidade', 'required', 'message' => 'O campo Localidade é obrigatório.'],
            ['localidade', 'string', 'max' => 255, 'tooLong' => 'A Localidade não pode ter mais que 255 caracteres.'],

            // Rua
            ['rua', 'required', 'message' => 'O campo Rua é obrigatório.'],
            ['rua', 'string', 'max' => 255, 'tooLong' => 'A Rua não pode ter mais que 255 caracteres.'],


            [['telefone'], 'string', 'max' => 9, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            ['telefone', 'unique', 'targetClass' => '\common\models\UserForm', 'message' => 'This telefone has already been taken.'],
            [['telefone'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números .'],

            [['nif'], 'string', 'max' => 10, 'min' => 9, 'tooShort' => 'Precisa no mínimo 9 digitos', 'tooLong' => 'Não pode ter mais de 9 digitos'],
            ['nif', 'unique', 'targetClass' => '\common\models\UserForm', 'message' => 'This NIF has already been taken.'],
            [['nif'], 'match', 'pattern' => '/^\d+$/i', 'message' => 'Só são aceites números.'],

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
