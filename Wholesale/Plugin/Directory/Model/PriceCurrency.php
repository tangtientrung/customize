<?php

namespace AHT\Wholesale\Plugin\Directory\Model;

/**
 * Class PriceCurrency
 * @package AHT\Wholesale\Plugin\Directory\Model
 */
class PriceCurrency
{
    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $_localeFormat;

    /**
     * PriceCurrency constructor.
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     */
    public function __construct(
        \Magento\Framework\Locale\FormatInterface $localeFormat
    ) {
        $this->_localeFormat = $localeFormat;
    }

    public function aroundFormatTxt(
        \Magento\Directory\Model\Currency $subject,
        \Closure $proceed,
        $price,
        $options = []
    ) {
        return '';
    }
}
