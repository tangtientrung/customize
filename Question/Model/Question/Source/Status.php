<?php

namespace AHT\Question\Model\Question\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{

    protected $_question;

    public function __construct(\AHT\Question\Model\Question $question)
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