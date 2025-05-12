<?php

namespace marqu3s\rbacplus\models;

use marqu3s\rbacplus\Module;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\rbac\Item;

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
     * Used to filter the grid by a direct role.
     * @var string $role
     */
    public $role;

    /**
     * Used to filter the grid by a direct permission.
     * @var string $permission
     */
    public $permission;

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
        return [[['id', 'login', 'userActive', 'role', 'permission'], 'safe']];
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
        $query
            ->select([
                $userTbl . '.*',
                'roles' => new Expression('GROUP_CONCAT(ait_roles.name)'),
                'permissions' => new Expression('GROUP_CONCAT(ait_perm.name)'),
            ])
            ->leftJoin(
                $authAssignmentTbl . ' ast_roles',
                'ast_roles.user_id = ' . $userTbl . '.' . $this->rbacModule->userModelIdField
            )
            ->leftJoin(
                $authItemTbl . ' ait_roles',
                'ait_roles.name = ast_roles.item_name AND ait_roles.type = ' . Item::TYPE_ROLE
            )
            ->leftJoin(
                $authAssignmentTbl . ' ast_perm',
                'ast_perm.user_id = ' . $userTbl . '.' . $this->rbacModule->userModelIdField
            )
            ->leftJoin(
                $authItemTbl . ' ait_perm',
                'ait_perm.name = ast_perm.item_name AND ait_perm.type = ' . Item::TYPE_PERMISSION
            )
            ->groupBy($userTbl . '.' . $this->rbacModule->userModelIdField);

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

        $query
            ->andFilterWhere([$this->rbacModule->userModelIdField => $this->id])
            ->andFilterWhere(['like', $this->rbacModule->userModelLoginField, $this->login])
            ->andFilterWhere([$this->rbacModule->userModelActiveField => $this->userActive])
            ->andFilterWhere(['like', 'ait_roles.name', $this->role])
            ->andFilterWhere(['like', 'ait_perm.name', $this->permission]);

        $sql = $query->createCommand()->getRawSql();

        return $dataProvider;
    }
}
