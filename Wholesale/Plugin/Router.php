<?php
namespace AHT\Wholesale\Plugin;

use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class Router
{

    /**
     * @var \Magento\Framework\App\Action\Context
     */
    private $_context;
    private  $_response;
    private  $_redirect;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @param \Magento\Framework\Controller\Result\ForwardFactory
     */
    private $_forwardFactory;

    /**
     * @param \Magento\Framework\UrlInterface
     */
    private $_url;

    /**
     * @param \Magento\Framework\App\ActionFactory
     */
    private $_actionFactory;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        UrlFinderInterface $urlFinder,
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ActionFactory $actionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->urlFinder = $urlFinder;
        $this->_forwardFactory = $forwardFactory;
        $this->_context = $context;
        $this->_response = $context->getResponse();
        $this->_redirect = $context->getRedirect();
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_resultFactory = $context->getResultFactory();
        $this->_url = $url;
        $this->_actionFactory = $actionFactory;
    }

    /**
     * Match corresponding URL Rewrite and modify request.
     *
     * @param RequestInterface|HttpRequest $request
     * @return ActionInterface|null
     * @throws NoSuchEntityException
     */
    public function beforeMatch(
        \Magento\UrlRewrite\Controller\Router $subject,
        $request
        )
    {

        $rewrite = $this->getRewrite(
            $request->getPathInfo(),
            $this->storeManager->getStore()->getId()
        );
        if ($rewrite != null){
            if ($rewrite->getEntityType() == 'category' || $rewrite->getEntityType() == 'product') {
//                die('1');
                return $this->_actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            else {
//                die('2');
//                 $resultForward = $this->_forwardFactory->create();
//                 $resultForward->forward('defaultNoRoute');
//                 return $resultForward;
                $norouteUrl = $this->_url->getUrl('noroute');
                return $this->_response->setRedirect($norouteUrl);

            }
        }
        else
        {
            $arr = array("/checkout/","/checkout/onepage/success/","/","/noroute/");
            if(in_array($request->getPathInfo(),$arr))
            {
//                die('3');
                return $this->_actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            else {
//                die('4');
                $norouteUrl = $this->_url->getUrl('noroute');
                return $this->_response->setRedirect($norouteUrl);
            }

        }
    }

    /**
     * Find rewrite based on request data
     *
     * @param string $requestPath
     * @param int $storeId
     * @return UrlRewrite|null
     */
    protected function getRewrite($requestPath, $storeId)
    {
        return $this->urlFinder->findOneByData(
            [
                UrlRewrite::REQUEST_PATH => ltrim($requestPath, '/'),
                UrlRewrite::STORE_ID => $storeId,
            ]
        );
    }
    public function getResponse()
    {
        return $this->_response;
    }
}
