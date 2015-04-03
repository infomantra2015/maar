<?php

namespace Infomantra\Model;

use Zend\Db\Sql\Sql;

class UserTable extends AppModel {

    protected $table = 'users';

    public function getUserDetails($where = array(), $columns = array(), $joinColumns = array()) {

        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
                ->from(array(
            'user' => $this->getTable()
        ));

        if (!empty($where)) {
            $select->where($where);
        }

        if (!empty($columns)) {
            $select->columns($columns);
        }
        
        $select->join(array('userDetail' => 'user_details'), 'user.user_id = userDetail.user_id', $joinColumns, 'LEFT');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
        return $result;
    }

}
