<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Api;

interface LocationRepositoryInterface
{
    const LOCATOR_COORDINATES = 'mw_store_locator_coordinates';

    /**
     * Save location.
     *
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     *
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\MageWorx\Locations\Api\Data\LocationInterface $location);

    /**
     * @param string[] $data
     * @param string $code
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     *
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveByCode($data, $code);

    /**
     * @param string $code
     * @param string[] $everyday
     * @param string[] $monday
     * @param string[] $tuesday
     * @param string[] $wednesday
     * @param string[] $thursday
     * @param string[] $friday
     * @param string[] $saturday
     * @param string[] $sunday
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function updateWorkingHoursByCode(
        $code,
        $everyday = [],
        $monday = [],
        $tuesday = [],
        $wednesday = [],
        $thursday = [],
        $friday = [],
        $saturday = [],
        $sunday = []
    );

    /**
     * Retrieve Location.
     *
     * @param int $id
     *
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function getById($id);

    /**
     * Retrieve Location.
     *
     * @param string $code
     *
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function getByCode($code);

    /**
     * Get empty Location
     *
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function getEmptyEntity();

    /**
     * Delete Location
     *
     * @param \MageWorx\Locations\Api\Data\LocationInterface $location
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(\MageWorx\Locations\Api\Data\LocationInterface $location);

    /**
     * Delete Location by given Location Identity
     *
     * @param string $id
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * Delete Location by given Location Code
     *
     * @param string $code
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function deleteByCode($code);

    /**
     * Create Location with the specified code
     *
     * Required location data:
     * - code;          Example: "code": "mw_store"
     * - name;          Example: "name": "MageWorx Store"
     * - store_ids;     Example: All Store Views - "store_ids": [0]; multiple values - "store_ids": "2,5"
     * - country_id;    Example: "country_id": "US"
     * - region;        Example: "region": "New York"
     * - city;          Example: "city": "Minneapolis"
     * - address;       Example: "address": "222 South Ninth St"
     * - postcode;
     *
     * Additional location data:
     * - is_active;          Active is default, "is_active": "1"
     * - order;              Example: "order": "3"
     * - description;        Example: "description": "<div class='description'>Mageworx is an experienced and creative
     *                              Magento extensions developer, eCommerce solutions and services provider.</div>"
     * - postcode;           Example: "postcode": "55402"
     * - email;              Example: "email": "support@mageworx.com"
     * - phone_number;       Example: "phone_number": "+100000000000"
     * - website;            Example: "website": "mageworx.com"
     * - facebook;           Example: "facebook": "https://www.facebook.com/mageworx"
     * - skype;              Example: "skype": "mageworx.com"
     * - instagram;          Example: "instagram": "@mageworx"
     * - whatsapp;           Example: "whatsapp": "mageworx.com"
     *
     * - working_hours_type; Example: "working_hours_type": "everyday"
     * - latitude;           Example: "latitude": "40.4167754"
     * - longitude;          Example: "longitude": "-3.7037901"
     * - assign_type;        All products is default, "assign_type": "all_products"
     *
     * Additional data for 'specific_products' assign type:
     * - product_skus;       Example: "product_skus": "24-MB03,24-MB04,24-MB05";
     *
     * @param string[] $location
     * @return \MageWorx\Locations\Api\Data\LocationInterface
     */
    public function createWithSpecifiedCode($location);

    /**
     * Get list Location
     *
     * @return \MageWorx\Locations\Model\ResourceModel\Location\Collection
     */
    public function getListLocation();

    /**
     * Get list Location by product ids
     *
     * @param int|array $ids
     * @param int|null $limit
     * @param bool $addOutOfStockItems
     * @param array $filters
     * @return string[]
     */
    public function getListLocationByProductIds($ids, $limit = null, $addOutOfStockItems = true, $filters = []);

    /**
     * Get list Location by for cron
     *
     * @return string[]
     */
    public function getListLocationForCron();

    /**
     * Return codes of all existing locations
     *
     * @return string[]
     */
    public function getAllCodes();

    /**
     * @param null|int $storeId
     * @param array $filters
     * @return \MageWorx\Locations\Model\ResourceModel\Location\Collection
     */
    public function getListLocationForFront($storeId = null, $filters = []);

    /**
     * @param int $id
     * @param bool $addOutOfStockItems
     * @return int
     */
    public function getLocationCountForProduct($id, $addOutOfStockItems = true);
}
