<?php

namespace Store\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use Store\Form\OfferForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use Infomantra\Constant\AppConstant;

class OfferController extends AppController {

    public function manageOfferAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function renderOfferFormAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $data = $request->getPost();

            $userId = $this->_getLoggedUserDetails('user_id');
            $offerId = (isset($data['offerId']) && !empty($data['offerId'])) ? $data['offerId'] : 0;

            $offerForm = new OfferForm();
            $offerData = $this->getServiceLocator()->get('OfferData');

            if (!empty($offerId)) {

                $offerTable = $this->getServiceLocator()->get('OfferTable');

                $offerDetails = $offerTable->getRecords(array('offer_id' => $offerId,
                    'user_id' => $userId),
                        array('offerId' => 'offer_id', 'offerTitle' => 'title',
                    'offerDescription' => 'description', 'validFrom' => 'valid_from',
                    'validTo' => 'valid_to', 'storeStatus' => 'status'));

                if (count($offerDetails) > 0) {
                    $offerDetails = $offerDetails[0];

                    $offerData->setOfferId($offerDetails['offerId']);
                    $offerData->setOfferTitle($offerDetails['offerTitle']);
                    $offerData->setOfferDescription($offerDetails['offerDescription']);
                    $offerData->setValidFrom($offerDetails['validFrom']);
                    $offerData->setValidTo($offerDetails['validTo']);
                    $offerData->setStatus($offerDetails['storeStatus']);
                }
            }

            $offerForm->bind($offerData);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariable('offerForm', $offerForm);
            $viewModel->setVariable('offerId', $offerId);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function processOfferUpdateAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');
            $data = $request->getPost();

            $validData = $this->_validateOfferForm($data);

            $response = $this->_manipulateOffer($userId, $validData);

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
    private function _validateOfferForm($data) {

        $validData = array();

        $offerForm = new OfferForm();
        $offerData = $this->getServiceLocator()->get('OfferData');

        $offerForm->bind($offerData);
        $offerForm->setData($data);

        if ($offerForm->isValid()) {
            $validData = $offerForm->getData();
        } else {
            $errors = $offerForm->getMessages();
            prx($errors);
        }

        return $validData;
    }

    private function _manipulateOffer($userId, $offerData = array()) {

        try {

            $response = array('status' => true, 'message' => Message::GENRAL_ERROR_MESSAGE);

            if (count($offerData) > 0) {

                $createdOn = $this->_getHelper('DateTime');
                $offerId = $offerData->getOfferId();
                $offerTable = $this->getServiceLocator()->get('OfferTable');

                $offerDetails = array(
                    'title' => $offerData->getOfferTitle(),
                    'description' => $offerData->getOfferDescription(),
                    'valid_from' => $offerData->getValidFrom(),
                    'valid_to' => $offerData->getValidTo(),
                    'status' => $offerData->getStatus(),
                );

                $status = false;

                if (!empty($offerId)) {
                    $status = $offerTable->updateData($offerDetails,
                            array('offer_id' => $offerId, 'user_id' => $userId));
                } else {

                    $offerDetails['user_id'] = $userId;
                    $offerDetails['created_on'] = $createdOn(null,
                            AppConstant::DEFAULT_DATE_FORMAT);

                    $status = $offerTable->saveRecord($offerDetails);
                }

                if (!$status) {
                    $response['status'] = false;
                } else {
                    $response['message'] = (empty($offerId)) ? Message::OFFER_ADDED_SUCCESS : Message::OFFER_UPDATED_SUCCESS;
                }
            } else {
                $response['status'] = false;
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getOfferListAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');

            $data = $request->getPost()->toArray();

            $page = $this->params()->fromRoute('page');
            $pageNo = !empty($page) ? $page : AppConstant::DEFAULT_PAGE_NUMBER;

            $columnName = isset($data['field']) ? $data['field'] : 'title';
            $order = isset($data['order']) ? $data['order'] : 'asc';
            $orderBy = $columnName . ' ' . $order;

            $offerTable = $this->getServiceLocator()->get('OfferTable');

            $paginator = $offerTable->getRecords(array('user_id' => $userId),
                    array('offer_id', 'title', 'status', 'created_on'),
                    array($orderBy), true);

            $paginator->setCurrentPageNumber($pageNo);
            $paginator->setItemCountPerPage(AppConstant::DEFAULT_RECORDS_PER_PAGE);

            $offerList = (array) json_decode($paginator->toJson());

            $viewModel = new ViewModel();
            $viewModel->setVariable('page', $pageNo);
            $viewModel->setVariable('order', $order);
            $viewModel->setVariable('columnName', $columnName);
            $viewModel->setVariable('paginator', $paginator);
            $viewModel->setVariable('offerList', $offerList);
            $viewModel->setVariable('recordsPerPage',
                    AppConstant::DEFAULT_RECORDS_PER_PAGE);

            $viewModel->setTerminal(true);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function viewOfferAction() {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');

            $data = $request->getPost()->toArray();

            $offerId = $data['offerId'];

            $offerTable = $this->getServiceLocator()->get('OfferTable');
            $offerDetails = $offerTable->getRecords(array('user_id' => $userId,
                'offer_id' => $offerId),
                    array('title', 'description', 'valid_from', 'valid_to', 'created_on',
                'modified_on',
                'status'));

            if (count($offerDetails) > 0) {
                $offerDetails = $offerDetails[0];
            }

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariable('offerDetails', $offerDetails);

            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function assignOfferToStoreAction() {

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {

            $userId = $this->_getLoggedUserDetails('user_id');
            $offerId = $this->params()->fromRoute('offerId');

            $storeTable = $this->getServiceLocator()->get('StoreTable');

            $storeList = $storeTable->getStoreDetails(array(
                'stores.user_id' => $userId, 'stores.status' => 'active'),
                    array('storeId' => 'store_id', 'storeName' => 'store_name',
                'storeAddress' => 'address'), array('store_name ASC'), false,
                    array('STORE_OFFERS', 'OFFER_ID' => $offerId));

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariable('offerId', $offerId);
            $viewModel->setVariable('storeList', $storeList);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

    public function processOfferAssignmentAction() {

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {

            $response = array('status' => true, 'message' => Message::GENRAL_ERROR_MESSAGE);

            $data = $request->getPost();

            $userId = $this->_getLoggedUserDetails('user_id');
            $selectedOfferId = $data['selectedOfferId'];
            $storeIds = $data['storeIds'];

            if (!empty($selectedOfferId) && count($storeIds) > 0) {

                $this->beginTransaction();

                $storeOfferTable = $this->getServiceLocator()->get('StoreOfferTable');

                $storeOfferTable->deleteRecords(array('user_id' => $userId, 'offer_id' => $selectedOfferId));

                $createdOn = $this->_getHelper('DateTime');

                $saveData = array('user_id' => $userId, 'offer_id' => $selectedOfferId,
                    'created_on' => $createdOn(null,
                            AppConstant::DEFAULT_DATE_FORMAT));

                $processStatus = true;

                foreach ($storeIds as $storeId) {

                    if (!$processStatus) {
                        continue;
                    }

                    $saveData['store_id'] = $storeId;
                    $status = $storeOfferTable->saveRecord($saveData);

                    if (!$status) {
                        $processStatus = false;
                    }
                }

                if ($processStatus) {
                    $this->commitTransaction();
                    $response['message'] = Message::OFFER_ASSIGNMENT_SUCCESS;
                    $response['status'] = 'success';
                } else {
                    $this->rollbackTransaction();
                }
            }

            return new JsonModel($response);
        } else {
            return $this->redirect()->toRoute('dashboard');
        }
    }

}
