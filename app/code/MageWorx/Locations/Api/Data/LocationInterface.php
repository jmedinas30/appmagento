<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Api\Data;

use Magento\Framework\Exception\InputException;

/**
 * Location interface
 *
 * @api
 */
interface LocationInterface
{
    /**
     * Constants for DB tables
     */
    const ENTITY                        = 'mageworx_location';
    const ENTITY_TABLE                  = 'mageworx_location_entity';
    const ENTITY_STORE_RELATION_TABLE   = 'mageworx_location_stores_relation';
    const ENTITY_PRODUCT_RELATION_TABLE = 'mageworx_location_products_relation';
    const ENTITY_WORKING_HOURS_TABLE    = 'mageworx_location_working_hours';

    /**
     * Constants for keys of data array.
     */
    const ENTITY_ID                = 'entity_id';
    const CODE                     = 'code';
    const NAME                     = 'name';
    const DESCRIPTION              = 'description';
    const IS_ACTIVE                = 'is_active';
    const IS_PICKUP_AVAILABLE      = 'is_pickup_available';
    const ORDER                    = 'order';
    const IMAGE_PATH               = 'image_path';
    const WORKING_HOURS_TYPE       = 'working_hours_type';
    const WORKING_HOURS            = 'working_hours';
    const IS_WORKING_HOURS_ENABLED = 'is_working_hours_enabled';
    const TIMEZONE                 = 'timezone';
    const COUNTRY_ID               = 'country_id';
    const REGION                   = 'region';
    const CITY                     = 'city';
    const ADDRESS                  = 'address';
    const POSTCODE                 = 'postcode';
    const EMAIL                    = 'email';
    const PHONE_NUMBER             = 'phone_number';
    const WEBSITE_URL              = 'website';
    const FACEBOOK                 = 'facebook';
    const SKYPE                    = 'skype';
    const INSTAGRAM                = 'instagram';
    const WHATSAPP                 = 'whatsapp';
    const LATITUDE                 = 'latitude';
    const LONGITUDE                = 'longitude';
    const STORE_IDS                = 'store_ids';
    const PRODUCT_SKUS             = 'product_skus';
    const ASSIGN_TYPE              = 'assign_type';
    const APPLY_BY_CRON            = 'apply_by_cron';
    const LOCATION_PAGE_PATH       = 'location_page_path';
    const SOURCE_CODE              = 'source_code';
    const META_TITLE               = 'meta_title';
    const META_DESCRIPTION         = 'meta_description';
    const META_KEYWORDS            = 'meta_keywords';
    const META_ROBOTS              = 'meta_robots';
    const GAS_STATION              = 'gas_station';
    const ATM                      = 'atm';
    const PHARMACY                 = 'pharmacy';
    const TIRE_CENTER              = 'tire_center';
    const FOOD_COURT               = 'food_court';
    const CURBSIDE_PICKUP          = 'curbside_pickup';
    const PARKING                  = 'parking';
    const AMAZON_RETURNS           = 'amazon_returns';
    const DELIVERY_PICKUP          = 'delivery_pickup';
    const OPTICAL_DEPARTMENT       = 'optical_department';

    const INACTIVE = 0;
    const ACTIVE   = 1;

    const WORKING_EVERYDAY        = 'everyday';
    const WORKING_PER_DAY_OF_WEEK = 'per_day_of_week';
    const WORKING_24_HOURS_A_DAY  = '24_hours_a_day';

    const ASSIGN_TYPE_ALL                  = 'all_products';
    const ASSIGN_TYPE_CONDITION            = 'condition';
    const ASSIGN_TYPE_SPECIFIC_PRODUCTS    = 'specific_products';
    const ASSIGN_TYPE_PRODUCTS_FROM_SOURCE = 'products_from_source';

    const CURRENT_LOCATION = 'mageworx_locations_current_location';

    const CURRENT_STORE_ID_FOR_LOCATION = 'mageworx_locations_current_store_id_for_location';

    const IMPORT_ARRAY_SEPARATOR = '||';

    const API_ARRAY_SEPARATOR = ',';

    const REQUIRED_FIELDS = [
        'code',
        'name',
        'store_ids',
        'country_id',
        'region',
        'city',
        'address',
        'postcode'
    ];

