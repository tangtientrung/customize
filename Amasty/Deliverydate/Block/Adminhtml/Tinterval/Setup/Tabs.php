<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Adminhtml\Tinterval\Setup;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {

        $this->setId('tinterval_setup_tabs');
        $this->setDestElementId('edit_form');

        $this->_coreRegistry = $registry;

        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }
}