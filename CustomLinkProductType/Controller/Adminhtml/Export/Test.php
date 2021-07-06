<?php
namespace AHT\CustomLinkProductType\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;

class Test extends Action
{

    public function execute()
    {
        //csv
        $this->_view->loadLayout(false);
        $fileName = 'catalog_products.csv';
        $exportBlock = $this->_view->getLayout()->createBlock('Magento\Catalog\Block\Adminhtml\Product\Grid');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_fileFactory = $objectManager->create('Magento\Framework\App\Response\Http\FileFactory');
        return $this->_fileFactory->create(
            $fileName,
            $exportBlock->getCsvFile(),
            DirectoryList::VAR_DIR
        );

    }
}
