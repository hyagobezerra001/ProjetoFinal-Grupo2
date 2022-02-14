<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class SetPaymentMethods implements DataPatchInterface
{
    private $config;
    private $storeRepository;
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StoreRepositoryInterface $storeRepository,
        ConfigInterface          $config
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->storeRepository = $storeRepository;
        $this->config = $config;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $fashionEN = $this->storeRepository->get(WebsiteConfigure:: WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();

        $this->config->saveConfig(
            'payment/banktransfer/active',
            true,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/banktransfer/title',
            'Transferência Bancária',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/checkmo/title',
            'Cheque ou Dinheiro',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/checkmo/active',
            true,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/checkmo/title',
            'Cheque ou Dinheiro',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/banktransfer/title',
            'Transferência Bancária',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            '0'
        );
        $this->config->saveConfig(
            'payment/checkmo/title',
            'Check/Money Order',
            ScopeInterface::SCOPE_STORES,
            $fashionEN
        );
        $this->config->saveConfig(
            'payment/checkmo/title',
            'Check/Money Order',
            ScopeInterface::SCOPE_STORES,
            $wineEN
        );
        $this->config->saveConfig(
            'payment/banktransfer/title',
            'Bank Transfer',
            ScopeInterface::SCOPE_STORES,
            $fashionEN
        );
        $this->config->saveConfig(
            'payment/banktransfer/title',
            'Bank Transfer',
            ScopeInterface::SCOPE_STORES,
            $wineEN
        );
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [
            WebsiteConfigure::class
        ];
    }

    public function getAliases()
    {
        return [];
    }
}
