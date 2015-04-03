<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use User\Form\ForgotPasswordForm;
use User\Form\ResetPasswordForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use Infomantra\Constant\AppConstant;

class ForgotPasswordController extends AppController {

    /**
     * Render login form
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return ViewModel
     */
    public function indexAction() {
        
        $this->layout('layout/login');
        
        $this->_getHelper('HeadScript')
                ->appendFile('/js/module/user/forgot-password/forgot-password.js');

        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordData = $this->getServiceLocator()->get('ForgotPasswordData');
        $forgotPasswordForm->bind($forgotPasswordData);

        $viewModel = new ViewModel();
        $viewModel->setVariable('forgotPasswordForm', $forgotPasswordForm);
        return $viewModel;
    }

    /**
     * Process user login
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return JsonModel
     */
    public function processForgotPasswordAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $this->request->getPost();
            $userId = $this->_validateForgotPasswordForm($data);

            if ($userId) {

                $forgotPasswordToken = $this->manageEncryption(AppConstant::DEFAULT_SALT . time());

                $userTable = $this->getServiceLocator()->get("UserTable");
                $status = $userTable->updateData(array('forgot_password_token' => $forgotPasswordToken),
                        array('user_id' => $userId));

                if ($status) {
                    $response['message'] = Message::USER_FORGOT_PASSWORD_SUCESS;
                    $response['status'] = 'success';
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
    private function _validateForgotPasswordForm($data) {

        $userId = 0;

        $forgotPasswordForm = new ForgotPasswordForm();
        $forgotPasswordData = $this->getServiceLocator()->get('ForgotPasswordData');

        $forgotPasswordForm->bind($forgotPasswordData);
        $forgotPasswordForm->setData($data);

        if ($forgotPasswordForm->isValid()) {
            $data = $forgotPasswordForm->getData();

            $email = $data->getEmail();

            $userTable = $this->getServiceLocator()->get("UserTable");

            $record = $userTable->getRecords(array('email' => $email, 'status' => 'active'),
                    array('user_id'));

            if (count($record) > 0) {
                $userId = $record[0]['user_id'];
            }
        }

        return $userId;
    }

    public function resetPasswordAction() {

        $token = $this->params()->fromRoute('token');

        if (!empty($token)) {
            $this->_getHelper('HeadScript')
                    ->appendFile('/js/module/user/forgot-password/reset-password.js');

            $resetPasswordData = $this->getServiceLocator()->get('ResetPasswordData');
            $resetPasswordData->setResetPasswordToken($token);

            $resetPasswordForm = new ResetPasswordForm();
            $resetPasswordForm->bind($resetPasswordData);

            $viewModel = new ViewModel();
            $viewModel->setVariable('resetPasswordForm', $resetPasswordForm);
            return $viewModel;
        } else {
            return $this->redirect()->toRoute('user');
        }
    }

    /**
     * Process user login
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return JsonModel
     */
    public function processResetPasswordAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $data = $request->getPost();

            $userDetails = $this->_validateResetPasswordForm($data);

            if (count($userDetails) > 0) {

                $passwordService = $this->getServiceLocator()->get('PasswordGenrator');

                $newPassword = $passwordService->create($userDetails['newPassword']);
                $currentPassword = $passwordService->create($userDetails['currentPassword']);
                $forgotPasswordToken = $userDetails['resetPasswordToken'];
                $userId = $userDetails['userId'];

                $userTable = $this->getServiceLocator()->get("UserTable");
                $status = $userTable->updateData(
                        array('password' => $newPassword, 'forgot_password_token' => null),
                        array(
                    'user_id' => $userId,
                    'password' => $currentPassword,
                    'forgot_password_token' => $forgotPasswordToken,
                    'status' => 'active'
                        )
                );

                if ($status) {
                    $response['message'] = Message::USER_RESET_PASSWORD_SUCESS;
                    $response['status'] = 'success';
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
    private function _validateResetPasswordForm($data) {

        $userDetails = array();

        $resetPasswordForm = new ResetPasswordForm();
        $resetPasswordData = $this->getServiceLocator()->get('ResetPasswordData');

        $resetPasswordForm->bind($resetPasswordData);
        $resetPasswordForm->setData($data);

        if ($resetPasswordForm->isValid()) {
            $data = $resetPasswordForm->getData();

            $forgotPasswordToken = $data->getResetPasswordToken();
            $userTable = $this->getServiceLocator()->get("UserTable");

            $record = $userTable->getRecords(array('forgot_password_token' => $forgotPasswordToken,
                'status' => 'active'), array('user_id'));

            if (count($record) > 0) {
                $userDetails['userId'] = $record[0]['user_id'];
                $userDetails['newPassword'] = $data->getPassword();
                $userDetails['currentPassword'] = $data->getCurrentPassword();
                $userDetails['resetPasswordToken'] = $forgotPasswordToken;
            }
        }

        return $userDetails;
    }

}
