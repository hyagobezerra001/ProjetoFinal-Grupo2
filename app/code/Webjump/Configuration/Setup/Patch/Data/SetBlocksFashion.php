<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;


class SetBlocksFashion implements DataPatchInterface
{
    const BLOCK_IDENTIFIER = 'footer_links_block1';

    protected $blockFactory;


    public function __construct(
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
    }



    public function footerBlock($fashionEn, $fashion)
    {
        return [
            [
                'title' => 'Footer Links1_Fashion',
                'identifier' => 'footer-links1_Fashion',
                'content' =>
                    '<div class="main-footer">
                        <div class="link1">
                        <h3>INSTITUCIONAL</h3>
                        <ul>
                            <li><a>A Marca</a></li>
                            <li><a>Nossas Lojas</a></li>
                            <li><a>Trabalhe Conosco</a></li>
                            <li><a>Politica de Privacidade</a></li>
                        </ul>
                        </div>
                        <div class="link2">
                        <h3>AJUDA</h3>
                        <ul>
                            <li><a>Frente e Entrega</a></li>
                            <li><a>Troca e Devolução</a></li>
                            <li><a>Segurança</a></li>
                            <li><a>Central de Atendimento</a></li>
                            <li><a>Perguntas Frequentes</a></li>
                        </ul>
                        </div>
                    </div>',
                'stores' => [$fashionEn->getId(),$fashion->getId()],
                'is_active' => 1,
            ],
        ];
    }

    public function apply()
    {

        $fashion = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN);
        $fashionEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE);

        $footerData = $this->footerBlock($fashion, $fashionEn);

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