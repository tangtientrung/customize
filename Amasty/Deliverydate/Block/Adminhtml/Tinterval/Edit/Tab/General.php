<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Block\Adminhtml\Tinterval\Edit\Tab;

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
        return __('Properties');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('General');
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

        $model = $this->_coreRegistry->registry('current_amasty_deliverydate_tinterval');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_deliverydate_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        if ($model->getId()) {
            $fieldset->addField('tinterval_id', 'hidden', ['name' => 'tinterval_id']);
        }

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore(true)->getStoreId();
            $fieldset->addField('store_ids', 'hidden', ['name' => 'store_ids[]', 'value' => $storeId]);
            $model->setStoreIds($storeId);
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
            'time_from',
            'text',
            [
                'label' => __('From'),
                'title' => __('From'),
                'name' => 'time_from',
                'required' => true
            ]
        );

        $fieldset->addField(
            'time_to',
            'text',
            [
                'label' => __('To'),
                'title' => __('To'),
                'name' => 'time_to',
                'required' => true
            ]
        );

        $fieldset->addField(
            'sorting_order',
            'text',
            [
                'label' => __('Position'),
                'title' => __('Position'),
                'name' => 'sorting_order'
            ]
        );

        $fieldset->addField(
            'quota',
            'hidden',
            [
                'label' => __('Quota Per Time Interval'),
                'title' => __('Quota Per Time Interval'),
                'name' => 'quota'
            ]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}