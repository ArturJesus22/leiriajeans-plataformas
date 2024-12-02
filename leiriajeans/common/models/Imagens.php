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
            [['produto_id'], 'integer'],
            [['produto_id'], 'required', 'message' => 'Selecione um Produto!'],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produtos::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
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
                $uploadPathBack = Yii::getAlias('@backend/web/public/imagens/produtos/') . $uid . $file->baseName . '.' . $file->extension;
                $uploadPathFront = Yii::getAlias('@frontend/web/images/produtos/') . $uid . $file->baseName . '.' . $file->extension;

                $file->saveAs($uploadPathBack, false);
                $file->saveAs($uploadPathFront, false);
                $uploadPaths[] = $uploadPathBack;

            }

            return $uploadPaths;
        } else {

            return false;
        }
    }


}
