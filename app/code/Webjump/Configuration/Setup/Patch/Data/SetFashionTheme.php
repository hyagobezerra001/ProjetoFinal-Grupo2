<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Laminas\Filter\ToInt;
use Magento\Store\Model\StoreManagerInterface;

class SetFashionTheme implements DataPatchInterface
{
    private $moduleDataSetup;
    private $writer;
    private $themeProvider;
    private $storeManager;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface          $writer,
        ThemeProviderInterface   $themeProvider,
        StoreManagerInterface $storeManager
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->writer = $writer;
        $this->themeProvider = $themeProvider;
        $this->storeManager = $storeManager;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $theme = $this->themeProvider->getThemeByFullPath('frontend/webjump_themes/theme-fashion');

        $storeId = $this->storeManager->getStore(WebsiteConfigure::WEBSITE_FASHION_CODE)->getId();
        $storeIdEN = $this->storeManager->getStore(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $this->writer->save(
            'design/theme/theme_id',
            $theme->getId(),
            ScopeInterface::SCOPE_STORES,
            $storeId
        );
        $this->writer->save(
            'design/theme/theme_id',
            $theme->getId(),
            ScopeInterface::SCOPE_STORES,
            $storeIdEN
        );
        $this->moduleDataSetup->getConnection()->endSetup();
    }
    public function getAliases(): array
    {
        return [];
    }
    public static function getDependencies(): array
    {
        return [
            WebsiteConfigure:: class
        ];
    }
}
