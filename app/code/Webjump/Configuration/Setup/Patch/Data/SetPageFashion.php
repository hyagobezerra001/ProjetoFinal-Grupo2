<?php

declare (strict_types = 1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class SetPageFashion implements DataPatchInterface
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

        $fashionEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $fashion = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE)->getId();

        $pageData = [
            'title' => 'Banner-Fashion',
            'page_layout' => '1column',
            'meta_keywords' => 'Banner Fashion Loja1',
            'meta_description' => 'Banner para loja Fashion',
            'identifier' => 'banner',
            'content_heading' => 'Rohan Custom CMS Page',
            'content' =>
            '<div class="banner">
                <h1 class="titulo-banner">Descontos Progressivos
                    <span class="subtitulo">em todas as peças</span>
                </h1>
                <button class="button-banner" type="name">Comprar Agora</button>
                <img class="img-banner" src="{{media url="wysiwyg/banner.png"}}" alt="">
            </div>

            <div class="car">
                <span class="text-car">frente grátis para todas as compras acima de R$100</span>
            </div>
            <div class="main_banner divflex">
                <div class="sub_banner_top divflex">
                    <img class="banner-footer2" src="{{media url="wysiwyg/garotas.png"}}" alt="imagem-banner2" />
                    <img class="outline" src="{{media url="wysiwyg/saiapena.png"}}" alt="imagem-banner1" />
                </div>

                <div class="sub_banner_bottom divflex">
                    <div class="banner-footer3 divflex">
                        <a href="#">
                            <img src="{{media url="wysiwyg/mulher-listrado.png"}}" alt="imagem-banner3" />
                        </a>
                    </div>
                    <div class="banner-footer4 divflex">
                        <a href="#">
                            <img src="{{media url="wysiwyg/mulher-onca.png"}}"alt="imagem-banner4" />
                        </a>
                    </div>
                </div>
            </div>',
            'layout_update_xml' => '',
            'url_key' => 'banner',
            'is_active' => 1,
            'stores' => [$fashionEN, $fashion],
            'sort_order' => 0,
        ];
        $this->moduleDataSetup->startSetup();
        $this->pageFactory->create()->setData($pageData)->save();
        $this->moduleDataSetup->endSetup();

        $this->config->saveConfig('web/default/cms_home_page','banner', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $fashion);

        $this->moduleDataSetup->getConnection()->endSetup();
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
