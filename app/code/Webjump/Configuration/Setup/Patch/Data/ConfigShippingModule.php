<?php

declare(strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Webjump\Configuration\Model\ArrayModel\ArrayShipping;
use Magento\Setup\Module\Setup;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigShippingModule implements DataPatchInterface
{
    private const TABLE_SHIPPING = 'shipping_tablerate';

    private $websiteRepository;
    private $moduleaDataSetup;
    private $config;
    private $arrayShipping;
    private $setup;
    private $storeRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $config,
        WebsiteRepositoryInterface $websiteRepository,
        ArrayShipping $arrayShipping,
        Setup $setup,
        StoreRepositoryInterface $storeRepository
    )
    {

        $this->moduleaDataSetup = $moduleDataSetup;
        $this->config = $config;
        $this->websiteRepository = $websiteRepository;
        $this->arrayShipping = $arrayShipping;
        $this->setup = $setup;
        $this->storeRepository = $storeRepository;
    }


    public function apply()
    {
        $this->moduleaDataSetup->getConnection()->startSetup();

        $keysColuns = $this->arrayShipping->colunsData();
        $data = $this->arrayShipping->dataBalanceBr();
        $dataUs = $this->arrayShipping->dataBalanceUs();

        $arrayMerge = array_merge($data, $dataUs);
        $this->setup->getConnection()->insertArray(static::TABLE_SHIPPING, $keysColuns, $arrayMerge);

        $configuration = $this->arrayShipping->arrayConfiguration();
        foreach ($configuration as $config){
            $this->config->saveConfig($config[0], $config[1]);
        }

        $translateLabel = $this->arrayShipping->arrayConfigurationEn();


        $fashionEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();
        foreach ($translateLabel as $label){
            $this->config->saveConfig(
                $label[0],
                $label[1],
                ScopeInterface::SCOPE_STORES,
                $fashionEN
            );
        }

        foreach ($translateLabel as $label){
            $this->config->saveConfig(
                $label[0],
                $label[1],
                ScopeInterface::SCOPE_STORES,
                $wineEN
            );
        }
        $this->moduleaDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies():array
    {
        return [];
    }


    public function getAliases():array
    {
        return [];
    }
}
