<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use User\Form\ChangePasswordForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use Infomantra\Constant\AppConstant;

class ChangePasswordController extends AppController {

    public function indexAction() {

        $this->_getHelper('HeadScript')
                ->appendFile('/js/module/user/change-password/change-password.js');

        $changePasswordData = $this->getServiceLocator()->get('ResetPasswordData');
        $token = $this->manageEncryption(AppConstant::DEFAULT_SALT . time());
        $changePasswordData->setResetPasswordToken($token);

        $changePasswordForm = new ChangePasswordForm();
        $changePasswordForm->bind($changePasswordData);

        $viewModel = new ViewModel();
        $viewModel->setVariable('changePasswordForm', $changePasswordForm);
        return $viewModel;
    }

    /**
     * Process user login
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return JsonModel
     */
    public function processChangePasswordAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $data = $request->getPost();

            $userDetails = $this->_getLoggedUserDetails();
            
            if (count($userDetails) > 0) {

                $validData = $this->_validateChangePasswordForm($data);

                if (count($validData) > 0) {

                    $passwordService = $this->getServiceLocator()->get('PasswordGenrator');

                    $userId = $userDetails->user_id;
                    $newPassword = $passwordService->create($validData['newPassword']);
                    $currentPassword = $passwordService->create($validData['currentPassword']);
                    
                    $userTable = $this->getServiceLocator()->get("UserTable");
                    
                    $where = array(
                        'user_id' => $userId,
                        'password' => $newPassword,
                        'status' => 'active'
                    );
                    
                    $userDetails = $userTable->getRecords($where);
                    
                    if(count($userDetails) > 0){
                        $response['message'] = Message::PREVIOUS_SAME_PASSWORD_ERROR;
                    }else{
                        
                        $where['password'] = $currentPassword;
                        
                        $status = $userTable->updateData(
                            array('password' => $newPassword), $where
                        );

                        if ($status) {
                            $response['message'] = Message::USER_RESET_PASSWORD_SUCESS;
                            $response['status'] = 'success';
                        }
                    }                    
                }
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate login data
     * 
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param array $data
     * @return boolean
     */
    private function _validateChangePasswordForm($data) {

        $userDetails = array();

        $changePasswordForm = new ChangePasswordForm();
        $changePasswordData = $this->getServiceLocator()->get('ResetPasswordData');

        $changePasswordForm->bind($changePasswordData);
        $changePasswordForm->setData($data);

        if ($changePasswordForm->isValid()) {
            $data = $changePasswordForm->getData();
            
            $userDetails['newPassword'] = $data->getPassword();
            $userDetails['currentPassword'] = $data->getCurrentPassword();
        }
        
        return $userDetails;
    }

}
