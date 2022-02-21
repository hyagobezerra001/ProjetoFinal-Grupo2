<?php

declare (strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class SetPageWine implements DataPatchInterface
{

    private $moduleDataSetup;
    private $pageFactory;
    private $storeRepository;
    private $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory,
        StoreRepositoryInterface $storeRepository,
        ConfigInterface $config
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
        $this->storeRepository = $storeRepository;
        $this->config = $config;
    }

    public function apply()
    {

        $this->moduleDataSetup->getConnection()->startSetup();

        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();
        $wine = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE)->getId();

        $pageData = $this->setPageFashion($wineEN, $wine);

        $this->moduleDataSetup->startSetup();
        $this->pageFactory->create()->setData($pageData)->save();
        $this->moduleDataSetup->endSetup();

        $this->config->saveConfig('web/default/cms_home_page','banner_fashion', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $wine);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function setPageFashion($wineEN, $wine)
    {
        return [
            'title' => 'Banner-Fashion',
            'page_layout' => '1column',
            'meta_keywords' => 'Banner Wine Loja1',
            'meta_description' => 'Banner para loja Wine',
            'identifier' => 'banner_fashion',
            'content' =>
                '<h1>Hello</h1>',
            'layout_update_xml' => '',
            'url_key' => 'banner_fashion',
            'is_active' => 1,
            'stores' => [$wineEN, $wine],
            'sort_order' => 0,
        ];
    }

    public static function getDependencies():array
    {
        return [
            WebsiteConfigure:: class
        ];
    }

    public function getAliases():array
    {
        return [];
    }
}
