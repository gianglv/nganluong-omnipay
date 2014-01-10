<?php

namespace Omnipay\NganLuong\Message;

/**
 * NganLuong Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = '85.0';

    protected $liveEndpoint = 'https://www.nganluong.vn/micro_checkout_api.php?wsdl';
    protected $testEndpoint = 'http://beta.nganluong.vn/micro_checkout_api.php?wsdl';

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
    
    public function getSubject()
    {
        return $this->getParameter('subject');
    }

    public function setSubject($value)
    {
        return $this->setParameter('subject', $value);
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

    public function setHeaderImageUrl($value)
    {
        return $this->setParameter('headerImageUrl', $value);
    }

    public function getNoShipping()
    {
        return $this->getParameter('noShipping');
    }

    public function setNoShipping($value)
    {
        return $this->setParameter('noShipping', $value);
    }

    public function getAllowNote()
    {
        return $this->getParameter('allowNote');
    }

    public function setAllowNote($value)
    {
        return $this->setParameter('allowNote', $value);
    }

    protected function getBaseData($method)
    {
        $data = array();
        $data['operation'] = $method;
        $data['merchant_side_code'] = $this->getMerchantSiteCode();
        $data['merchant_password'] = $this->getMerchantPassword();

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint().'?'.http_build_query($data);
        $httpResponse = $this->httpClient->get($url)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}