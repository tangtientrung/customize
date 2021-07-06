<?php

namespace AHT\Question\Ui\Component\Listing\Column;

class FullName extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components = []
     * @param array $data = []
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])){
            foreach($dataSource['data']['items'] as &$item){
                if($item['user_id']==0)
                {
                    $item['fullname']="amin";
                }
                else
                {
                    $item['fullname'] = $item['firstname'] . ' ' . $item['lastname'];
                }

            }
        }

        return $dataSource;
    }
}