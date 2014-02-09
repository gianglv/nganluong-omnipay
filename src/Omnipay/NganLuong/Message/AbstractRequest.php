<?php

namespace Omnipay\NganLuong\Message;

use SoapClient;
use DOMDocument;
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
    
    public function getReceiver() {
    	return $this->getParameter('receiver');
    }
    
    public function setReceiver($value) {
    	return $this->setParameter('receiver', $value);
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
        $soap_client = new SoapClient($this->getEndpoint(), array('encoding' => 'ISO-8859-1'));
        $soap_response = $soap_client->__soapCall($this->getOperation(), $this->buildSoapParams($data));
        return $this->createResponse($soap_response);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
    
    protected function buildSoapParams($data)
    {
    	$params = array(
    			'merchant_site_code'	=> $this->merchantSiteCode,
    			'checksum'				=> $this->_makeChecksum($data),
    			'params'				=> '<params>'.$this->_convertArrayToXML($data).'</params>'
    	);
    	return $params;
    }
    private function _makeChecksum($params)
    {
    	$md5 = '';
    	$keys = $this->_getMapKeys();
    	foreach ($keys as $key) {
    		$md5.= strval(@$params[$key]);
    	}
    	$md5.= $this->merchantPassword;
    	return md5($md5);
    }
    
    private function _convertXMLToArray($xml, $tag = 'params')
    {
    	$doc = new DOMDocument();
    	$doc->loadXML($xml);
    	$params = $doc->getElementsByTagName($tag);
    	return $this->_getNodes($params->item(0));
    }
    
    private function _getNodes($parent_node)
    {
    	$result = array();
    	$nodes = $parent_node->childNodes;
    	for ($i = 0; $i < $nodes->length; $i++) {
    		$node = $nodes->item($i);
    		if ($node->nodeType == 1) {
    			if ($parent_node->getElementsByTagName($node->nodeName)->length == 1) {
    				if ($this->_hasChildNodes($node)) {
    					$result[$node->nodeName] = $this->_getNodes($node);
    				} else {
    					$result[$node->nodeName] = trim(html_entity_decode($node->nodeValue));
    				}
    			} else {
    				if ($this->_hasChildNodes($node)) {
    					$result[] = $this->_getNodes($node);
    				} else {
    					$result[] = trim(html_entity_decode($node->nodeValue));
    				}
    			}
    		}
    	}
    	return $result;
    }
    
    private function _hasChildNodes($parent_node)
    {
    	if ($parent_node->hasChildNodes()) {
    		$nodes = $parent_node->childNodes;
    		for ($i = 0; $i < $nodes->length; $i++) {
    			if ($nodes->item($i)->nodeType == 1) {
    				return true;
    			}
    		}
    	}
    	return false;
    }
    
    private function _convertArrayToXML($array)
    {
    	$result = "";
    	if (!empty($array)) {
    		foreach ($array as $key=>$value) {
    			$result.= is_numeric($key) ? "<item>" : "<$key>";
    			if (is_array($value)) {
    				$result.= $this->_convertArrayToXML($value);
    			} else {
    				$result.= htmlspecialchars($value);
    			}
    			$result.= is_numeric($key) ? "</item>" : "</$key>";
    		}
    	}
    	return $result;
    }
}