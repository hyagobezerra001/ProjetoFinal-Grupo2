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
use Magento\Store\Api\WebsiteRepositoryInterface;


class SetPageWine implements DataPatchInterface
{

    private $websiteRepository;
    private $moduleDataSetup;
    private $pageFactory;
    private $storeRepository;
    private $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory,
        StoreRepositoryInterface $storeRepository,
        ConfigInterface $config,
        WebsiteRepositoryInterface $websiteRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
        $this->storeRepository = $storeRepository;
        $this->config = $config;
        $this->websiteRepository = $websiteRepository;
    }

    public function apply()
    {

        $this->moduleDataSetup->getConnection()->startSetup();

        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();
        $wine = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE)->getId();
        $pageData = $this->setPageFashion($wine,$wineEN);
        foreach($pageData as $page){
            $this->pageFactory->create()->setData($page)->save();
            $this->config->saveConfig('web/default/cms_home_page',$page['identifier'], ScopeInterface::SCOPE_STORES, $page['stores'][0]);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function setPageFashion($wine,$wineEN)
    {
        return [
            [
                'title' => 'Wine Store',
                'page_layout' => '1column',
                'meta_keywords' => 'Banner Wine Loja1',
                'meta_description' => 'Banner para loja Wine',
                'identifier' => 'banner_wine',
                'content' =>
                    '<div class="page-main-image-wine">
                    <a href="http://wine.develop.com.br/vinhos-italianos.html">
                        <img src="{{media url="wysiwyg/banner-principal.jpg"}}" alt="">
                    </a>
                </div>
                <div class="container">
                    <div class="img1">
                        <a href="http://wine.develop.com.br/sign-wine-mensal.html">
                            <img src="{{media url="wysiwyg/010.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img2">
                        <a href="http://wine.develop.com.br/vinhoswine.html">
                            <img src="{{media url="wysiwyg/img2.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img3">
                        <a href="http://wine.develop.com.br/espumanteswine.html">
                            <img src="{{media url="wysiwyg/img3.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img4">
                        <img src="{{media url="wysiwyg/img4.png"}}" alt="">
                    </div>
                </div>
                <div class="main-slider">
                    <h3>Lançamentos da semana</h3>
                        {{widget type="Magento\CatalogWidget\Block\Product\ProductsList" show_pager="0" products_count="6" template="Magento_CatalogWidget::product/widget/content/grid.phtml" conditions_encoded="^[`1`:^[`type`:`Magento||CatalogWidget||Model||Rule||Condition||Combine`,`aggregator`:`all`,`value`:`1`,`new_child`:``^]^]"}}</div>',
                'layout_update_xml' => '',
                'url_key' => 'banner_wine',
                'is_active' => 1,
                'stores' => [$wine],
                'sort_order' => 0,
            ],
                        //INICIO EN//
            [
                'title' => 'Wine Store En',
                'page_layout' => '1column',
                'meta_keywords' => 'Banner Wine Loja1 En',
                'meta_description' => 'Banner para loja Wine En',
                'identifier' => 'banner_wine_en',
                'content' =>
                    '<div class="page-main-image-wine">
                    <a href="http://wine.develop.com.br/kits/promotion.html">
                        <img src="{{media url="wysiwyg/banner-principal.jpg"}}" alt="">
                    </a>
                </div>
                <div class="container">
                    <div class="img1">
                        <a href="http://wine.develop.com.br/premium/oenologists.html">
                            <img src="{{media url="wysiwyg/010.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img2">
                        <a href="http://wine.develop.com.br/wines.html">
                            <img src="{{media url="wysiwyg/img2.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img3">
                        <a href="http://wine.develop.com.br/sparklingwines.html">
                            <img src="{{media url="wysiwyg/img3.png"}}" alt="">
                        </a>
                    </div>
                    <div class="img4">
                        <img src="{{media url="wysiwyg/img4.png"}}" alt="">
                    </div>
                </div>',
                'layout_update_xml' => '',
                'url_key' => 'banner_wine_en',
                'is_active' => 1,
                'stores' => [$wineEN],
                'sort_order' => 1,
            ]
                        //FIM EN


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

