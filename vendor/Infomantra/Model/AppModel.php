<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Update;

class AppModel extends AbstractTableGateway {

    public function init($adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }

    /**
     * Generic function to fetch data from various tables
     * 
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return array
     * @throws \Exception
     */
    public function getRecords($where = array(), $columns = array(),
            $orderBy = array(), $paging = false) {
        try {

            $sql = new Sql($this->getAdapter());

            $select = $this->prepareSelect($sql, $where, $columns, $orderBy);

            if ($paging) {

                $dbAdapter = new DbSelect($select, $this->getAdapter());
                $paginator = new Paginator($dbAdapter);
                return $paginator;
            } else {

                $statement = $sql->prepareStatementForSqlObject($select);
                $records = $this->getResultSetPrototype()->initialize($statement->execute())
                        ->toArray();
                return $records;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function prepareSelect($sql, $where = array(), $columns = array(),
            $orderBy = array()) {

        $select = $sql->select()->from($this->getTable());

        if (count($where) > 0) {
            $select->where($where);
        }

        if (count($columns) > 0) {
            $select->columns($columns);
        }

        if (count($orderBy) > 0) {
            $select->order($orderBy);
        }

        return $select;
    }

    public function saveRecord($data) {
        $this->insert($data);
        return $this->lastInsertValue;
    }

    public function updateData($data, $where) {

        $sql = new Sql($this->getAdapter());

        $update = new Update($this->getTable());
        $update->set($data);
        $update->where($where);

        $statement = $sql->prepareStatementForSqlObject($update);
        $results = $statement->execute();
        $affectedRows = $results->getAffectedRows();

        return $affectedRows;
    }

    public function deleteRecords($where) {

        $sql = new Sql($this->getAdapter());

        $delete = new Delete($this->getTable());
        $delete->where($where);

        $statement = $sql->prepareStatementForSqlObject($delete);
        $results = $statement->execute();
        $affectedRows = $results->getAffectedRows();

        return $affectedRows;
    }

}
