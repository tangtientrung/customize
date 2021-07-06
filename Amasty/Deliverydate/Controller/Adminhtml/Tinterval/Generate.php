<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Deliverydate
 */

namespace Amasty\Deliverydate\Controller\Adminhtml\Tinterval;

use Magento\Framework\Exception\LocalizedException;

class Generate extends \Amasty\Deliverydate\Controller\Adminhtml\Tinterval
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $stores = '';
                if (!$this->storeManager->isSingleStoreMode()) { // prepare stores
                    $stores = $data['store_ids'];
                    if (is_array($stores)) {
                        $stores = implode(',', $stores);
                    }
                }

                $now = $this->date->date('U');
                // prepare start time
                list($h, $m, $s) = $data['start'];
                $src = $this->date->date('Y', $now)
                    . '-' . $this->date->date('m', $now) .
                    '-' . $this->date->date('d', $now)
                    . ' ' . $h . ':' . $m . ':' . $s;
                $start = strtotime($src);

                // prepare finish time
                list($h, $m, $s) = $data['finish'];
                $src = $this->date->date('Y', $now)
                    . '-' . $this->date->date('m', $now)
                    . '-' . date('d', $now)
                    . ' ' . $h . ':' . $m . ':' . $s;
                $finish = strtotime($src);

                if ($finish < $start) {
                    $finish = $finish + 86400; // 24 h. * 60 min. * 60 sec. = 86400 sec.
                }

                // prepare sorting
                $modifySorting = false;
                $sorting = 0;
                $sortingStep = 0;
                if ($data['sorting_start']) {
                    $modifySorting = true;
                    $sorting = (int)$data['sorting_start'];
                    $sortingStep = (int)$data['sorting_step'];
                }

                $step = (int)$data['step'] * 60; // 1 min = 60 sec.
                $format = $data['format'];
                $defaultQuota = 0;
                $total = 0;
                do {
                    $model = $this->model->create();

                    $data = [];
                    $data['store_ids'] = $stores;
                    $data['time_from'] = $this->date->date($format, $start);

                    $start = $start + $step;
                    $data['time_to'] = date($format, $start);
                    $data['quota'] = $defaultQuota;
                    if ($modifySorting) {
                        $data['sorting_order'] = $sorting;
                        $sorting = $sorting + $sortingStep;
                    } else {
                        $data['sorting_order'] = '';
                    }

                    $model->setData($data);
                    $this->resourceModel->save($model);
                    $total++;
                } while ($start < $finish);

                $this->messageManager->addSuccessMessage(
                    __('Total of %1 record(s) were successfully created', $total)
                );
                $this->_redirect('amasty_deliverydate/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_redirect('amasty_deliverydate/*/index');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while generate the date interval data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->_redirect('amasty_deliverydate/*/index');
                return;
            }
        }
    }
}
