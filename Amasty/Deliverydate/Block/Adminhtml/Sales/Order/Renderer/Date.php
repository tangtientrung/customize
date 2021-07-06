<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */


namespace Amasty\Deliverydate\Block\Adminhtml\Sales\Order\Renderer;

class Date extends \Magento\Framework\Data\Form\Element\Date
{

    public function getElementHtml()
    {
        $this->addClass('admin__control-text  input-text');
        $dateFormat = $this->getDateFormat() ? : $this->getFormat();
        $timeFormat = $this->getTimeFormat();
        if (empty($dateFormat)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                'Output format is not specified. ' .
                'Please specify "format" key in constructor, or set it using setFormat().'
            );
        }

        $dataInit = 'data-mage-init="' . $this->_escape(json_encode([
                        'calendar' => [
                            'dateFormat' => $dateFormat,
                            'showsTime' => !empty($timeFormat),
                            'timeFormat' => $timeFormat,
                            'buttonImage' => $this->getImage(),
                            'buttonText' => 'Select Date',
                            'disabled' => $this->getDisabled(),
                            'minDate' => $this->getMinDate()
                        ],
                    ])). '"';

        $html = sprintf(
            '<input name="%s" id="%s" value="%s" %s %s />',
            $this->getName(),
            $this->getHtmlId(),
            $this->_escape($this->getValue()),
            $this->serialize($this->getHtmlAttributes()),
            $dataInit
        );
        $html .= $this->getAfterElementHtml();

        return $html;
    }
}
