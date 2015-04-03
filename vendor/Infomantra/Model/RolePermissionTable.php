<?php

namespace Infomantra\Model;

use Zend\Db\Sql\Sql;

class RolePermissionTable extends AppModel {

    protected $table = 'role_permissions';

    public function getRolePermissions() {
        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
                ->from(array(
                    'role' => 'roles'
                ))
                ->columns(array(
                    'role'
                ))
                ->join(array(
                    'role_permission' => $this->table
                        ), 'role_permission.role_id = role.role_id', array(), 'left')
                ->join(array(
                    'permission' => 'permissions'
                        ), 'permission.permission_id = role_permission.permission_id', array(
                    'action'
                        ), 'left')
                ->join(array(
                    'resource' => 'resources'
                        ), 'resource.resource_id = permission.resource_id', array(
                    'resource'
                        ), 'left')
                ->where('permission.action is not null and resource.resource is not null')
                ->order('role.role_id');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $result;
    }

}
