<?php

namespace Infomantra\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Expression;

class AppController extends AbstractActionController {

    protected function _getHelper($helper) {
        return $this->getServiceLocator()
                        ->get('viewhelpermanager')
                        ->get($helper);
    }

    protected function beginTransaction() {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $dbAdapter->getDriver()->getConnection()->beginTransaction();
    }

    protected function commitTransaction() {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $dbAdapter->getDriver()->getConnection()->commit();
    }

    protected function rollbackTransaction() {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $dbAdapter->getDriver()->getConnection()->rollback();
    }

    /**
     * Send Mail
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return stdObject
     */
    protected function _sendMail($mailOptions) {
        $mailer = $this->plugin('EmailPlugin');
        $mailer->sendMail($mailOptions);
    }

    /**
     * Get particular configuration
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return stdObject
     */
    protected function _getConfig($key = '') {

        $config = $this->getServiceLocator()->get('Config');

        if (!empty($key)) {
            return $config[$key];
        }

        return $config;
    }

    /**
     * Manage encryption/descryption
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return string
     */
    protected function manageEncryption($text, $type = 'encrypt') {

        $cryptography = $this->getServiceLocator()->get('Cryptography');
        $cryptography->init();
        if ($type == 'encrypt') {
            return $cryptography->encrypt($text);
        } else {
            return $cryptography->decrypt($text);
        }
    }

    /**
     * Get logged in user details
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param string $key
     * @return stdObject
     */
    protected function _getLoggedUserDetails($key = null) {
        $auth = $this->getServiceLocator()->get('AuthenticationService');
        $storage = $auth->getStorage();
        $content = $storage->read();
        if (!empty($key) && isset($content->{$key})) {
            return $content->{$key};
        }
        return $content;
    }

    /**
     * Get state list
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param integer $countryId
     * @return array
     */
    protected function getStates($countryId = 0) {

        $stateList = array('' => 'Select state');

        if ($countryId) {
            $stateTable = $this->getServiceLocator()->get('StateTable');

            $records = $stateTable->getRecords(array('country_id' => $countryId,
                'status' => 'active'), array('state_id', 'state_name'), array('state_name asc'));

            if (count($records) > 0) {
                foreach ($records as $record) {
                    $stateList[$record['state_id']] = $record['state_name'];
                }
            }
        }
        return $stateList;
    }

    /**
     * Get state list
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param integer $stateId
     * @return array
     */
    protected function getCities($stateId = 0) {

        $cityList = array('' => 'Select city');

        if ($stateId) {
            $cityTable = $this->getServiceLocator()->get('CityTable');

            $records = $cityTable->getRecords(array('state_id' => $stateId, 'status' => 'active'), array('city_id', 'city_name'), array('city_name asc'));

            if (count($records) > 0) {
                foreach ($records as $record) {
                    $cityList[$record['city_id']] = $record['city_name'];
                }
            }
        }
        return $cityList;
    }

    /**
     * Get state list
     * 
     * @access protected
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param integer $stateId
     * @return array
     */
    protected function getCategories() {

        $categoryList = array('' => 'Select category');

        $categoryTable = $this->getServiceLocator()->get('CategoryTable');

        $records = $categoryTable->getRecords(array('status' => 'active'), array('category_id', 'category_name'), array('category_name asc'));

        if (count($records) > 0) {
            foreach ($records as $record) {
                $categoryList[$record['category_id']] = $record['category_name'];
            }
        }

        return $categoryList;
    }

    protected function _getFileExtension($fileName) {
        $info = new \SplFileInfo($fileName);
        return $info->getExtension();
    }

    protected function _getBaseFilename($fileName) {
        $info = new \SplFileInfo($fileName);
        return $info->getBasename();
    }

    protected function _getFiles($dirPath, $filePattern = "*.*") {
        $files = glob($dirPath . $filePattern);
        return $files;
    }

    protected function _downloadFile($filePath, $extension) {

        $ctype = "application/force-download";

        switch ($extension) {
            case "pdf": $ctype = "application/pdf";
                break;
            case "exe": $ctype = "application/octet-stream";
                break;
            case "zip": $ctype = "application/zip";
                break;
            case "doc": $ctype = "application/msword";
                break;
            case "xls": $ctype = "application/vnd.ms-excel";
                break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint";
                break;
            case "gif": $ctype = "image/gif";
                break;
            case "png": $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg": $ctype = "image/jpg";
                break;
            default: $ctype = "application/force-download";
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers 
        header("Content-Type: $ctype");
        // change, added quotes to allow spaces in filenames, by Rajkumar Singh
        header("Content-Disposition: attachment; filename=\"" . basename($filePath) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filePath));
        readfile("$filePath");
        exit();
    }

    protected function _getStoreCount($userId = null) {
        $totalStoreCount = 0;
        if (!empty($userId)) {
            $storeTable = $this->getServiceLocator()->get('StoreTable');
            $records = $storeTable->getRecords(
                    array('user_id' => $userId),    
                    array('totalStoreCount' => new Expression('count(store_id)'))
            );
            $totalStoreCount = $records[0]['totalStoreCount'];            
        }
        return $totalStoreCount;
    }
    
    protected function _getOfferCount($userId = null) {
        $totalOfferCount = 0;
        if (!empty($userId)) {
            $offerTable = $this->getServiceLocator()->get('OfferTable');
            $records = $offerTable->getRecords(
                    array('user_id' => $userId),    
                    array('totalOfferCount' => new Expression('count(offer_id)'))
            );
            $totalOfferCount = $records[0]['totalOfferCount'];
        }
        return $totalOfferCount;
    }
}
