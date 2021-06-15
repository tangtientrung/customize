<?php

namespace AHT\ProductOrderGrid\Ui\Component\Listing\Column;

class GrandTotal extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $_currency;
    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components = []
     * @param array $data = []
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Directory\Model\Currency $currency,
        array $components = [],
        array $data = []
    ){
        $this->_currency = $currency;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $symbol = $this->_currency->getCurrencySymbol();
        if(isset($dataSource['data']['items'])){
            foreach($dataSource['data']['items'] as &$item){
                
                    $item['grand_total'] = $symbol.''.number_format($item['grand_total'], 2);
                

            }
        }

        return $dataSource;
    }
}
