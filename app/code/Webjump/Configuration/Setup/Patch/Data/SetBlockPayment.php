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
    const IDFashion = 'payment-fashion';
    const TITTLEFashion = 'Payment Fashion';
    const IDFashionEn = 'payment-fashion-en';
    const TITTLEFashionEn = 'Payment Fashion En';
    const IDWineEn = 'payment-wine-en';
    const TITTLEWineEn = 'Payment Wine En';
    const IDWine = 'payment-wine';
    const TITTLEWine = 'Payment Wine';

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

    public function contentFashion($fashion)
    {
        return [
                'title' => self::TITTLEFashion,
                'identifier' => self::IDFashion,
                'content'=>
                    '<div class="payment-info-blockFashion">
                        <h3>Parcele em até 12x, sem juros, com parcela mínima de R$50,00.</h3>
                    </div>',
                'stores' => [$fashion->getId()],
                'is_active' => 1,
        ];
    }
    public function contentWine($wine)
    {
        return [
            'title' => self::TITTLEWine,
            'identifier' => self::IDWine,
            'content'=>
                '<div class="payment-info-blockWine">
                        <h3>Parcele em até 12x, sem juros, com parcela mínima de R$50,00.</h3>
                    </div>',
            'stores' => [$wine->getId()],
            'is_active' => 1,
        ];
    }
    public function contentFashionEn($fashionEn)
    {
        return [

            'title' => self::TITTLEFashionEn,
            'identifier' => self::IDFashionEn,
            'content'=>
                '<div class="payment-info-blockFashion">
                        <h3>Payment in up to 12x, interest-free, with a minimum installment of $50.00.</h3>
                    </div>',
            'stores' => [$fashionEn->getId()],
            'is_active' => 1,
        ];
    }
    public function contentWineEn($wineEn)
    {
        return [

            'title' => self::TITTLEWineEn,
            'identifier' => self::IDWineEn,
            'content'=>
                '<div class="payment-info-blockWine">
                        <h3>Payment in up to 12x, interest-free, with a minimum installment of $50.00.</h3>
                    </div>',
            'stores' => [$wineEn->getId()],
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

        $setContentFashion = $this->contentFashion($fashion);
        $setContentFashionEn = $this->contentFashionEn($fashionEn);

        $setContentWine = $this->contentWine($wine);
        $setContentWineEn = $this->contentWineEn($wineEn);

        $block = $this->blockFactory->create();

        $block->setData($setContentFashion)->save();
        $block->setData($setContentFashionEn)->save();

        $block->setData($setContentWine)->save();
        $block->setData($setContentWineEn)->save();

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
