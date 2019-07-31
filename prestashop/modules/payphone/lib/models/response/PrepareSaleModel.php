<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrepareSaleModel
 *
 * @author ovazquez
 */
class PrepareSaleModel {

    /**
     * Payment id generate
     * @var string
     */
    public $paymentId;

    /**
     * Url for redirect and pay with PayPhone
     * @var string
     */
    public $payWithPayPhone;

    /**
     * Url for redirect to PayPhone and pay only with your credit card
     * @var string
     */
    public $payWithCard;

}
