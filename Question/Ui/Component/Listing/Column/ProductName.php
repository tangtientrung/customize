<?php

namespace AHT\Question\Ui\Component\Listing\Column;

class ProductName extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
	 * @var $product
     */
    protected $_product; 

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components = []
     * @param array $data = []
     * @param \Magento\Catalog\Model\ProductFactory $product
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Model\ProductFactory $product,
        array $components = [],
        array $data = []
    ){
        $this->_product = $product;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])){
            foreach($dataSource['data']['items'] as &$item){
                $item['product'] = $this->getProduct($item['product_id']);

            }
        }

        return $dataSource;
    }
    public function getProduct($id)
    {
        return $this->_product->create()->load($id)->getName();
    }
}