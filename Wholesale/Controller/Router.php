<?php

namespace AHT\Wholesale\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

/**
 * Class Router
 */
class Router implements RouterInterface
{

    /**
     * @param \Magento\Framework\UrlInterface
     */
    private $_url;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $_responseFactory;

    /**
     * Router constructor.
     *
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseFactory $responseFactory
    ) {
        $this->_url = $url;
        $this->_responseFactory = $responseFactory;
    }

    /**
     * @param RequestInterface $request
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');

        $arr = array('search/term/popular','catalogsearch/advanced','sales/guest/form','contact','customer/account/create','customer/account/login');
        if (in_array($identifier,$arr)) {
            $redirectionUrl = $this->_url->getUrl('/');
            $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();

            return $this;
        }

        return null;
    }
}
