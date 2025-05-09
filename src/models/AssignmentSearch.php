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
     * @var int|string $id
     */
    public $id;

    /**
     * @var string $login
     */
    public $login;

    /**
     * @var string|int|bool|null $userActive
     */
    public $userActive;

    /**
     * Used to filter the grid by a role.
     * @var string $role
     */
    public $role;

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
        return [[['id', 'login', 'userActive', 'role'], 'safe']];
    }

    /**
     * Create data provider for Assignment model.
     */
    public function search()
    {
        $userTbl = $this->rbacModule->userModelClassName::tableName();
        $authAssignmentTbl = Yii::$app->authManager->assignmentTable;
        $authItemTbl = Yii::$app->authManager->itemTable;

        $query = call_user_func($this->rbacModule->userModelClassName . '::find');

        $query->leftJoin(
            $authAssignmentTbl . ' ast',
            'ast.user_id = ' . $userTbl . '.' . $this->rbacModule->userModelIdField
        );

        $query->leftJoin($authItemTbl . ' ait', 'ait.name = ast.item_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    $this->rbacModule->userModelLoginField => SORT_ASC,
                ],
            ],
        ]);

        $params = Yii::$app->request->getQueryParams();

        if (!isset($params['AssignmentSearch']['userActive'])) {
            $params['AssignmentSearch']['userActive'] = 1;
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([$this->rbacModule->userModelIdField => $this->id]);
        $query->andFilterWhere(['like', $this->rbacModule->userModelLoginField, $this->login]);
        $query->andFilterWhere([$this->rbacModule->userModelActiveField => $this->userActive]);
        $query->andFilterWhere(['ait.name' => $this->role]);

        return $dataProvider;
    }
}
