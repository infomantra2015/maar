<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use User\Form\LoginForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use Zend\Authentication\AuthenticationService;

class LoginController extends AppController {

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
                ->appendFile('/js/module/user/login/login.js');

        $loginForm = new LoginForm();
        $loginData = $this->getServiceLocator()->get('LoginData');
        $loginForm->bind($loginData);

        $viewModel = new ViewModel();
        $viewModel->setVariable('loginForm', $loginForm);
        return $viewModel;
    }

    /**
     * Process user login
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * @return JsonModel
     */
    public function processLoginAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $this->request->getPost();
            $status = $this->_validateLoginForm($data);

            if ($status) {
                $response['message'] = Message::USER_LOGIN_SUCCESS;
                $response['status'] = 'success';
            } else {
                $response['message'] = Message::USER_LOGIN_FAILURE;
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
    private function _validateLoginForm($data) {

        $isValidUser = false;

        $loginForm = new LoginForm();
        $loginData = $this->getServiceLocator()->get('LoginData');

        $loginForm->bind($loginData);
        $loginForm->setData($data);

        if ($loginForm->isValid()) {
            $data = $loginForm->getData();

            $email = $data->getEmail();
            $password = $data->getPassword();

            $passwordService = $this->getServiceLocator()->get('PasswordGenrator');
            $encryptedPassword = $passwordService->create($password);

            $auth = $this->getServiceLocator()->get('ZendAuth');
            $status = $auth->authenticate($email, $encryptedPassword);
            
            if ($status) {
                $isValidUser = true;
            }
        }else{
            $errors = $loginForm->getMessages();
            //prx($errors);
        }

        return $isValidUser;
    }
    
    public function logoutAction(){
        
        $auth = $this->getServiceLocator()->get('ZendAuth');
        $auth->logout();
        return $this->redirect()->toRoute('user');
    }

}
