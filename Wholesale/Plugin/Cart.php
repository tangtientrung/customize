<?php
namespace AHT\Wholesale\Plugin;

class Cart
{
    /**
     * @param \Magento\Framework\Controller\Result\ForwardFactory
     */
    private $_forwardFactory;

    public function __construct(
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
    )
    {
        $this->_forwardFactory = $forwardFactory;

    }
    /**
     * @param \Magento\Checkout\Controller\Cart $subject
     * @param \Magento\Framework\App\RequestInterface $request
     * @return //redirect checkout/cart ->checkout/index
     */

    public function beforeDispatch(
        \Magento\Checkout\Controller\Cart $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {

        if($request->getPathInfo()== '/checkout/cart/')
        {
            // rediect checkout/index
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $responseFactory = $objectManager->get('\Magento\Framework\App\ResponseFactory');
            $url = $objectManager->get('Magento\Framework\UrlInterface');
            $responseFactory->create()->setRedirect(
                $url->getUrl('checkout/'))->sendResponse();
            exit();
        }

    }
}
