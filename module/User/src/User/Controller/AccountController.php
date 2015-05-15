<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;
use User\Form\ProfileForm;
use Infomantra\Message\Message;
use Zend\View\Model\JsonModel;
use User\Form\AddressForm;
use User\Form\SocialForm;
use User\Form\FileUploadForm;
use Infomantra\Constant\AppConstant;

class AccountController extends AppController {

    /**
     * Render user profile form
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return ViewModel
     */
    public function indexAction() {

        $this->_getHelper('HeadLink')
                ->prependStylesheet('/js/tabs/css/tabstyles.css')
                ->prependStylesheet('/js/tabs/css/tabs.css');

        $this->_getHelper('HeadScript')
                ->appendFile('/js/tabs/js/modernizr.custom.js')
                ->appendFile('/js/tabs/js/cbpFWTabs.js')
                ->appendFile('/js/module/user/account/account.js');

        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function renderProfileAction() {

        $userId = $this->_getLoggedUserDetails('user_id');

        $userTable = $this->getServiceLocator()->get("UserTable");
        $userDetails = $userTable->getUserDetails(
                array('user.user_id' => $userId),
                array('userId' => 'user_id', 'firstName' => 'first_name', 'lastName' => 'last_name',
            'gender'), array('dob', 'mobileNumber' => 'phone_number')
        );

        $profileForm = null;

        if (count($userDetails) > 0) {

            $userDetails = $userDetails[0];

            $profileData = $this->getServiceLocator()->get('ProfileData');
            $profileForm = new ProfileForm();
            $profileData->setUserId($userId);
            $profileData->setFirstName($userDetails['firstName']);
            $profileData->setLastName($userDetails['lastName']);
            $profileData->setDob($userDetails['dob']);
            $profileData->setGender($userDetails['gender']);
            $profileData->setMobileNumber($userDetails['mobileNumber']);

            $profileForm->bind($profileData);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('profileForm', $profileForm);
        $viewModel->setVariable('userDetails', $userDetails);
        return $viewModel;
    }

    /**
     * Update user profile
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return JsonModel
     */
    public function processProfileUpdateAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            $validData = $this->_validateProfileForm($data);

            if ($validData) {

                $userTable = $this->getServiceLocator()->get("UserTable");
                $userDetailTable = $this->getServiceLocator()->get("UserDetailTable");

                $userTable->updateData(array('first_name' => $validData->getFirstName(),
                    'last_name' => $validData->getLastName(), 'gender' => $validData->getGender()),
                        array('user_id' => $validData->getUserId()));

                $userDetailTable->updateData(array('dob' => $validData->getDob(),
                    'phone_number' => $validData->getMobileNumber()),
                        array('user_id' => $validData->getUserId()));

                $response['message'] = Message::PROFILE_UPDATE_SUCCESS;
                $response['status'] = 'success';
            } else {
                $response['message'] = Message::PROFILE_UPDATE_FAILURE;
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
    private function _validateProfileForm($data) {

        $validData = false;

        $profileForm = new ProfileForm();
        $profileData = $this->getServiceLocator()->get('ProfileData');

        $profileForm->bind($profileData);
        $profileForm->setData($data);

        if ($profileForm->isValid()) {
            $data = $profileForm->getData();
            $validData = $data;
        } else {
            $errors = $profileForm->getMessages();
            //prx($errors);
        }

        return $validData;
    }

    /**
     * Render user address form
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return ViewModel
     */
    public function renderAddressAction() {

        $userId = $this->_getLoggedUserDetails('user_id');

        $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');

        $addressDetails = $userDetailTable->getRecords(array('user_id' => $userId),
                array('countryId' => 'country_id', 'stateId' => 'state_id', 'cityId' => 'city_id',
            'address'));

        $addressForm = null;

        if (count($addressDetails) > 0) {

            $addressDetails = $addressDetails[0];

            $stateList = array();
            if (!empty($addressDetails['countryId'])) {
                $stateList = $this->getStates($addressDetails['countryId']);
            }

            $cityList = array();
            if (!empty($addressDetails['stateId'])) {
                $cityList = $this->getCities($addressDetails['stateId']);
            }
            $addressForm = new AddressForm();
            $addressForm->getBaseFieldset()->get('stateId')->setOptions(array('options' => $stateList));
            $addressForm->getBaseFieldset()->get('cityId')->setOptions(array('options' => $cityList));

            $addressData = $this->getServiceLocator()->get('UserAddressData');
            $addressData->setUserId($userId);
            $addressData->setCountryId($addressDetails['countryId']);
            $addressData->setStateId($addressDetails['stateId']);
            $addressData->setCityId($addressDetails['cityId']);
            $addressData->setAddress($addressDetails['address']);
            $addressForm->bind($addressData);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('addressForm', $addressForm);

        return $viewModel;
    }

    /**
     * Update user address information
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return JsonModel
     */
    public function processUserAddressUpdateAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            $validData = $this->_validateUserAddressForm($data);

            if ($validData) {

                $userDetailTable = $this->getServiceLocator()->get("UserDetailTable");

                $status = $userDetailTable->updateData(array('country_id' => $validData->getCountryId(),
                    'state_id' => $validData->getStateId(), 'city_id' => $validData->getCityId(),
                    'address' => $validData->getAddress()),
                        array('user_id' => $validData->getUserId()));

                if ($status) {
                    $response['message'] = Message::ADDRESS_UPDATE_SUCCESS;
                    $response['status'] = 'success';
                }
            } else {
                $response['message'] = Message::ADDRESS_UPDATE_FAILURE;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate user address form
     *
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @param array $data
     * @return boolean
     */
    private function _validateUserAddressForm($data) {

        $validData = false;

        $addressForm = new AddressForm();
        $addressData = $this->getServiceLocator()->get('UserAddressData');

        $addressForm->bind($addressData);
        $addressForm->setData($data);

        if ($addressForm->isValid()) {
            $data = $addressForm->getData();
            $validData = $data;
        } else {
            $errors = $addressForm->getMessages();
            prx($errors);
        }

        return $validData;
    }

    /**
     * Render user address form
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return ViewModel
     */
    public function renderSocialAction() {

        $userId = $this->_getLoggedUserDetails('user_id');

        $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');

        $addressDetails = $userDetailTable->getRecords(array('user_id' => $userId),
                array('facebookUrl' => 'facebook_url', 'twitterUrl' => 'twitter_url',
            'linkedinUrl' => 'linkedin_url',
            'googlePlusUrl' => 'google_plus_url', 'websiteUrl' => 'website_url'));

        $socialForm = null;

        if (count($addressDetails) > 0) {

            $addressDetails = $addressDetails[0];

            $socialForm = new SocialForm();
            $socialData = $this->getServiceLocator()->get('UserSocialData');
            $socialData->setUserId($userId);
            $socialData->setFacebookUrl($addressDetails['facebookUrl']);
            $socialData->setTwitterUrl($addressDetails['twitterUrl']);
            $socialData->setLinkedinUrl($addressDetails['linkedinUrl']);
            $socialData->setGooglePlusUrl($addressDetails['googlePlusUrl']);
            $socialData->setWebsiteUrl($addressDetails['websiteUrl']);
            $socialForm->bind($socialData);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('socialForm', $socialForm);

        return $viewModel;
    }

    /**
     * Update user address information
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return JsonModel
     */
    public function processUserSocialUpdateAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            $validData = $this->_validateUserSocialForm($data);

            if ($validData) {

                $userDetailTable = $this->getServiceLocator()->get("UserDetailTable");

                $status = $userDetailTable->updateData(array('website_url' => $validData->getWebsiteUrl(),
                    'facebook_url' => $validData->getFacebookUrl(), 'twitter_url' => $validData->getTwitterUrl(),
                    'google_plus_url' => $validData->getGooglePlusUrl(), 'linkedin_url' => $validData->getLinkedinUrl()),
                        array('user_id' => $validData->getUserId()));

                if ($status) {
                    $response['message'] = Message::ADDRESS_UPDATE_SUCCESS;
                    $response['status'] = 'success';
                }
            } else {
                $response['message'] = Message::ADDRESS_UPDATE_FAILURE;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate user address form
     *
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @param array $data
     * @return boolean
     */
    private function _validateUserSocialForm($data) {

        $validData = false;

        $socialForm = new SocialForm();
        $socialData = $this->getServiceLocator()->get('UserSocialData');

        $socialForm->bind($socialData);
        $socialForm->setData($data);

        if ($socialForm->isValid()) {
            $data = $socialForm->getData();
            $validData = $data;
        } else {
            $errors = $socialForm->getMessages();
            //prx($errors);
        }

        return $validData;
    }

    /**
     * Render user file upload form
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return ViewModel
     */
    public function renderUploadProfilePicAction() {

        $userId = $this->_getLoggedUserDetails('user_id');

        $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');

        $profileDetails = $userDetailTable->getRecords(array('user_id' => $userId),
                array('profileImage' => 'profile_image'));

        $uploadForm = null;
        $profileImage = AppConstant::DEFAULT_NO_IMAGE;

        if (count($profileDetails) > 0) {

            $profileDetails = $profileDetails[0];
            $uploadData = $this->getServiceLocator()->get('UserUploadPicData');
            $uploadData->setUserId($userId);
            if (!empty($profileDetails['profileImage'])) {
                $profileImage = $profileDetails['profileImage'];
                $uploadData->setIsProfilePicSet($profileImage);
            }
            $uploadForm = new FileUploadForm();
            $uploadForm->bind($uploadData);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('uploadForm', $uploadForm);
        $viewModel->setVariable('profileImage', $profileImage);

        return $viewModel;
    }

    public function getUserProfilePicsAction() {

        $userId = $this->_getLoggedUserDetails('user_id');

        $dirPath = AppConstant::DEFAULT_IMAGE_UPLOAD_PATH . $userId . '/profile/';

        $profilePicList = $this->_getFiles($dirPath);

        $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');
        $profileImageDetails = $userDetailTable->getRecords(
                array('user_id' => $userId), array('profile_image')
        );

        $profileImage = AppConstant::DEFAULT_NO_IMAGE;
        if (count($profileImageDetails) > 0) {
            $profileImage = basename($profileImageDetails[0]['profile_image']);
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('profilePicList', $profilePicList);
        $viewModel->setVariable('profileImage', $profileImage);
        $viewModel->setVariable('userId', $userId);
        return $viewModel;
    }

    /**
     * Update user file upload information
     *
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @return JsonModel
     */
    public function processUserFileUploadAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $validData = $this->_validateUserFileUploadForm($data);

            if ($validData) {

                $userId = $validData->getUserId();
                $imageDetails = $validData->getProfileImage();
                $isProfilePicSet = $validData->getIsProfilePicSet();
                $imagePath = AppConstant::DEFAULT_IMAGE_DB_PATH . $userId . '/profile/';
                $imageUploadPath = AppConstant::DEFAULT_IMAGE_UPLOAD_PATH . $userId . '/profile/';

                if (!is_dir($imageUploadPath)) {
                    @mkdir($imageUploadPath, 0777, true);
                }

                $fileName = AppConstant::DEFAULT_PROFILE_IMG_PREFIX . time() . '.' . $this->_getFileExtension($imageDetails['name']);
                $imageUploadPath .= $fileName;
                $imagePath .= $fileName;

                if (@move_uploaded_file($imageDetails['tmp_name'],
                                $imageUploadPath)) {

                    $status = false;

                    if (empty($isProfilePicSet)) {
                        $userDetailTable = $this->getServiceLocator()->get("UserDetailTable");

                        $status = $userDetailTable->updateData(array('profile_image' => $imagePath),
                                array('user_id' => $validData->getUserId()));
                    } else {
                        $status = true;
                    }

                    if ($status) {
                        $response['message'] = Message::UPLOAD_PICTURE_SUCCESS;
                        $response['status'] = 'success';
                    }
                }
            } else {
                $response['message'] = Message::UPLAOD_PICTURE_FAILURE;
            }
        }

        return new JsonModel($response);
    }

    /**
     * Validate user file upload form
     *
     * @access private
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     *
     * @param array $data
     * @return boolean
     */
    private function _validateUserFileUploadForm($data) {

        $validData = false;

        $uploadForm = new FileUploadForm();
        $fileData = $this->getServiceLocator()->get('UserUploadPicData');

        $uploadForm->bind($fileData);
        $uploadForm->setData($data);

        if ($uploadForm->isValid()) {
            $data = $uploadForm->getData();
            $validData = $data;
        } else {
            $errors = $uploadForm->getMessages();
            //prx($errors);
        }

        return $validData;
    }

    public function downloadUserPicAction() {

        $picId = $this->params()->fromRoute('token');
        $ext = $this->params()->fromRoute('ext');
        $userId = $this->_getLoggedUserDetails('user_id');

        $filePath = 'public\\users\\' . $userId . '\\profile\\profile_' . $picId . '.' . $ext;

        $this->_downloadFile($filePath, $ext);
        exit;
    }

    public function deleteUserPicAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();

            $picId = $data->picId;
            $ext = $data->ext;
            $userId = $this->_getLoggedUserDetails('user_id');

            $filePath = 'public\\users\\' . $userId . '\\profile\\profile_' . $picId . '.' . $ext;

            if (file_exists($filePath) && unlink($filePath)) {
                $response = array('status' => 'success', 'message' => Message::DELETE_PICTURE_SUCCESS);
            }
        }
        return new JsonModel($response);
    }

    public function setProfilePicAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();

            $picId = $data->picId;
            $ext = $data->ext;
            $userId = $this->_getLoggedUserDetails('user_id');

            $filePath = '\\users\\' . $userId . '\\profile\\profile_' . $picId . '.' . $ext;

            if (file_exists('public' . $filePath)) {
                $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');
                $filePath = str_replace("\\", "/", $filePath);
                $status = $userDetailTable->updateData(
                        array('profile_image' => $filePath),
                        array('user_id' => $userId));
                if ($status) {
                    $response = array('status' => 'success', 'message' => Message::DELETE_PICTURE_SUCCESS);
                }
            }
        }
        return new JsonModel($response);
    }

    public function removeProfilePicAction() {

        $response = array('status' => 'error', 'message' => Message::GENRAL_ERROR_MESSAGE);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();

            $picId = $data->picId;
            $ext = $data->ext;
            $userId = $this->_getLoggedUserDetails('user_id');

            $filePath = 'public\\users\\' . $userId . '\\profile\\profile_' . $picId . '.' . $ext;

            if (file_exists($filePath)) {
                $userDetailTable = $this->getServiceLocator()->get('UserDetailTable');
                $status = $userDetailTable->updateData(
                        array('profile_image' => AppConstant::DEFAULT_NO_IMAGE),
                        array('user_id' => $userId));
                if ($status) {
                    $response = array('status' => 'success', 'message' => Message::REMOVE_PROFILE_PICTURE_SUCCESS);
                }
            }
        }
        return new JsonModel($response);
    }

}
