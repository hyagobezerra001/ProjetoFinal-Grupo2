<?php

declare (strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Store\Model\ScopeInterface;


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

        $this->config->saveConfig('web/default/cms_home_page','banner_wine', ScopeInterface::SCOPE_STORES, $wine);
        $this->config->saveConfig('web/default/cms_home_page','banner_wine_en', ScopeInterface::SCOPE_STORES, $wineEN);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function setPageFashion($wineEN, $wine)
    {
        return [
            'title' => 'Wine Store',
            'page_layout' => '1column',
            'meta_keywords' => 'Banner Wine Loja1',
            'meta_description' => 'Banner para loja Wine',
            'identifier' => 'banner_wine',
            'content' =>
                '<p>
                    <img src="{{media url="wysiwyg/banner-principal.jpg"}}" alt="">
                </p>
                <div class="container">
                    <div class="img1"><img src="{{media url="wysiwyg/010.png"}}" alt=""></div>
                    <div class="img2"><img src="{{media url="wysiwyg/img2.png"}}" alt=""></div>
                    <div class="img3"><img src="{{media url="wysiwyg/img3.png"}}" alt=""></div>
                    <div class="img4"><img src="{{media url="wysiwyg/img4.png"}}" alt=""></div>
                </div>',
            'layout_update_xml' => '',
            'url_key' => 'banner_wine',
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

