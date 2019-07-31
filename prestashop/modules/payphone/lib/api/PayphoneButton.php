<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once _PS_MODULE_DIR_ . 'payphone/lib/common/PayPhoneWebException.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/request/ConfirmSaleRequestModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/request/PrepareSaleRequestModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/response/PrepareSaleModel.php';
require_once _PS_MODULE_DIR_ . 'payphone/lib/models/response/SaleGetResponseModel.php';


class PayphoneButton {

    /**
     * Establece la transacción
     * @param \PrepareSaleRequestModel 
     * @return \PrepareSaleModel
     * @throws \PayPhoneWebException
     */
    public function Prepare($model) {
        try {
            $uri = 'api/button/Prepare';
            $json = json_encode($model);
            $response = $this->post_call($uri, $json);
            return $response;
        } catch (PayPhoneWebException $exc) {
            throw $exc;
        } catch (Exception $exc) {
            throw $this->ThrowException();
        }
    }

    /**
     * 
     * @param int $id
     * @return \SaleGetResponseModel
     * @throws \PayPhoneWebException
     */
    public function Confirm($id) {
        try {
            $uri = 'api/button/Confirm';
            $model = new ConfirmSaleRequestModel();
            $model->id = $id;
            $json = json_encode($model);
            $response = $this->post_call($uri, $json);
            return $response;
        } catch (PayPhoneWebException $exc) {
            throw $exc;
        } catch (Exception $exc) {
            throw $this->ThrowException();
        }
    }

    /**
     * @author Henry Campoverde
     * @param int $id
     * @return \Boolean
     * @throws \PayPhoneWebException
     */
    public function Reverse($id) {
        try {
            $uri = 'api/Reverse';
            $model = new ConfirmSaleRequestModel();
            $model->id = $id;
            $json = json_encode($model);
            $response = $this->post_call($uri, $json);
            return $response;
        } catch (PayPhoneWebException $exc) {
            throw $exc;
        } catch (Exception $exc) {
            throw $this->ThrowException();
        }
    }

    /**
     * 
     * @param string $clientId
     * @return \SaleGetResponseModel
     * @throws \PayPhoneWebException
     */
    public function GetSaleByClientId($clientId) {
        try {
            $uri = '/api/Sale/client/' . $clientId;
            $response = $this->get_call($uri);
            return $response;
        } catch (PayPhoneWebException $exc) {
            throw $exc;
        } catch (Exception $exc) {
            throw $this->ThrowException($exc);
        }
    }

    private function post_call($uri, $post_data, $content_type = "application/json") {

        $config = ConfigurationManager::Instance();
        $curl = curl_init($config->ApiPath . $uri);
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $config->Token;
        $headers[] = 'Content-Type: ' . $content_type;
        /*if (!empty($config->Lang))
            $headers[] = 'Accept-Language: ' . $config->Lang;*/
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        $curl_response = curl_exec($curl);

        $info = curl_getinfo($curl);
        curl_close($curl);

        $errors = array();
        switch ($info['http_code']) {
            case 200:
                return json_decode($curl_response);
            case 0:
                $temp = new ErrorResponseModel();
                $temp->message = 'Lo sentimos por favor verifique su internet o cadena de conexi&oacute;n.';
                $errors[] = $temp;
                throw new PayPhoneWebException(null, $info['http_code'], $errors);
            default :
                $errors = json_decode($curl_response);
                if (!is_array($errors)) {
                    $temp = $errors;
                    $errors = array();
                    $errors[] = $temp;
                }
                throw new PayPhoneWebException(null, $info['http_code'], $errors);
        }
    }

    private function get_call($uri) {
        $config = ConfigurationManager::Instance();
        $curl = curl_init($config->ApiPath . $uri);
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $config->Token;
        $headers[] = 'Content-Type: application/json';
        /*if (!empty($config->Lang))
            $headers[] = 'Accept-Language: ' . $config->Lang;*/
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        $info = curl_getinfo($curl);
        curl_close($curl);

        $errors = array();
        switch ($info['http_code']) {
            case 200:
                return json_decode($curl_response);
            case 0:
                $temp = new ErrorResponseModel();
                $temp->message = 'Lo sentimos por favor verifique su internet o cadena de conexi&oacute;n.';
                $errors[] = $temp;
                throw new PayPhoneWebException(null, $info['http_code'], $errors);
            default :
                $errors = json_decode($curl_response);
                if (!is_array($errors)) {
                    $temp = $errors;
                    $errors = array();
                    $errors[] = $temp;
                }
                throw new PayPhoneWebException(null, $info['http_code'], $errors);
        }
    }

    /**
     * Transaforma una excepción genérica a la excepción que entrega PayPhone
     * @param Exception $e
     * @return \PayPhoneWebException
     */
    private function ThrowException(Exception $e) {
        $errors = array();

        $error = new ErrorResponseModel();
        $error->message = $e->Message;
        $errors[] = $error;
        return new PayPhoneWebException(null, "500", $errors);
    }

}
