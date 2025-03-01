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
 * @property Produto $produto
 */
class Imagem extends \yii\db\ActiveRecord
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
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 2] // Limite de 2MB
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'Imagem',
            'produto_id' => 'Produto Associados',
        ];
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
