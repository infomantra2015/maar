<?php

namespace Infomantra\Model;

use Zend\Db\Sql\Sql;

class UserRoleTable extends AppModel {

    protected $table = 'user_role';

    /**
     * Get user role name
     * 
     * @param array $where
     * @param array $columns
     * @return array
     */
    public function getUserRoleName($where = array(), $columns = array()) {

        try {
            $sql = new Sql($this->getAdapter());

            $select = $this->prepareSelect($sql, $where, $columns);

            $select->join(array('role' => 'roles'), 'role.role_id = user_role.role_id',
                    array('role', 'role_id'));

            $statement = $sql->prepareStatementForSqlObject($select);
            
            $records = $this->getResultSetPrototype()->initialize($statement->execute())
                    ->toArray();
            return $records;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
