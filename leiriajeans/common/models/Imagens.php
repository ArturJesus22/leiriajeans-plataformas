<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "imagem".
 *
 * @property int $id
 * @property string|null $fileName
 * @property int|null $produto_id
 *
 * @property Produtos $produto
 */
class Imagens extends \yii\db\ActiveRecord
{

    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imagem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'safe'],
            [['produto_id'], 'integer'],
            [['fileName'],  'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['produto_id'], 'required', 'message' => 'Selecione um Produto!'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'Imagens',
            'produto_id' => 'Produtos Associados',
        ];
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

    public function upload()
    {

        $uploadPaths = [];

        if ($this->validate()) {

            foreach ($this->imageFiles as $file) {
                $uid = uniqid();
                //Caminho para dar  upload
                $uploadCommon = Yii::getAlias('@common/public/imagens/produtos/') . $uid . $file->baseName . '.' . $file->extension;

                // guardar imagem no common
                $file->saveAs($uploadCommon, false);

                // Adiciona o caminho de upload para a lista
                $uploadPaths[] = $uploadCommon;
            }

            return $uploadPaths;
        } else {
            return false;

        }
    }
}
