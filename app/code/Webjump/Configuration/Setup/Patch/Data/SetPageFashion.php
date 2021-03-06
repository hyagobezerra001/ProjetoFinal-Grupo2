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

class SetPageFashion implements DataPatchInterface
{
    private $moduleDataSetup;
    private $pageFactory;
    private $storeRepository;
    private $config;
    private $websiteRepository;

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

        $fashionEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $fashion = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE)->getId();


        $pageData = $this->setPageFashion($fashion,$fashionEN);

        foreach($pageData as $page){
            $this->pageFactory->create()->setData($page)->save();
            $this->config->saveConfig('web/default/cms_home_page',$page['identifier'], ScopeInterface::SCOPE_STORES, $page['stores'][0]);
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function setPageFashion($fashion,$fashionEN)
    {
           return [
               [
                   'title' => 'Fashion Store',
                   'page_layout' => '1column',
                   'meta_keywords' => 'Banner Fashion Loja1',
                   'meta_description' => 'Banner para loja Fashion',
                   'identifier' => 'banner_fashion',
                   'content' =>
                       '<div class="page-main-image">
                        <a href="http://fashion.develop.com.br/promocoesmoda.html">
                          <img class="banner1" src="{{media url="wysiwyg/banner1.png"}}" alt="img1">
                        </a>
                    </div>
                    <div class="message">
                        <div class="car">
                            <img class="image-car image_message" src="{{media url="wysiwyg/caminh??o.png"}}" alt="icon1">
                            <span class="text-car text_message" >Frete gr??tis para todas as compras acima de R$100</span>
                        </div>
                        <div class="card">
                            <img class="image-card image_message" src="{{media url="wysiwyg/cartao.png"}}" alt="img2">
                            <span class="text-card text_message">Pague em at?? 5x sem juros no cart??o de cr??dito</span>
                        </div>
                        <div class="return">
                            <img class=" image-return image_message" src="{{media url="wysiwyg/retornar.png"}}" alt="img3">
                            <span class="text-return text_message">Primeira troca garantida sem custos adicionais</span>
                        </div>
                    </div>
                    <hr class="hr-top">
                    <div class="main_banner">
                        <div class="img1">
                        <a href="http://fashion.develop.com.br/roupasmoda/blusas.html">
                             <img src="{{media url="wysiwyg/banner_basicos.jpg"}}" alt="img4">
                        </a>

                        </div>
                        <div class="img2">
                        <a href="http://fashion.develop.com.br/roupasmoda/saias.html">
                            <img src="{{media url="wysiwyg/banner_saias.jpg"}}" alt="img5">
                        </a>
                        </div>
                        <div class="img3">
                            <a href="http://fashion.develop.com.br/roupasmoda.html">
                                <img src="{{media url="wysiwyg/mulher-listrado.png"}}" alt="img6">
                            </a>
                        </div>
                        <div class="img4">
                            <a href="http://fashion.develop.com.br/roupasmoda/vestidos.html">
                                <img src="{{media url="wysiwyg/mulher-onca.png"}}" alt="img7">
                            </a>
                        </div>
                    </div>
                    <div class="maincontainer">
                        <div class="about-magnolia">
                            <hr />
                        <div class="div-text">
                            <h3 class="about-magnolia-text">sobre a magnolia</h3>
                        </div>
                            <hr />
                    </div>
                          <div class="content-text">
                            <div class="magnolia">
                                <span class="part1">Magnolia L. ?? um g??nero de plantas com flor, da ordem Magnoliales</span>

                                <span class="part2">Agrupa as esp??cies maioritariamente arb??reas conhecidas pelo nome comum de magn??lias. Na sua presente circunscri????o taxon??mica o g??nero foi alargado para incluir as esp??cies que se encontravam dispersas pelos g??neros Magnolia, Manglietia, Michelia, Talauma, Aromadendron, Kmeria, Pachylarnax e Alcimandra (todos da antiga subfam??lia Magnolioideae), resultando num g??nero monofil??tico com cerca de 297 esp??cies.[2] O g??nero distribui-se pelas regi??es subtropicais e tropicais do leste e sueste da ??sia (incluindo a Mal??sia) e pelas Am??ricas, com centros de diversidade no Sueste Asi??tico e no norte da Am??rica do Sul. O g??nero inclui diversas esp??cies amplamente utilizadas como ??rvore ornamental nas regi??es subtropicais e temperadas de ambos os hemisf??rios.</span>
                            </div>

                            <div class="decriptionlow">
                                <span class="part3">Descri????o da Marca</span>
                                <span class="part4">O g??nero Magnolia tem como ep??nimo o nome de Pierre Magnol, um bot??nico de Montpellier (Fran??a). A primeira esp??cie identificada deste g??nero foi Magnolia virginiana, encontrada por mission??rios enviados ?? Am??rica do Norte na d??cada de 1680. J?? em pleno s??culo XVIII foi descrita, tamb??m a partir de amostras norte-americanas, a esp??cie Magnolia grandiflora, hoje a esp??ci mais conhecida do g??nero dado ser amplamente utilizada como ??rvore ornamental nas regi??es subtropicais e temperadas de clima moderado de todo o mundo.</span>
                            </div>
                        </div>
                    </div>',
                   'layout_update_xml' => '',
                   'url_key' => 'banner_fashion',
                   'is_active' => 1,
                   'stores' => [$fashion],
                   'sort_order' => 0,
               ],
               //----------INICIO EN--------------//
               [
                   'title' => 'Fashion Store En',
                   'page_layout' => '1column',
                   'meta_keywords' => 'Banner Fashion Loja1 En',
                   'meta_description' => 'Banner for store Fashion',
                   'identifier' => 'banner_fashion_en',
                   'content' =>
                       '<div class="page-main-image">
                        <a href="http://fashion.develop.com.br/promotion.html">
                          <img class="banner1" src="{{media url="wysiwyg/banner1.png"}}" alt="img1">
                        </a>
                    </div>
                    <div class="message">
                        <div class="car">
                            <img class="image-car image_message" src="{{media url="wysiwyg/caminh??o.png"}}" alt="icon1">
                            <span class="text-car text_message" >Free shipping on all purchases over $100</span>
                        </div>
                        <div class="card">
                            <img class="image-card image_message" src="{{media url="wysiwyg/cartao.png"}}" alt="img2">
                            <span class="text-card text_message">Pay up to 5x interest free on your credit card</span>
                        </div>
                        <div class="return_en">
                            <img class=" image-return image_message" src="{{media url="wysiwyg/retornar.png"}}" alt="img3">
                            <span class="text-return_en text_message">First exchange guaranteed at no extra cost</span>
                        </div>
                    </div>
                    <hr class="hr-top">
                    <div class="main_banner">
                        <div class="img1">
                        <a href="http://fashion.develop.com.br/clothes/blouse.html">
                             <img src="{{media url="wysiwyg/banner_basicos.jpg"}}" alt="img4">
                        </a>

                        </div>
                        <div class="img2">
                        <a href="http://fashion.develop.com.br/clothes/skirt.html">
                            <img src="{{media url="wysiwyg/banner_saias.jpg"}}" alt="img5">
                        </a>
                        </div>
                        <div class="img3">
                            <a href="http://fashion.develop.com.br/clothes.html">
                                <img src="{{media url="wysiwyg/mulher-listrado.png"}}" alt="img6">
                            </a>
                        </div>
                        <div class="img4">
                            <a href="http://fashion.develop.com.br/clothes/dress.html">
                                <img src="{{media url="wysiwyg/mulher-onca.png"}}" alt="img7">
                            </a>
                        </div>
                    </div>
                    <div class="maincontainer">
                        <div class="about-magnolia">
                            <hr />
                        <div class="div-text">
                            <h3 class="about-magnolia-text">about the magnolia</h3>
                        </div>
                            <hr />
                    </div>
                          <div class="content-text">
                            <div class="magnolia">
                                <span class="part1">Magnolia L. is a genus of flowering plants in the order Magnoliales</span>

                                <span class="part2">It groups the mostly arboreal species known by the common name of magnolias. In its present taxonomic circumscription, the genus was extended to include species that were dispersed in the genera Magnolia, Manglietia, Michelia, Talauma, Aromadendron, Kmeria, Pachylarnax and Alcimandra (all of the former subfamily Magnolioideae), resulting in a monophyletic genus with about 297 species.[2] The genus is distributed in the subtropical and tropical regions of East and Southeast Asia (including Malesia) and the Americas, with centers of diversity in Southeast Asia and northern South America. The genus includes several species widely used as an ornamental tree. in the subtropical and temperate regions of both hemispheres.</span>
                            </div>

                            <div class="decriptionlow">
                                <span class="part3">Brand Description</span>
                                <span class="part4">The genus Magnolia is eponymous after Pierre Magnol, a botanist from Montpellier (France). The first identified species of this genus was Magnolia virginiana, found by missionaries sent to North America in the 1680s. As early as the 18th century, the species Magnolia grandiflora was described, also from North American samples, today the best known species. of the genus as it is widely used as an ornamental tree in subtropical and temperate regions of moderate climate around the world.</span>
                            </div>
                        </div>
                    </div>',
                   'layout_update_xml' => '',
                   'url_key' => 'banner_fashion_en',
                   'is_active' => 1,
                   'stores' => [$fashionEN],
                   'sort_order' => 1,
               ]
               //----------FIM EN--------------//
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
