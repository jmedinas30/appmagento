<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Ui\DataProvider\Location\Form;

use MageWorx\Locations\Api\LocationRepositoryInterface;
use MageWorx\Locations\Api\Data\LocationInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;

/**
 * Class LocationDataProvider
 */
class LocationDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * LocationDataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param LocationRepositoryInterface $locationRepository
     * @param PoolInterface $pool
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        LocationRepositoryInterface $locationRepository,
        PoolInterface $pool,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->pool         = $pool;
        $this->storeManager = $storeManager;
        $this->collection   = $locationRepository->getListLocation();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMeta()
    {
        $meta      = parent::getMeta();
        $modifiers = $this->pool->getModifiersInstances();
        /** @var ModifierInterface $modifier */
        foreach ($modifiers as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        if (!count($items)) {
            return null;
        }

        /** @var LocationInterface $location */
        foreach ($items as $location) {
            $this->loadedData[$location->getId()] = $location->getData();
            foreach ($this->loadedData[$location->getId()]['store_ids'] as $key => $storeId) {
                if ($storeId != Store::DEFAULT_STORE_ID) {
                    $this->loadedData[$location->getId()]['store_ids'][$key] = (string)$storeId;
                } else {
                    $this->loadedData[$location->getId()]['store_ids'][$key] = (int)$storeId;
                }
            }

            $this->prepareDataForTimePicker($location->getId());

            if ($this->loadedData[$location->getId()]['image_path']) {
                /* prepare image for preview */
                $imageData[0]['size'] = 0;
                $imageData[0]['name'] = $this->loadedData[$location->getId()]['image_path'];
                $imageData[0]['type'] = 'image';
                $imageData[0]['url']  = $this->storeManager->getStore()->getBaseUrl('media')
                    . 'mageworx/locations/' . $this->loadedData[$location->getId()]['image_path'];

                $this->loadedData[$location->getId()]['image_path'] = $imageData;
            }

            $this->loadedData[$location->getId()]['disabled'] = true;
        }
        $modifiers = $this->pool->getModifiersInstances();
        /** @var ModifierInterface $modifier */
        foreach ($modifiers as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }

        return $this->loadedData;
    }

    /**
     * @param int $id
     * @return $this
     */
    protected function prepareDataForTimePicker($id)
    {
        $time         = [
            'hour'     => '1',
            'minute'   => '00',
            'day_part' => 'am'
        ];
        $schedule     = [
            'from'           => $time,
            'to'             => $time,
            'off'            => false,
            'lunch_from'     => $time,
            'lunch_to'       => $time,
            'has_lunch_time' => false
        ];
        $workingHours = [
            'everyday'  => $schedule,
            'sunday'    => $schedule,
            'monday'    => $schedule,
            'tuesday'   => $schedule,
            'wednesday' => $schedule,
            'thursday'  => $schedule,
            'friday'    => $schedule,
            'saturday'  => $schedule,
        ];

        foreach ($this->loadedData[$id]['working_hours'] as $key => $data) {
            $workingHours[$key]['off']            = $data['off'];
            $workingHours[$key]['has_lunch_time'] = $data['has_lunch_time'];
            foreach ($data as $dataKey => $value) {
                if (in_array($dataKey, ['from', 'to', 'lunch_from', 'lunch_to']) && $value) {

                    $time = explode(':', $value);

                    if (isset($time[0])) {
                        $workingHours[$key][$dataKey]['hour'] = $time[0];
                    }

                    if (isset($time[1])) {
                        $minute = explode(' ', $time[1]);

                        if (isset($minute[0])) {
                            $workingHours[$key][$dataKey]['minute']
                                = $minute[0];
                        }

                        if (isset($minute[1])) {
                            $workingHours[$key][$dataKey]['day_part']
                                = $minute[1];
                        }
                    }
                }
            }
        }

        $this->loadedData[$id]['working_hours'] = $workingHours;

        return $this;
    }
}
