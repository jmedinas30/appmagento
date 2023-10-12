<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Model\Datatransfer;

use Magento\Framework\Exception\InputException;
use Magento\Store\Model\Store;
use MageWorx\Locations\Helper\Data as Helper;
use MageWorx\Locations\Model\ResourceModel\Location as ResourceLocation;
use MageWorx\Locations\Api\Data\LocationInterface;

class CsvImportHandler
{
    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var \MageWorx\Locations\Api\LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var ResourceLocation
     */
    protected $resource;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var int
     */
    protected $codeColumnId;

    /**
     * @var int
     */
    protected $storeCodeColumnId;

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var int
     */
    protected $success = 0;

    /**
     * @var int
     */
    protected $error = 0;

    /**
     * @var int
     */
    protected $created = 0;

    /**
     * @var array
     */
    protected $requiredColumns = [
        'store_code',
        'store_codes',
        'code',
        'name',
        'country_id',
        'region',
        'city',
        'address'
    ];

    /**
     * CsvImportHandler constructor.
     *
     * @param ResourceLocation $resource
     * @param Helper $helper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \MageWorx\Locations\Api\LocationRepositoryInterface $locationRepository
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        ResourceLocation $resource,
        Helper $helper,
        \Psr\Log\LoggerInterface $logger,
        \MageWorx\Locations\Api\LocationRepositoryInterface $locationRepository,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
        $this->resource           = $resource;
        $this->helper             = $helper;
        $this->logger             = $logger;
        $this->locationRepository = $locationRepository;
        $this->csvProcessor       = $csvProcessor;
        $this->dataObjectFactory  = $dataObjectFactory;
    }

    /**
     * @param string[] $file
     * @return string[]
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importFromCsvFile($file)
    {
        if (!isset($file['tmp_name'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }
        $data = $this->csvProcessor->getData($file['tmp_name']);

        if (count($data) < 2) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Data for import not found.')
            );
        }
        $this->keys = $data[0];

        $missedColumns = array_diff($this->requiredColumns, $this->keys);

        if (!empty($missedColumns)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(implode(', ', $missedColumns) . ' is required column(s).')
            );
        }

        $flipData                = array_flip($this->keys);
        $this->codeColumnId      = $flipData['code'];
        $this->storeCodeColumnId = $flipData['store_code'];
        array_shift($data);
        array_walk_recursive($data, [$this, 'trim']);

        $dataByStores = $this->getDataByStores($data);

        //default store should be first to create new entities
        $this->helper->setCurrentStoreIdForLocation(Store::DEFAULT_STORE_ID);
        $this->import($dataByStores[Store::DEFAULT_STORE_ID]);
        unset($dataByStores[Store::DEFAULT_STORE_ID]);

        foreach ($dataByStores as $storeId => $data) {
            $this->helper->setCurrentStoreIdForLocation($storeId);
            $this->import($data);
        }

        return ['success' => $this->success, 'created' => $this->created, 'error' => $this->error];
    }

    /**
     * @param string $item
     * @param string $key
     */
    protected function trim(
        &$item,
        $key
    ) {
        $item = trim($item);
    }

    /**
     * @param string[] $data
     */
    protected function import($data)
    {
        foreach ($data as $itemData) {
            try {
                if (!isset($itemData[0])) {
                    continue;
                }

                $id = $this->resource->getIdByLocationCode($itemData[$this->codeColumnId]);

                if (!$id && $this->helper->getCurrentStoreIdForLocation() === Store::DEFAULT_STORE_ID) {
                    //create new location
                    $location = $this->locationRepository->getEmptyEntity();
                } elseif ($id) {
                    //update location
                    $location = $this->locationRepository->getByCode($itemData[$this->codeColumnId]);
                    $location->setCurrentStoreId($this->helper->getCurrentStoreIdForLocation());
                } else {
                    $exception = new InputException();
                    $exception->addError(
                        __('There is no location with "code"=%1 on default store view', $itemData[$this->codeColumnId])
                    );
                    throw $exception;
                }
                $this->setCsvDataToLocation($location, $itemData);
                $location->setArraySeparator(LocationInterface::IMPORT_ARRAY_SEPARATOR);
                $this->locationRepository->save($location);
                $this->success++;
            } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
                $this->generateError(
                    __('Row data is incorrect - products from column product_skus don\'t exist in your store.')
                );
            } catch (InputException $e) {
                $this->generateError($e->getMessage(), $itemData['row']);
                foreach ($e->getErrors() as $error) {
                    $this->generateError($error->getMessage());
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->generateError($e->getMessage(), $itemData['row']);
            }
        }
    }

    /**
     * @param string[] $data
     * @return string[]
     */
    protected function getDataByStores($data)
    {
        $result = [];
        foreach ($this->helper->getAllStoreIds() as $storeId) {
            $result[$storeId] = [];
        }

        foreach ($data as $rowId => $item) {
            $rowId = $rowId + 2;

            if (count($item) < count($this->requiredColumns)) {
                if ($item === [""]) {
                    continue;
                }

                $this->generateError(__('Not enough data for import in this row.'), $rowId);
                continue;
            }

            $storeId = $this->helper->getStoreIdByCode($item[$this->storeCodeColumnId]);

            if ($storeId === false) {
                $this->generateError(__('Store with code %1 don\'t exist', $item[$this->storeCodeColumnId]), $rowId);
                continue;
            }
            $item['row']        = $rowId;
            $result[$storeId][] = $item;
        }

        return $result;
    }

    /**
     * @param LocationInterface $location
     * @param string[] $itemData
     */
    protected function setCsvDataToLocation($location, $itemData)
    {
        foreach ($itemData as $keyId => $value) {
            if ($keyId === 'row') {
                continue;
            }

            if ($this->keys[$keyId] == 'entity_id') {
                continue;
            }

            if ($value === '') {
                continue;
            }

            $location->setData($this->keys[$keyId], $value);
        }
    }

    /**
     * @param string $text
     * @param string|int $rowId
     */
    protected function generateError($text, $rowId = '')
    {
        $this->error++;
        $rowStr = $rowId ? '(row=' . $rowId . ')' : '';
        $this->logger->error('MageWorx_Locations Import Error' . $rowStr . ': ' . $text);
    }
}
