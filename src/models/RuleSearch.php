<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class RuleSearch extends Rule
{
    /**
     *
     * @var string
     */
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['name'], 'safe']];
    }

    /**
     * Search authitem
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $authManager = Yii::$app->authManager;
        $models = [];
        foreach ($authManager->getRules() as $name => $item) {
            if ($this->name == null || empty($this->name)) {
                $models[$name] = new Rule($item);
            } elseif (strpos($name, $this->name) !== false) {
                $models[$name] = new Rule($item);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $models,
        ]);
    }
}
