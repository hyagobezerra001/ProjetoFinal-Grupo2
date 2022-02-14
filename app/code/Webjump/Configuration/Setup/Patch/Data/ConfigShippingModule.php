<?php

declare(strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Webjump\Configuration\Model\ArrayModel\ArrayShipping;
use Magento\Setup\Module\Setup;

class ConfigShippingModule implements DataPatchInterface
{
    private const TABLE_SHIPPING = 'shipping_tablerate';

    private $websiteRepository;
    private $moduleaDataSetup;
    private $config;
    private $arrayShipping;
    private $setup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $config,
        WebsiteRepositoryInterface $websiteRepository,
        ArrayShipping $arrayShipping,
        Setup $setup
    )
    {

        $this->moduleaDataSetup = $moduleDataSetup;
        $this->config = $config;
        $this->websiteRepository = $websiteRepository;
        $this->arrayShipping = $arrayShipping;
        $this->setup = $setup;
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
