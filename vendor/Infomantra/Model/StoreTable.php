<?php

namespace Infomantra\Model;

use Zend\Db\Sql\Sql;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Expression;

class StoreTable extends AppModel {

    const STORE_OFFER_JOIN = 'STORE_OFFERS';

    protected $table = 'stores';

    public function getStoreDetails($where = array(), $columns = array(),
            $orderBy = array(), $paging = false, $params = array()) {

        try {

            $sql = new Sql($this->getAdapter());
            $select = $this->prepareSelect($sql, $where, $columns, $orderBy);

            $select->join(array(
                        'country' => 'countries'
                            ), 'country.country_id = stores.country_id',
                            array('country_name'), 'left')
                    ->join(array(
                        'state' => 'states'
                            ), 'state.state_id = stores.state_id',
                            array(
                        'state_name'
                            ), 'left')
                    ->join(array(
                        'city' => 'cities'
                            ), 'city.city_id = stores.city_id',
                            array(
                        'city_name'
                            ), 'left')
                    ->join(array(
                        'category' => 'categories'
                            ), 'stores.category_id = category.category_id',
                            array(
                        'category_name'
                            ), 'left');        


            if (count($params) > 0) {

                if (in_array(self::STORE_OFFER_JOIN, $params)) {

                    $select->join(array(
                        'storeOffers' => 'store_offers'
                            ), new Expression('storeOffers.store_id = stores.store_id AND storeOffers.offer_id = '. $params['OFFER_ID']),
                            array('is_opted' => new Expression('NOT ISNULL(storeOffers.id)')),
                            'LEFT');
                }
            }

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

}
