<?php

declare(strict_types=1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Setup\Module\Setup;


class ConfigLocale implements DataPatchInterface
{
    private $moduleDataSetup;
    private $storeRepository;
    private $config;
    private $setup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $config,
        StoreRepositoryInterface $storeRepository,
        Setup $setup
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->config = $config;
        $this->storeRepository = $storeRepository;
        $this->setup = $setup;
    }

    public static function getDependencies()
    {
        return [
            WebsiteConfigure:: class
        ];
    }
    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->config->saveConfig(
            'system/currency/installed',
            'BRL,USD',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );

        $this->setup->getConnection()->insertArray(
            'directory_currency_rate',
            ['currency_from', 'currency_to', 'rate'],
            [['BRL', 'USD', 0.19104321]]
        );

        $fashionEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();

        $this->config->saveConfig(
            'general/locale/code',
            'en_US',
            ScopeInterface::SCOPE_STORES,
            $fashionEN
        );

        $this->config->saveConfig(
            'currency/options/allow',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $fashionEN
        );

        $this->config->saveConfig(
            'currency/options/default',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $fashionEN
        );

        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();

        $this->config->saveConfig(
            'general/locale/code',
            'en_US',
            ScopeInterface::SCOPE_STORES,
            $wineEN
        );

        $this->config->saveConfig(
            'currency/options/allow',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $wineEN
        );

        $this->config->saveConfig(
            'currency/options/default',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $wineEN
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
