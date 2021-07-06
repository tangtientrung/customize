<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Dinterval;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Amasty\Deliverydate\Controller\Adminhtml\Dinterval
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                /** @var \Amasty\Deliverydate\Model\Dinterval $model */
                $model = $this->_objectManager->create('Amasty\Deliverydate\Model\Dinterval');

                $id = $this->getRequest()->getParam('dinterval_id');

                if ($id) {
                    $this->resourceModel->load($model, $id);
                    if ($id != $model->getId()) {
                        throw new LocalizedException(__('The wrong date interval is specified.'));
                    }
                }

                $model->setData($data);
                $this->prepareForSave($model, $data);

                $this->resourceModel->save($model);

                $this->messageManager->addSuccessMessage(__('Record has been successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amasty_deliverydate/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('amasty_deliverydate/*/');
                return;

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->session->setPageData($data);
                $id = (int)$this->getRequest()->getParam('dinterval_id');
                if (!empty($id)) {
                    $this->_redirect('amasty_deliverydate/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('amasty_deliverydate/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the date interval data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('amasty_deliverydate/*/edit', ['id' => $this->getRequest()->getParam('dinterval_id')]);
                return;
            }
        }
    }

    /**
     * @param \Amasty\Deliverydate\Model\Dinterval $model
     * @param array                                $data
     *
     * @return bool
     * @throws LocalizedException
     */
    protected function prepareForSave($model, $data)
    {
        if ($data['each_year']) {
            // 0 - each Year
            $model->setForEachYear();
        }
        if ($data['each_month']) {
            // 0 - each month
            $model->setForEachMonth();
        }

        if (!$model->isForEachYear() && !$model->isForEachMonth()) {
            $fromDate = $this->date
                ->timestamp($model->getFromMonth() . '/' . $model->getFromDay() . '/' . $model->getFromYear());
            $toDate   = $this->date
                ->timestamp($model->getToMonth() . '/' . $model->getToDay() . '/' . $model->getToYear());

            if ($fromDate > $toDate) {
                throw new LocalizedException(__('The end date you entered is before the start date'));
            }
        } elseif (!$model->isForEachYear() && $model->isForEachMonth() && $model->getFromYear() > $model->getToYear()) {
            // allow from day be greater than to day.
            throw new LocalizedException(__('The end date you entered is before the start date'));
        }
        $stores = $model->getData('store_ids');
        if (is_array($stores)) {
            // need commas to simplify sql query
            $model->setData('store_ids', implode(',', $stores));
        } else { // need for null value
            $model->setData('store_ids', '');
        }

        return true;
    }
}
