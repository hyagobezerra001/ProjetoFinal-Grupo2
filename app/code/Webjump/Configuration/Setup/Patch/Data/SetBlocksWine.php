<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;



class SetBlocksWine implements DataPatchInterface
{

    /**
     * @var BlockFactory
     */
    private $blockFactory;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    private $storeRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        
        $wineStore = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE);
        $wineStoreEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN);
        $headerData = $this->getData();
        $headerBlock = $this->blockFactory->create()->load($headerData);

        $this->moduleDataSetup->getConnection()->startSetup();
    }

    public function getData()
    {
        return [
            [
                'title' => 'Footer Links1',
                'identifier' => 'footer-links1',
                'content' => '<div class="footer-links"> <ul>
                                <h3>Vinhos</h3>
                                <li><a>Todos os vinhos</a></li>
                                <li><a>Tintos</a></li>
                                <li><a>Brancos</a></li>
                                <li><a>Rosés</a></li>
                                <li><a>Espumantes</a></li>
                                <li><a>Frisantes</a></li>
                                <li><a>Sobremesa</a></li>
                                </ul>
                                </div>',
                'stores' => 4,5,
                'is_active' => 1,
            ],
            [
                'title' => 'Footer Links2',
                'identifier' => 'footer-links2',
                'content' => '<div class="footer-links block2">
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
                'stores' => 4,5,
                'is_active' => 1,
            ],
            [
                'title' => 'Footer links3',
                'identifier' => 'footer-links3',
                'content' => '<div class="footer-links">
                                <ul>
                                <h3>Suporte</h3>
                                <li><a>Política de frete</a></li>
                                <li><a>Política de privacidade</a></li>
                                <li><a>Termos e condições</a></li>
                                </ul>
                                </div>',
                'stores' => 4,5,
                'is_active' => 1,
            ],
            [
                'title' => 'Footer links4',
                'identifier' => 'footer-links4',
                'content' => '<div class="footer-links">
                                <ul>
                                <h3>WineClub</h3>
                                <li><a>Sobre nós</a></li>
                                <li><a>Central de dúvidas</a></li>
                                </ul>
                                </div>',
                'stores' => 4,5,
                'is_active' => 1,
            ]
        ];
    }
    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return[];
    }
}