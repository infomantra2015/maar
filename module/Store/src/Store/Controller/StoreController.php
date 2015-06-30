<?php

namespace Store\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use Store\Form\StoreForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use Infomantra\Constant\AppConstant;
use Store\Form\FileUploadForm;

class StoreController extends AppController {

    public function manageStoreAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function renderStoreFormAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $data = $request->getPost();

            $userId = $this->_getLoggedUserDetails('user_id');
            $storeId = (isset($data['storeId']) && !empty($data['storeId'])) ? $data['storeId'] : 0;

            $storeForm = new StoreForm();
            $storeData = $this->getServiceLocator()->get('StoreData');

            $categoryList = $this->getCategories();
            $storeForm->getBaseFieldset()->get('categoryId')->setOptions(array(
                'options' => $categoryList));
            if (!empty($storeId)) {

                $storeTable = $this->getServiceLocator()->get('StoreTable');

                $storeDetails = $storeTable->getRecords(array('store_id' => $storeId,
                    'user_id' => $userId), array('categoryId' => 'category_id', 'storeId' => 'store_id',
                    'storeName' => 'store_name',
                    'storeAddress' => 'address', 'storeDescription' => 'description',
                    'countryId' => 'country_id', 'stateId' => 'state_id', 'cityId' => 'city_id',
                    'storeStatus' => 'status'));

                if (count($storeDetails) > 0) {
                    $storeDetails = $storeDetails[0];

                    $stateList = array();
                    if (!empty($storeDetails['countryId'])) {
                        $stateList = $this->getStates($storeDetails['countryId']);
                    }

                    $cityList = array();
                    if (!empty($storeDetails['stateId'])) {
                        $cityList = $this->getCities($storeDetails['stateId']);
                    }

                    $storeForm->getBaseFieldset()->get('stateId')->setOptions(array(
                        'options' => $stateList));
                    $storeForm->getBaseFieldset()->get('cityId')->setOptions(array(
                        'options' => $cityList));

                    $storeData->setCategoryId($storeDetails['categoryId']);
                    $storeData->setStoreId($storeDetails['storeId']);
                    $storeData->setStoreName($storeDetails['storeName']);
                    $storeData->setCountryId($storeDetails['countryId']);
                    $storeData->setStateId($storeDetails['stateId']);
                    $storeData->setCityId($storeDetails['cityId']);
                    $storeData->setStoreAddress($storeDetails['storeAddress']);
                    $storeData->setStoreDescription($storeDetails['storeDescription']);
                    $storeData->setStatus($storeDetails['storeStatus']);
                }
            }

            $storeForm->bind($storeData);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariable('storeForm', $storeForm);
            $viewModel->setVariable('storeId', $storeId);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function processStoreUpdateAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');
            $data = $request->getPost();

            $validData = $this->_validateStoreForm($data);

            $response = $this->_manipulateStore($userId, $validData);

