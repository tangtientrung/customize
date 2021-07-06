<?php
namespace AHT\ProductQuestion\Model\Config\Source;

class IsSignIn implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {

        return [
            ['value' => 1, 'label' => __('Yes')],
            ['value' => 0, 'label' => __('No')]
        ];
    }
}