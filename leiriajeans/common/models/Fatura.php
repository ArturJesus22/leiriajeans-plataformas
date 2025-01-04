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


}
