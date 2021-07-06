<?php

namespace AHT\ProductQuestion\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{

    protected $_question;

    public function __construct(\AHT\ProductQuestion\Model\ProductQuestion $question)
    {
        $this->_question = $question;
    }

    /**
     * Get status options
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_question->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}