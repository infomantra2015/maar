<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Message;

class Message{
    
    const GENRAL_ERROR_MESSAGE = 'There was some error. Please try again.';
    const ALPHABETS_REQUIRED_ONLY = 'Only alphabets are allowed.';
    
    const USER_LOGIN_FAILURE = 'Please enter valid email or password.';
    const USER_LOGIN_SUCCESS = 'You are successfully logged in.';
    
    const USER_FORGOT_PASSWORD_SUCESS = 'Please check you email for password reset link.';
    const USER_RESET_PASSWORD_SUCESS = 'Your password has been changed successfully.';
    const PREVIOUS_SAME_PASSWORD_ERROR = 'Your new password is same as the previous password. Please enter diifrent password.';
    
    const USER_REGISTER_SUCCESS = 'You are successfully registered with Info-Manatra.';
    
    const EMAIL_IS_REQUIRED = 'Email is required.';
    const VALID_EMAIL_REQUIRED = 'Valid email is required.';
    const INVALID_EMAIL_LENGTH = 'Email can be 50 characters long.';
    
    const PASSWORD_IS_REQUIRED = 'Password is required.';
    const CURRENT_PASSWORD_IS_REQUIRED = 'Current Password is required.';
    const CONFIRM_PASSWORD_IS_REQUIRED = 'Confirm Password is required.';
    const INVALID_PASSWORD_LENGTH = 'Password must be 6 characters long atleast.';
    const CONFIRM_PASSWORD_NOT_MATCHED = 'Confirm Password not matched with password.';
    const FORGOT_PASSWORD_TOKEN_MISSING = 'Forgot Password Token is missing.';
    const INVALID_FORGOT_PASSWORD_TOKEN = 'Forgot Password Token must be 255 characters long.';
    
    const FIRSTNAME_IS_REQUIRED = 'First Name is required.';
    const INVALID_FISRT_NAME_LENGTH = 'First Name can be 50 characters long.';
    
    const LASTNAME_IS_REQUIRED = 'Last Name is required.';    
    const INVALID_LAST_NAME_LENGTH = 'Last Name can be 50 characters long.';
    
    const GENDER_IS_REQUIRED = 'Gender is required.';
    const VALID_GENDER_REQUIRED = 'Valid Gender is required.';
    
    const MEMBER_TYPE_REQUIRED = 'Member Type is required.';
    
    const USER_ID_IS_REQUIRED = 'User id is missing.';
    const COUNTRY_IS_REQUIRED = 'Country is required.';
    const CATEGORY_IS_REQUIRED = 'Category is required.';
    const STATUS_IS_REQUIRED = 'Status is required.';
    const STATE_IS_REQUIRED = 'Please select state.';
    const CITY_IS_REQUIRED = 'Please select city.';
    const DESCRIPTION_IS_REQUIRED = 'Description is required.';
    const OFFER_VALID_FROM_REQUIRED = 'Valid From date is required.';
    const OFFER_VALID_TO_REQUIRED = 'Valid To date is required.';
    const INVALID_TO_DATE = 'Valid To date must be equal or greater than Valid From date.';
    const ADDRESS_IS_REQUIRED = 'Address is required.';
    const DOB_IS_REQUIRED = 'Date Of Birth is required.';
    const MOBILE_IS_REQUIRED = 'Mobile Number is required.';
    const INVALID_MOBILE = 'Valid Mobile Number is required.';
    const FILE_IS_REQUIRED = 'Please upload a file.';
    const INVALID_FILE_SIZE = 'File size is more than 2MB.';
    const INVALID_FILE_EXTENTION = 'Only gif,png,jpg and jpeg image extensions are allowed.';
        
    const STORE_NAME_IS_REQUIRED = 'Store Name is required.';
    
    const DIGIT_REQUIRED = 'Please enter digits only.';
    const VALID_URL_REQUIRED = 'Please enter valid url only.';
    
    const PROFILE_UPDATE_SUCCESS = 'Your profile updated successfully.';
    const PROFILE_UPDATE_FAILURE = 'Your profile not updated. Please try again.';
    
    const ADDRESS_UPDATE_SUCCESS = 'Your address updated successfully.';
    const ADDRESS_UPDATE_FAILURE = 'Your address not updated. Please try again.';
    
    const UPLOAD_PICTURE_SUCCESS = 'Your picture uploaded successfully.';
    const UPLAOD_PICTURE_FAILURE = 'Your picture not uploaded. Please try again.';
    const DELETE_PICTURE_SUCCESS = 'Your picture deleted successfully.';
    
    const STORE_ADDED_SUCCESS = 'Your store added successfully.';    
    const STORE_UPDATED_SUCCESS = 'Your store updated successfully.';
    
    const TITLE_IS_REQUIRED = 'Tilte is required.';
    
    const OFFER_ADDED_SUCCESS = 'Your offer added successfully.';    
    const OFFER_UPDATED_SUCCESS = 'Your offer updated successfully.';
    
    const OFFER_ASSIGNMENT_SUCCESS = 'Offer has been assinged to selected store(s) successfully.';
    const OFFER_REMOVAL_SUCCESS = 'Offer(s) removed from th selected store successfully.';
    
}