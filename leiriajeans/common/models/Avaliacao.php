<?php

namespace common\models;

use app\mosquitto\phpMQTT;
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
 * @property LinhaFatura $linhafatura
 * @property UserForm $userdata
 */
class Avaliacao extends \yii\db\ActiveRecord
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
            [['linhafatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhaFatura::class, 'targetAttribute' => ['linhafatura_id' => 'id']],
            [['linhafatura_id'], 'validateLinhaFatura'],
            [['userdata_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserForm::class, 'targetAttribute' => ['userdata_id' => 'id']],
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
            'linhafatura_id' => 'LinhaFatura ID',
        ];
    }

    /**
     * Gets query for [[LinhaFatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafatura()
    {
        return $this->hasOne(LinhaFatura::class, ['id' => 'linhafatura_id']);
    }

    /**
     * Gets query for [[Userdata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserdata()
    {
        return $this->hasOne(UserForm::class, ['id' => 'userdata_id']);
    }

    // Relacionamento para User (final)
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])
            ->via('userdata'); // Usa o relacionamento 'userdata' para encontrar o user_id
    }

    public function validateLinhaFatura($attribute, $params)
    {
        $linhaFatura = LinhaFatura::findOne($this->$attribute);

        if (!$linhaFatura) {
            $this->addError($attribute, 'Linha de fatura inválida.');
            return;
        }

        // Verifica se a linha de fatura pertence a uma fatura do utilizador
        $fatura = Fatura::findOne($linhaFatura->fatura_id);

        if (!$fatura || $fatura->userdata_id !== Yii::$app->user->identity->userform->id) {
            $this->addError($attribute, 'Não tem permissão para avaliar esta linha fatura.');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $utilizador = $this->userdata;
        $nomeUtilizador = $utilizador ? $utilizador->nome : 'Desconhecido';

        $produto = $this->linhafatura ? $this->linhafatura->produto->nome : 'Desconhecido';  // Assuming LinhaFatura has a 'produto_nome' field

        $mensagem = "O utilizador {$nomeUtilizador} deixou uma avaliacao no produto {$produto}.";

        $canal = $insert ? "INSERT_AVALIACOES" : "UPDATE_AVALIACOES";

        $this->FazPublishNoMosquitto($canal, $mensagem);
    }

    public function FazPublishNoMosquitto($canal, $msg)
    {
        $server = "localhost";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT-publisher";
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug_output.log", "Time out!");
        }
    }
}
