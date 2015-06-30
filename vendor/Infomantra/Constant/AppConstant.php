<?php

/*
 * To change this license header; choose License Headers in Project Properties.
 * To change this template file; choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Constant;

class AppConstant {

    const APP_NAME = 'Info Mantra';
    const DEFAULT_ROLE = 'guest';
    const BCRYT_COST = 14;
    const MAX_EMAIL_LENGTH = 50;
    const MAX_PHONE_NUMBER_LENGTH = 15;
    const MAX_FIRST_NAME_LENGTH = 50;
    const MAX_LAST_NAME_LENGTH = 50;
    const MIN_PASSWORD_LENGTH = 6;
    const FORGOT_PASSWORD_TOKEN_LENGTH = 255;
    const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
    const DEFAULT_SALT = '*%!L2Hf)^Nl';
    const MAX_FILE_SIZE = '4096000';
    const DEFAULT_PAGE_NUMBER = 1;
    const DEFAULT_RECORDS_PER_PAGE = 2;
    const DEFAULT_NO_IMAGE = '/img/no-image.png';
    const DEFAULT_IMAGE_DB_PATH = '/users/'; 
    const DEFAULT_IMAGE_UPLOAD_PATH = 'public/users/';
    const DEFAULT_PROFILE_IMG_PREFIX = 'profile_';
    const DEFAULT_STORE_IMG_PREFIX = 'store_';
    const MOBILE_REG_EXP = '/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}9[0-9](\s){0,1}(\-){0,1}(\s){0,1}[1-9]{1}[0-9]{7}$/';
    
    public static function getFileExtensions() {
        return array('png', 'jpg', 'jpeg', 'gif');
    }

    public static function getMimeExtensions() {
        return array('image/gif', 'image/png', 'image/jpeg');
    }
}
