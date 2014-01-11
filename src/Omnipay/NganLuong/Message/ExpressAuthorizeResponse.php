<?php

namespace Omnipay\NganLuong\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * NganLuong Express Authorize Response
 */
class ExpressAuthorizeResponse extends Response implements RedirectResponseInterface
{
    protected $liveCheckoutEndpoint = '';
    protected $testCheckoutEndpoint = '';

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return isset($this->data['result_code']) && in_array($this->data['result_code'], array('00'));
    }

    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint();
    }

    public function getTransactionReference()
    {
        return isset($this->data['token']) ? $this->data['token'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    protected function getCheckoutEndpoint()
    {
        return $this->data['link_checkout'];
    }
}
