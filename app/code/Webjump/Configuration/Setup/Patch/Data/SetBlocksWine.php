<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;


class SetBlocksWine implements DataPatchInterface
{

    protected $blockFactory;

    public function __construct(
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
    }



    public function footerBlock($wineEn, $wine)
    {
        return [
                [
                    'title' => 'Footer Links1_en',
                    'identifier' => 'footer-links1_en',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>Wines</h3>
                                <li><a href="#">All wines</a></li>
                                <li><a href="#">Reds</a></li>
                                <li><a href="#">Whites</a></li>
                                <li><a href="#">Rosé</a></li>
                                <li><a href="#">Sparkling wines</a></li>
                                <li><a href="#">Frizzers</a></li>
                                <li><a href="#">Dessert</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wineEn->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer Links2_en',
                    'identifier' => 'footer-links2_en',
                    'content' =>
                        '<div class="footer-links block2">
                            <div>
                                <ul>
                                    <h3>Other products</h3>
                                    <li><a>All the products</a></li>
                                    <li><a>Accessories</a></li>
                                </ul>
                            </div>
                            <div>
                                 <ul>
                                    <h3>Account</h3>
                                    <li><a>My account</a></li>
                                    <li><a>Requests</a></li>
                                </ul>
                            </div>
                        </div>',
                    'stores' => [$wineEn->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer links3_en',
                    'identifier' => 'footer-links3_en',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>Support</h3>
                                <li><a>Shipping policy</a></li>
                                <li><a>Privacy Policy</a></li>
                                <li><a>Terms and conditions</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wineEn->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer links4_en',
                    'identifier' => 'footer-links4_en',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>WineClub</h3>
                                <li><a>About Us</a></li>
                                <li><a>Doubt Center</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wineEn->getId()],
                    'is_active' => 1,
                ],
                //-------------------INICIO PT------------//
                [
                    'title' => 'Footer Links1',
                    'identifier' => 'footer-links1',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>Vinhos</h3>
                                <li><a href="#">Todos os vinhos</a></li>
                                <li><a href="#">Tintos</a></li>
                                <li><a href="#">Brancos</a></li>
                                <li><a href="#">Rosés</a></li>
                                <li><a href="#">Espumantes</a></li>
                                <li><a href="#">Frisantes</a></li>
                                <li><a href="#">Sobremesa</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wine->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer Links2',
                    'identifier' => 'footer-links2',
                    'content' =>
                        '<div class="footer-links block2">
                            <div>
                                <ul>
                                    <h3>Outros produtos</h3>
                                    <li><a>Todos os produtos</a></li>
                                    <li><a>Acessórios</a></li>
                                </ul>
                            </div>
                            <div>
                                 <ul>
                                    <h3>Conta</h3>
                                    <li><a>Minha conta</a></li>
                                    <li><a>Pedidos</a></li>
                                </ul>
                            </div>
                        </div>',
                    'stores' => [$wine->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer links3',
                    'identifier' => 'footer-links3',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>Suporte</h3>
                                <li><a>Política de frete</a></li>
                                <li><a>Política de privacidade</a></li>
                                <li><a>Termos e condições</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wine->getId()],
                    'is_active' => 1,
                ],
                [
                    'title' => 'Footer links4',
                    'identifier' => 'footer-links4',
                    'content' =>
                        '<div class="footer-links">
                            <ul>
                                <h3>WineClub</h3>
                                <li><a>Sobre nós</a></li>
                                <li><a>Central de dúvidas</a></li>
                            </ul>
                        </div>',
                    'stores' => [$wine->getId()],
                    'is_active' => 1,
                ],

            ];
    }

    public function apply()
    {

        $wineEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN);
        $wine = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE);

        $footerData = $this->footerBlock($wineEn,$wine);

        foreach ($footerData as $data){
            $headerBlock = $this->blockFactory->create()->load($data['identifier'], 'identifier');

            if (!$headerBlock->getId()) {
                $headerBlock->setData($data)->save();
            } else {
                $headerBlock->setContent($data['content'])->save();
            }
        }

    }


    public static function getDependencies():array
    {
        return [
            WebsiteConfigure::class
        ];
    }

    public function getAliases():array
    {
        return [];
    }
}
