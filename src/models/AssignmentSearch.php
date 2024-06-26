<?php

namespace marqu3s\rbacplus\models;

use marqu3s\rbacplus\Module;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 *
 */
class AssignmentSearch extends \yii\base\Model
{
    /**
     * @var Module $rbacModule
     */
    protected $rbacModule;

    /**
     *
     * @var mixed $id
     */
    public $id;

    /**
     *
     * @var string $login
     */
    public $login;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->rbacModule = Yii::$app->getModule('rbac');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['id', 'login'], 'safe']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rbac', 'ID'),
            'login' => $this->rbacModule->userModelLoginFieldLabel,
        ];
    }

    /**
     * Create data provider for Assignment model.
     */
    public function search()
    {
        $query = call_user_func($this->rbacModule->userModelClassName . '::find');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $params = Yii::$app->request->getQueryParams();
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([$this->rbacModule->userModelIdField => $this->id]);
        $query->andFilterWhere(['like', $this->rbacModule->userModelLoginField, $this->login]);

        return $dataProvider;
    }
}
