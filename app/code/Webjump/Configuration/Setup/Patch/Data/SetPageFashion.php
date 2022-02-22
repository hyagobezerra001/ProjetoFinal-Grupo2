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

        $pageData = $this->setPageFashion($fashionEN, $fashion);

        $this->moduleDataSetup->startSetup();
        $this->pageFactory->create()->setData($pageData)->save();
        $this->moduleDataSetup->endSetup();

        $this->config->saveConfig('web/default/cms_home_page','banner_fashion', ScopeInterface::SCOPE_STORES, $fashion);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function setPageFashion($fashionEN, $fashion)
    {
           return [
               'title' => 'Fashion Store',
               'page_layout' => '1column',
               'meta_keywords' => 'Banner Fashion Loja1',
               'meta_description' => 'Banner para loja Fashion',
               'identifier' => 'banner_fashion',
               'content' =>
                    '<p>
                        <img class="banner1" src="{{media url="wysiwyg/banner1.png"}}" alt="img1">
                    </p>
                    <div class="message">
                        <div class="car">
                            <img class="image-car" src="{{media url="wysiwyg/caminhão.png"}}" alt="icon1">
                            <span class="text-car">grátis para todas as<br>compras acima de R$100</span>
                        </div>
                        <div class="card">
                            <img src="{{media url="wysiwyg/cartao.png"}}" alt="img2">
                            <span class="text-card">pague em até 5x sem <br>juros no cartão de crédito</span>
                        </div>
                        <div class="return">
                            <img src="{{media url="wysiwyg/retornar.png"}}" alt="img3">
                            <span class="text-return">primeira troca reservada
                            <br>sem custos adicionais</span>
                        </div>
                    </div>
                    <div class="main_banner">
                        <div class="img1">
                            <img src="{{media url="wysiwyg/banner_basicos.jpg"}}" alt="img4">
                        </div>
                        <div class="img2">
                            <img src="{{media url="wysiwyg/banner_saias.jpg"}}" alt="img5">
                        </div>
                        <div class="img3">
                            <a href="#">
                                <img src="{{media url="wysiwyg/mulher-listrado.png"}}" alt="img6">
                            </a>
                        </div>
                        <div class="img4">
                            <a href="#">
                                <img src="{{media url="wysiwyg/mulher-onca.png"}}" alt="img7">
                            </a>
                        </div>
                    </div>
                    <div class="maincontainer">
                        <div class="about-magnolia">
                            <div class="div1"></div>
                            <div>
                                <h3 class="about-magnolia-text">
                                    sobre a magnolia
                                </h3>
                            </div>
                            <div class="div2"></div>
                        </div>
                        <div class="content-text">
                            <div class="magnolia">
                                <span class="part1">Magnolia L. é um género de plantas com flor, da ordem Magnoliales</span>

                                <span class="part2">Agrupa as espécies maioritariamente arbóreas conhecidas pelo nome comum de magnólias. Na sua presente circunscrição taxonómica o género foi alargado para incluir as espécies que se encontravam dispersas pelos géneros Magnolia, Manglietia, Michelia, Talauma, Aromadendron, Kmeria, Pachylarnax e Alcimandra (todos da antiga subfamília Magnolioideae), resultando num género monofilético com cerca de 297 espécies.[2] O género distribui-se pelas regiões subtropicais e tropicais do leste e sueste da Ásia (incluindo a Malésia) e pelas Américas, com centros de diversidade no Sueste Asiático e no norte da América do Sul. O género inclui diversas espécies amplamente utilizadas como árvore ornamental nas regiões subtropicais e temperadas de ambos os hemisférios.</span>
                            </div>

                            <div class="decriptionlow">
                                <span class="part3">Descrição da Marca</span>
                                <span class="part4">O género Magnolia tem como epónimo o nome de Pierre Magnol, um botânico de Montpellier (França). A primeira espécie identificada deste género foi Magnolia virginiana, encontrada por missionários enviados à América do Norte na década de 1680. Já em pleno século XVIII foi descrita, também a partir de amostras norte-americanas, a espécie Magnolia grandiflora, hoje a espéci mais conhecida do género dado ser amplamente utilizada como árvore ornamental nas regiões subtropicais e temperadas de clima moderado de todo o mundo.</span>
                            </div>
                        </div>
                    </div>',
               'layout_update_xml' => '',
               'url_key' => 'banner_fashion',
               'is_active' => 1,
               'stores' => [$fashionEN, $fashion],
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