            if ($response['status']) {
                $response['status'] = 'success';
                $response['message'] = $response['message'];
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate store data
     * 
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param array $data
     * @return boolean
     */
    private function _validateStoreForm($data) {

        $validData = array();

        $storeForm = new StoreForm();
        $storeData = $this->getServiceLocator()->get('StoreData');

        $storeForm->bind($storeData);
        $storeForm->setData($data);

        if ($storeForm->isValid()) {
            $validData = $storeForm->getData();
        } else {
            $errors = $storeForm->getMessages();
        }

        return $validData;
    }

    private function _manipulateStore($userId, $storeData = array()) {

        try {

            $response = array('status' => true, 'message' => Message::GENRAL_ERROR_MESSAGE);

            if (count($storeData) > 0) {

                $createdOn = $this->_getHelper('DateTime');
                $storeId = $storeData->getStoreId();
                $storeTable = $this->getServiceLocator()->get('StoreTable');

                $storeDetails = array(
                    'category_id' => $storeData->getCategoryId(),
                    'country_id' => $storeData->getCountryId(),
                    'state_id' => $storeData->getStateId(),
                    'city_id' => $storeData->getCityId(),
                    'store_name' => $storeData->getStoreName(),
                    'address' => $storeData->getStoreAddress(),
                    'description' => $storeData->getStoreDescription(),
                    'status' => $storeData->getStatus()
                );

                $status = false;

                if (!empty($storeId)) {
                    $status = $storeTable->updateData($storeDetails, array('store_id' => $storeId, 'user_id' => $userId));
                } else {

                    $storeDetails['user_id'] = $userId;
                    $storeDetails['created_on'] = $createdOn(null, AppConstant::DEFAULT_DATE_FORMAT);

                    $status = $storeTable->saveRecord($storeDetails);
                }

                if (!$status) {
                    $response['status'] = false;
                } else {
                    $response['message'] = (empty($storeId)) ? Message::STORE_ADDED_SUCCESS : Message::STORE_UPDATED_SUCCESS;
                }
            } else {
                $response['status'] = false;
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getStoreListAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');

            $data = $request->getPost()->toArray();

            $page = $this->params()->fromRoute('page');
            $pageNo = !empty($page) ? $page : AppConstant::DEFAULT_PAGE_NUMBER;

            $columnName = isset($data['field']) ? $data['field'] : 'store_name';
            $order = isset($data['order']) ? $data['order'] : 'asc';
            $orderBy = $columnName . ' ' . $order;

            $storeTable = $this->getServiceLocator()->get('StoreTable');

            $paginator = $storeTable->getRecords(array('user_id' => $userId), array('store_id', 'store_name', 'status', 'created_on'), array($orderBy), true);

            $paginator->setCurrentPageNumber($pageNo);
            $paginator->setItemCountPerPage(AppConstant::DEFAULT_RECORDS_PER_PAGE);

            $storeList = (array) json_decode($paginator->toJson());

            $viewModel = new ViewModel();
            $viewModel->setVariable('page', $pageNo);
            $viewModel->setVariable('order', $order);
            $viewModel->setVariable('columnName', $columnName);
            $viewModel->setVariable('paginator', $paginator);
            $viewModel->setVariable('storeList', $storeList);
            $viewModel->setVariable('recordsPerPage', AppConstant::DEFAULT_RECORDS_PER_PAGE);

            $viewModel->setTerminal(true);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function viewStoreAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');

            $data = $request->getPost()->toArray();

            $storeId = $data['storeId'];

            $storeTable = $this->getServiceLocator()->get('StoreTable');
            $storeDetails = $storeTable->getStoreDetails(array('user_id' => $userId,
                'store_id' => $storeId), array('store_name', 'address', 'description', 'created_on', 'modified_on',
                'status'));

            if (count($storeDetails) > 0) {
                $storeDetails = $storeDetails[0];
            }

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariable('storeDetails', $storeDetails);

            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function removeStoreOffersAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $response = array('status' => true, 'message' => Message::GENRAL_ERROR_MESSAGE);

            $data = $request->getPost()->toArray();

            $userId = $this->_getLoggedUserDetails('user_id');
            $storeId = $data['storeId'];

            $storeOfferTable = $this->getServiceLocator()->get('StoreOfferTable');

            $affectedRows = $storeOfferTable->deleteRecords(array('user_id' => $userId,
                'store_id' => $storeId));

            if (count($affectedRows) > 0) {
                $response['status'] = 'success';
                $response['message'] = Message::OFFER_REMOVAL_SUCCESS;
            }

            return new JsonModel($response);
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    /**
     * Render store file upload form
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return ViewModel
     */
    public function renderStorePicturesAction() {

        $request = $this->getRequest();
        $data = $request->getPost()->toArray();

        $userId = $this->_getLoggedUserDetails('user_id');
        $storeId = isset($data['storeId']) ? $data['storeId'] : 0;

        $storeTable = $this->getServiceLocator()->get('StoreTable');

        $storeDetails = $storeTable->getRecords(array('store_id' => $storeId, 'user_id' => $userId), array('storeImage' => 'store_pic'));

        $uploadForm = null;
        $storeImage = AppConstant::DEFAULT_NO_IMAGE;

        if (count($storeDetails) > 0) {

            $storeDetails = $storeDetails[0];
            $uploadData = $this->getServiceLocator()->get('StoreUploadPicData');
            $uploadData->setStoreId($storeId);
            $uploadData->setUserId($userId);

            if (!empty($storeDetails['storeImage'])) {
                $storeImage = $storeDetails['storeImage'];
                $uploadData->setIsStoreLogoSet($storeImage);
            }

            $uploadForm = new FileUploadForm();
            $uploadForm->bind($uploadData);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('uploadForm', $uploadForm);
        $viewModel->setVariable('storeImage', $storeImage);
        $viewModel->setVariable('storeId', $storeId);
        return $viewModel;
    }

    public function renderStoreTimingsAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $response = array('status' => true, 'message' => Message::GENRAL_ERROR_MESSAGE);

            $data = $request->getPost()->toArray();

            $userId = $this->_getLoggedUserDetails('user_id');

            return new JsonModel($response);
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    /**
     * Update user file upload information
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return JsonModel
     */
    public function processStoreFileUploadAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $validData = $this->_validateStoreFileUploadForm($data);

            if ($validData) {

                $storeId         = $validData->getStoreId();
                $userId          = $validData->getUserId();
                $imageDetails    = $validData->getStoreImage();
                $isStoreLogoSet  = $validData->getIsStoreLogoSet();
                $imagePath       = AppConstant::DEFAULT_IMAGE_DB_PATH . $userId . '/store/' . $storeId . '/';
                $imageUploadPath = AppConstant::DEFAULT_IMAGE_UPLOAD_PATH . $userId . '/store/' . $storeId . '/';

                if (!is_dir($imageUploadPath)) {
                    @mkdir($imageUploadPath, 0777, true);
                }

                $fileName         = AppConstant::DEFAULT_STORE_IMG_PREFIX . time() . '.' . $this->_getFileExtension($imageDetails['name']);
                $imageUploadPath .= $fileName;
                $imagePath       .= $fileName;

                if (@move_uploaded_file($imageDetails['tmp_name'], $imageUploadPath)) {

                    $status = false;

                    if (empty($isStoreLogoSet)) {
                        $storeTable = $this->getServiceLocator()->get("StoreTable");
                        $status = $storeTable->updateData(array('store_pic' => $imagePath), array('user_id' => $userId, 'store_id' => $storeId));
                    } else {
                        $status = true;
                    }

                    if ($status) {
                        $response['message'] = Message::UPLOAD_PICTURE_SUCCESS;
                        $response['status']  = 'success';
                        $response['storeId'] = $storeId;
                    }
                }
            } else {
                $response['message'] = Message::UPLAOD_PICTURE_FAILURE;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate store file upload form
     *
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @param array $data
     * @return boolean
     */
    private function _validateStoreFileUploadForm($data) {

        $validData = false;

        $uploadForm = new FileUploadForm();
        $fileData = $this->getServiceLocator()->get('StoreUploadPicData');

        $uploadForm->bind($fileData);
        $uploadForm->setData($data);

        if ($uploadForm->isValid()) {
            $data = $uploadForm->getData();
            $validData = $data;
        } else {
            $errors = $uploadForm->getMessages();
            prx($errors);
        }

        return $validData;
    }
    
    public function getStorePictureListAction() {
        
        $request = $this->getRequest();
        $data    = $request->getPost()->toArray();
        
        $storeId = isset($data['storeId']) ? $data['storeId'] : 0;
        $userId  = $this->_getLoggedUserDetails('user_id');

        $dirPath = AppConstant::DEFAULT_IMAGE_UPLOAD_PATH . $userId . '/store/' . $storeId . '/';
        
        $storePictureList = $this->_getFiles($dirPath);
        
        $storeTable = $this->getServiceLocator()->get('StoreTable');
        $storeImageDetails = $storeTable->getRecords(
            array('user_id' => $userId, 'store_id' => $storeId), array('storeLogo' => 'store_pic')
        );

        $storeLogo = AppConstant::DEFAULT_NO_IMAGE;
        if (count($storeImageDetails) > 0) {
            $storeLogo = basename($storeImageDetails[0]['storeLogo']);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('storePictureList', $storePictureList);
        $viewModel->setVariable('storeLogo', $storeLogo);
        $viewModel->setVariable('userId', $userId);
        $viewModel->setVariable('storeId', $storeId);
        return $viewModel;
    }
    
    public function downloadStorePicAction() {

        $userId   = $this->_getLoggedUserDetails('user_id');
        $storeId  = $this->params()->fromRoute('storeId');
        $picId    = $this->params()->fromRoute('token');        
        $ext      = $this->params()->fromRoute('ext');
        
        $filePath = 'public\\users\\' . $userId . '\\store\\' . $storeId . '\\store_' . $picId . '.' . $ext;

        $this->_downloadFile($filePath, $ext);
        exit;
    }
    
    public function deleteStorePicAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            
            $storeId  = $data->storeId;
            $picId    = $data->picId;
            $ext      = $data->ext;
            $userId   = $this->_getLoggedUserDetails('user_id');

            $filePath = 'public\\users\\' . $userId . '\\store\\'. $storeId .'\\store_' . $picId . '.' . $ext;

            if (file_exists($filePath) && unlink($filePath)) {
                $response = array('status' => 'success', 'message' => Message::DELETE_STORE_PICTURE_SUCCESS, 'storeId' => $storeId);
            }
        }
        return new JsonModel($response);
    }

    public function setStoreLogoAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            
            $storeId  = $data->storeId;
            $picId    = $data->picId;
            $ext      = $data->ext;
            $userId   = $this->_getLoggedUserDetails('user_id');

            $filePath = '\\users\\' . $userId . '\\store\\'. $storeId .'\\store_' . $picId . '.' . $ext;

            if (file_exists('public' . $filePath)) {
                $storeTable = $this->getServiceLocator()->get('StoreTable');
                $filePath = str_replace("\\", "/", $filePath);
                $status = $storeTable->updateData(
                            array('store_pic' => $filePath),
                            array(
                                'user_id'   => $userId, 
                                'store_id' => $storeId
                            )
                        );
                
                if ($status) {
                    $response = array('status' => 'success', 'message' => Message::SET_STORE_LOGO_SUCCESS, 'storeId' => $storeId);
                }
            }
        }
        return new JsonModel($response);
    }

    public function removeStoreLogoAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);
        $request  = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            
            $data     = $request->getPost();            
            $storeId  = $data->storeId;
            $picId    = $data->picId;
            $ext      = $data->ext;
            $userId   = $this->_getLoggedUserDetails('user_id');

            $filePath = 'public\\users\\' . $userId . '\\store\\'. $storeId .'\\store_' . $picId . '.' . $ext;
            
            if (file_exists($filePath)) {
                $storeTable = $this->getServiceLocator()->get('StoreTable');
                $status     = $storeTable->updateData(
                                array('store_pic' => AppConstant::DEFAULT_NO_IMAGE),
                                array(
                                    'user_id'  => $userId, 
                                    'store_id' => $storeId
                                )
                            );
                
                if ($status) {
                    $response = array('status' => 'success', 'message' => Message::REMOVE_STORE_LOGO_SUCCESS, 'storeId' => $storeId);
                }
            }
        }
        return new JsonModel($response);
    }

}
