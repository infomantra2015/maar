<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use User\Form\RegisterForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;

class RegisterController extends AppController {

    public function indexAction() {
        
        $this->layout('layout/login');
        
        $this->_getHelper('HeadScript')
                ->appendFile('/js/module/user/register/register.js');

        $registerForm = new RegisterForm();
        $registerData = $this->getServiceLocator()->get('RegisterData');

        $createdOn = $this->_getHelper('dateTime');
        $registerData->setCreatedOn($createdOn());

        $registerForm->bind($registerData);

        $viewModel = new ViewModel();
        $viewModel->setVariable('registerForm', $registerForm);
        return $viewModel;
    }

    /**
     * Process user registration
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return JsonModel
     */
    public function processRegistrationAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $this->request->getPost();
            $status = $this->_validateRegisterForm($data);

            if ($status) {
                $response['message'] = Message::USER_REGISTER_SUCCESS;
                $response['status'] = 'success';
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate registration data
     * 
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @param array $data
     * @return boolean
     */
    private function _validateRegisterForm($data) {

        $isValidRegistration = false;

        $registerForm = new RegisterForm();
        $registerData = $this->getServiceLocator()->get('RegisterData');

        $registerForm->bind($registerData);
        $registerForm->setData($data);

        if ($registerForm->isValid()) {

            $data = $registerForm->getData();

            $postData = array(
                'first_name' => $data->getFirstName(),
                'last_name' => $data->getLastName(),
                'email' => $data->getEmail(),
                'password' => $data->getPassword(),
                'gender' => $data->getGender(),
                'created_on' => $data->getCreatedOn()
            );

            $passwordService = $this->getServiceLocator()->get('PasswordGenrator');
            $postData['password'] = $passwordService->create($data->getPassword());

            $userTable = $this->getServiceLocator()->get('UserTable');
            $userId = $userTable->saveRecord($postData);

            if ($userId) {

                $userRoleTable = $this->getServiceLocator()->get('UserRoleTable');

                $userRoleIds = $data->getUserRoleId();

                foreach ($userRoleIds as $roleId) {
                    $status = $userRoleTable->saveRecord(array(
                        'user_id' => $userId,
                        'role_id' => $roleId
                    ));
                }

                $isValidRegistration = true;
            }
        }

        return $isValidRegistration;
    }

}
