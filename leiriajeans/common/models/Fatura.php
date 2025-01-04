<?php

namespace common\models;

use Yii;
use stdClass;
use yii\db\ActiveQuery;
use app\mosquitto\phpMQTT;

require_once('../../mosquitto/phpMQTT.php');

/**
 * This is the model class for table "fatura".
 *
 * @property int $id
 * @property int $metodopagamento_id
 * @property int $metodoexpedicao_id
 * @property string|null $data
 * @property float|null $valorTotal
 * @property int|null $statuspedido
 *
 * @property LinhaFatura[] $linhafatura
 * @property MetodoExpedicao $metodoexpedicao
 * @property MetodoPagamento $metodopagamento
 */
class Fatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodopagamento_id', 'metodoexpedicao_id', 'userdata_id'], 'required'],
            [['metodopagamento_id', 'metodoexpedicao_id', 'statuspedido', 'userdata_id'], 'integer'],
            [['data'], 'safe'],
            [['valorTotal'], 'number'],
            [['metodoexpedicao_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetodoExpedicao::class, 'targetAttribute' => ['metodoexpedicao_id' => 'id']],
            [['metodopagamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetodoPagamento::class, 'targetAttribute' => ['metodopagamento_id' => 'id']],
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
            'metodopagamento_id' => 'Metodopagamento ID',
            'metodoexpedicao_id' => 'Metodoexpedicao ID',
            'data' => 'Data',
            'valorTotal' => 'Valor Total',
            'statuspedido' => 'Statuspedido',
        ];
    }

    /**
     * Gets query for [[Linhafaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafaturas()
    {
        return $this->hasMany(LinhaFatura::class, ['fatura_id' => 'id']);
    }

    /**
     * Gets query for [[Metodoexpedicao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodoexpedicao()
    {
        return $this->hasOne(MetodoExpedicao::class, ['id' => 'metodoexpedicao_id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodopagamento()
    {
        return $this->hasOne(MetodoPagamento::class, ['id' => 'metodopagamento_id']);
    }

    public function getUserdata()
    {
        return $this->hasOne(UserForm::class, ['id' => 'userdata_id']);
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
            file_put_contents("debug . output", "Time out!");
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Obter dados do utilizador
        $utilizador = $this->userdata;
        $nomeUtilizador = $utilizador ? $utilizador->nome : 'Desconhecido';

        // Obter os nomes dos produtos associados à fatura
        $linhasFatura = $this->linhafaturas;
        $nomesProdutos = [];
        foreach ($linhasFatura as $linha) {
            $produto = $linha->produto;
            if ($produto) {
                $nomesProdutos[] = $produto->nome;
            }
        }

        // Construir a mensagem
        $mensagemProdutos = implode(', ', $nomesProdutos);
        $mensagem = "O utilizador {$nomeUtilizador} comprou o produto {$mensagemProdutos}";

        // Publicar no Mosquitto
        $canal = $insert ? "INSERT_FATURAS" : "UPDATE_FATURAS";
        $this->FazPublishNoMosquitto($canal, $mensagem);
    }

}
