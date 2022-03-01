<?php

declare(strict_types=1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;

class SetBlockPayment implements DataPatchInterface
{
    const ID = 'payment';
    const TITTLE = 'Payment';
    const IDEn = 'payment-En';
    const TITTLEEn = 'Payment En';

    private $moduleDataSetup;
    private $blockFactory;
    private $storeRepository;
    private $blockRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
        $this->blockRepository = $blockRepository;
    }

    public function content($fashion, $wine)
    {
        return [

                'title' => self::TITTLE,
                'identifier' => self::ID,
                'content'=>
                    '<div class="payment-info-block">
                        <h3>Parcele em até 12x, sem juros, com parcela mínima de R$50,00.</h3>
                    </div>',
                'stores' => [$fashion->getId(),$wine->getId()],
                'is_active' => 1,

        ];
    }

    public function contentEn($fashionEn, $wineEn)
    {
        return [

            'title' => self::TITTLEEn,
            'identifier' => self::IDEn,
            'content'=>
                '<div class="payment-info-block">
                        <h3>Payment in up to 12x, interest-free, with a minimum installment of $50.00.</h3>
                    </div>',
            'stores' => [$fashionEn->getId(),$wineEn->getId()],
            'is_active' => 1,
        ];
    }
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $fashionEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN);
        $wineEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN);

        $fashion = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE);
        $wine = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE);

        $setContent = $this->content($fashion,$wine);
        $setContentEn = $this->contentEn($fashionEn,$wineEn);

        $block = $this->blockFactory->create();

        $block->setData($setContent)->save();
        $block->setData($setContentEn)->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }
    public static function getDependencies()
    {
        return [WebsiteConfigure::class];
    }

    public function getAliases()
    {
        return [];
    }
}
