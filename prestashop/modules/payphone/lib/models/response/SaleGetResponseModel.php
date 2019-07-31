<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleGetResponseModel
 *
 * @author ovazquez
 */
class SaleGetModel {

    public $email;
    public $cardType;
    public $clientUserId;
    public $processor;
    public $bin;
    public $deferredCode;
    public $deferredMessage;
    public $deferred;
    public $cardBrandCode;
    public $cardBrand;
    public $amount;
    public $clientTransactionId;
    public $phoneNumber;
    public $statusCode;
    public $transactionStatus;
    public $authorizationCode;
    public $message;
    public $messageCode;
    public $transactionId;
    public $document;

    /**
     * Taxes apply to current transaction
     * @var \TaxModel 
     */
    public $taxes;
    public $optionalParameter1;
    public $optionalParameter2;
    public $optionalParameter3;

}

class ErrorResponseModel {

    public $redirect;
    public $redirectUri;
    public $clientTransactionId;
    public $message;
    public $errorCode;

    /**
     * If parameter validation fail this field have values
     * @var string[]
     */
    public $errors;

}

class TaxModel {

    public $name;
    public $amount;
    public $value;
    public $tax;

}

class ErrorModel {

    public $message;
    public $errorCode;
    public $errorDescription;
    public $errorDescriptions;

}

class SaleGetResponseModel {

    /**
     * Result for the get sale
     * @var \SaleGetModel
     */
    public $result;

    /**
     * If response was sussccesfully or not
     * @var bool
     */
    public $success;
    //['100', '101', '200', '201', '202', '203', '204', '205', '206', '300', '300', '301', '301', '302', '302', '303', '303', '304', '305', '306', '307', '307', '400', '401', '402', '403', '404', '405', '406', '407', '408', '409', '410', '411', '412', '413', '414', '415', '416', '417', '426', '500', '501', '502', '503', '504', '505']
    /**
     * Http response model for this request
     * @var int
     */
    public $statusCode;

    /**
     * Describe the errors occurred
     * @var \ErrorResponseModel
     */
    public $error;

}
