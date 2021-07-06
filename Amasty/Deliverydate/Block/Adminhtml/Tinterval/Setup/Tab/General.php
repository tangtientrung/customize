<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Adminhtml\Tinterval\Setup\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class General extends Generic implements TabInterface {

    protected $_systemStore;
    /**
     * @var \Amasty\Deliverydate\Helper\Data
     */
    protected $amhelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Amasty\Deliverydate\Helper\Data $amhelper,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
        $this->amhelper = $amhelper;
    }


    public function getTabLabel()
    {
        return __('Configuration');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Configuration');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_deliverydate_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Configuration')]);

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore(true)->getStoreId();
            $fieldset->addField('store_ids', 'hidden', ['name' => 'store_ids[]', 'value' => $storeId]);
        } else {
            $field = $fieldset->addField(
                'store_ids',
                'multiselect',
                [
                    'name'     => 'store_ids[]',
                    'label'    => __('Stores'),
                    'title'    => __('Stores'),
                    'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
                    'required' => true,
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }

        $fieldset->addField(
            'start',
            'time',
            [
                'label' => __('Starting Time'),
                'title' => __('Starting Time'),
                'name' => 'start'
            ]
        );

        $fieldset->addField(
            'finish',
            'time',
            [
                'label' => __('Ending Time'),
                'title' => __('Ending Time'),
                'name' => 'finish'
            ]
        );

        $fieldset->addField(
            'step',
            'text',
            [
                'label' => __('Minutes Interval'),
                'title' => __('Minutes Interval'),
                'required' => true,
                'name' => 'step'
            ]
        );

        $fieldset->addField(
            'format',
            'select',
            [
                'label'  => __('Format'),
                'title'  => __('Format'),
                'name'   => 'format',
                'values' => $this->getFormatTime(),
            ]
        );

        $fieldset->addField(
            'sorting_start',
            'text',
            [
                'label'    => __('Starting Value for Position'),
                'title'    => __('Starting Value for Position'),
                'name'     => 'sorting_start',
            ]
        );

        $fieldset->addField(
            'sorting_step',
            'text',
            [
                'label'    => __('Step for Position'),
                'title'    => __('Step for Position'),
                'name'     => 'sorting_step',
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getFormatTime() {
        $formats = array(
            array(
                'value' => 'H:i',
                'label' => __('05:00 - 06:00 (24 Hour Format)'),
            ),
            array(
                'value' => 'G:i',
                'label' => __('5:00 - 6:00 (24 Hour Format)'),
            ),
            array(
                'value' => 'h:i a',
                'label' => __('05:00 am - 06:00 am (12 Hour Format)'),
            ),
            array(
                'value' => 'h:i A',
                'label' => __('05:00 AM - 06:00 AM (12 Hour Format)'),
            ),
            array(
                'value' => 'g:i a',
                'label' => __('5:00 am - 6:00 am (12 Hour Format)'),
            ),
            array(
                'value' => 'g:i A',
                'label' => __('5:00 AM - 6:00 AM (12 Hour Format)'),
            ),
        );

        return $formats;
    }
}