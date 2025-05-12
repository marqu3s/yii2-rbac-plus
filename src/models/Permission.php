<?php

namespace marqu3s\rbacplus\models;

use Yii;
use yii\db\Query;
use yii\rbac\Item;
use yii\rbac\Permission as RbacPermission;
use yii\rbac\Role as RbacRole;

/**
 * Description of Permistion
 *
 * @author John Martin <john.itvn@gmail.com>
 * @author Edmund Kawalec <e.kawalec@s4studio.pl>
 * @since 1.0.0
 */
class Permission extends AuthItem
{
    protected function getType()
    {
        return Item::TYPE_PERMISSION;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Permission name');
        return $labels;
    }

    public static function find($name)
    {
        $authManager = Yii::$app->authManager;
        $item = $authManager->getPermission($name);
        return new self($item);
    }

    /**
     * Returns all permissions that are directly assigned to user.
     * @param string|int $userId the user ID (see [[\yii\web\User::id]])
     * @return Permission[] all direct permissions that the user has. The array is indexed by the permission names.
     * @since 2.0.7
     */
    public static function getDirectPermissionsByUser($userId)
    {
        $authManager = Yii::$app->authManager;
        $query = (new Query())
            ->select('b.*')
            ->from(['a' => $authManager->assignmentTable, 'b' => $authManager->itemTable])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['a.user_id' => (string) $userId])
            ->andWhere(['b.type' => Item::TYPE_PERMISSION]);

        $permissions = [];
        foreach ($query->all(Yii::$app->db) as $row) {
            $permissions[$row['name']] = self::populateItem($row);
        }

        return $permissions;
    }

    /**
     * Populates an auth item with the data fetched from database.
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected static function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? RbacPermission::class : RbacRole::class;

        if (
            !isset($row['data']) ||
            ($data = @unserialize(
                is_resource($row['data']) ? stream_get_contents($row['data']) : $row['data']
            )) === false
        ) {
            $data = null;
        }

        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'] ?: null,
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }
}