    const DATA_FOR_CUSTOMER = [
        'mw_store_' . self::NAME,
        'mw_store_' . self::DESCRIPTION,
        'mw_store_' . self::PHONE_NUMBER,
        'mw_store_' . self::SKYPE,
        'mw_store_' . self::EMAIL,
        'mw_store_' . self::WEBSITE_URL,
        'mw_store_' . self::WHATSAPP,
        'mw_store_' . self::WORKING_HOURS,
        'mw_store_' . self::FACEBOOK,
        'mw_store_' . 'prepared_address',
        'mw_store_' . 'page_url'
    ];

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get assign type
     *
     * @return string
     */
    public function getAssignType();

    /**
     * Get apply by crone
     *
     * @return bool
     */
    public function getApplyByCron();

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * Get status
     *
     * @return bool
     */
    public function getIsActive();

    /**
     * @return bool
     */
    public function getIsPickupAvailable(): bool;

    /**
     * Get order
     *
     * @return int
     */
    public function getOrder();

    /**
     * Get image path
     *
     * @return string
     */
    public function getImagePath();

    /**
     * @return string
     */
    public function getCountry();

    /**
     * @return string
     */
    public function getTimezone(): string;

    /**
     * @return string
     */
    public function getCountryId();

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @return string
     */
    public function getPostcode();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getPhoneNumber();

    /**
     * @return string
     */
    public function getWebsiteUrl();

    /**
     * @return string
     */
    public function getSkype();

    /**
     * @return string
     */
    public function getWhatsapp();

    /**
     * @return string
     */
    public function getInstagram();

    /**
     * @return string
     */
    public function getFacebook();

    /**
     * @return string
     */
    public function getLatitude();

    /**
     * @return string
     */
    public function getLongitude();

    /**
     * @return string[]
     */
    public function getStoreIds();

    /**
     * @return string[]
     */
    public function getProductSkus();

    /**
     * @return string
     */
    public function getWorkingHoursType();

    /**
     * @return string[]
     */
    public function getWorkingHours();

    /**
     * @return bool
     */
    public function getIsWorkingHoursEnabled(): bool;

    /**
     * @return string
     */
    public function getLocationPagePath();

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @return string
     */
    public function getMetaRobots();

    /**
     * @param string $value
     * @return $this
     */
    public function setCode($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setAssignType($value);

    /**
     * @param bool $value
     * @return $this
     */
    public function setApplyByCron($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setName($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value);

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsActive($value);

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsPickupAvailable(bool $value): LocationInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setOrder($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setImagePath($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setTimezone(string $value): LocationInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setCountryId($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setRegion($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setCity($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setAddress($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setPostcode($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setEmail($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setSkype($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setWhatsapp($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setInstagram($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setFacebook($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setPhoneNumber($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setWebsiteUrl($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setLatitude($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setLongitude($value);

    /**
     * @param int[] $value
     * @return $this
     */
    public function setStoreIds($value);

    /**
     * @param string[] $value
     * @return $this
     */
    public function addProductSkus($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setWorkingHoursType($value);

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsWorkingHoursEnabled(bool $value): LocationInterface;

    /**
     * @param string $value
     * @return $this
     */
    public function setLocationPagePath($value);

    /**
     * @param string[] $value
     * @return $this
     */
    public function setWorkingHours($value);

    /**
     * @param string|string[] $value
     * @return $this
     */
    public function setProductSkus($value);

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaTitle(string $value);

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaDescription(string $value);

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaKeywords(string $value);

    /**
     * @param string $value
     * @return $this
     */
    public function setMetaRobots(string $value);

    /**
     * @return bool
     */
    public function isOpenNow();

    /**
     * @return string
     */
    public function getWorkingHoursInfo();

    /**
     * @param string $value
     * @return $this
     */
    public function setSourceCode(string $value): \MageWorx\Locations\Api\Data\LocationInterface;

    /**
     * @return string
     */
    public function getSourceCode(): ?string;

    /**
     * @return $this
     * @throws InputException
     */
    public function validateRequiredFields();

    /**
     * @return array
     */
    public function getPreparedDataForCustomer();
}
