<?php

namespace Omnipay\NganLuong;

use Omnipay\Common\AbstractGateway;
use Omnipay\NganLuong\Message\ExpressAuthorizeRequest;
use Omnipay\NganLuong\Message\ExpressCompleteAuthorizeRequest;
use Omnipay\NganLuong\Message\ExpressCompletePurchaseRequest;

/**
 * NganLuong Express Class
 */
class ExpressGateway extends AbstractGateway
{
    public function getName()
    {
        return 'NganLuong Express';
    }

    public function getDefaultParameters()
    {
        $settings = parent::getDefaultParameters();
        $settings['solutionType'] = array('Sole', 'Mark');
        $settings['landingPage'] = array('Billing', 'Login');
        $settings['headerImageUrl'] = '';

        return $settings;
    }

    public function getMerchantSiteCode() {
    	return $this->getParameter('merchantSiteCode');
    }
    
    public function setMerchantSiteCode($value) {
    	return $this->setParameter('merchantSiteCode', $value);
    }
    
    public function getMerchantPassword() {
    	return $this->getParameter('merchantPassword');
    }
    
    public function setMerchantPassword($value) {
    	return $this->setParameter('merchantPassword', $value);
    }
    
    
    public function getSolutionType()
    {
        return $this->getParameter('solutionType');
    }

    public function setSolutionType($value)
    {
        return $this->setParameter('solutionType', $value);
    }

    public function getLandingPage()
    {
        return $this->getParameter('landingPage');
    }

    public function setLandingPage($value)
    {
        return $this->setParameter('landingPage', $value);
    }

    public function getHeaderImageUrl()
    {
        return $this->getParameter('headerImageUrl');
    }

    /**
     * Header Image URL (Optional)
     *
     * URL for the image you want to appear at the top left of the payment page.
     * The image has a maximum size of 750 pixels wide by 90 pixels high.
     * PayPal recommends that you provide an image that is stored on a secure (https) server.
     * If you do not specify an image, the business name displays.
     * Character length and limitations: 127 single-byte alphanumeric characters
     */
    public function setHeaderImageUrl($value)
    {
        return $this->setParameter('headerImageUrl', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NganLuong\Message\ExpressAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NganLuong\Message\ExpressCompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->authorize($parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NganLuong\Message\ExpressCompletePurchaseRequest', $parameters);
    }
}
