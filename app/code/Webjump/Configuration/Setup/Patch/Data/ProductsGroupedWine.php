<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ProductsGroupedWine implements DataPatchInterface
{
    /**
     * @var ProductLinkInterfaceFactory
     */
    private $productLinkInterfaceFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var State
     */
    private $state;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ProductLinkInterfaceFactory $productLinkInterfaceFactory,
        ProductRepositoryInterface $productRepository,
        State $state
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productLinkInterfaceFactory = $productLinkInterfaceFactory;
        $this->state = $state;
        $this->productRepository = $productRepository;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $datas[] = $this->getData();
        foreach ($datas as $data) {
            $this->connection(
                $data['sku_grouped'],
                $data['link_type'],
                $data['product_type'],
                $data['first_sku_product'],
                $data['second_sku_product']
            );
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function connection($sku,$firstSku,$secondSku, $link, $product)
    {
        $firstLink = $this->productLinkInterfaceFactory->create();
        $secondLink = $this->productLinkInterfaceFactory->create();

        $firstLink  ->setSku($sku)
                    ->setLinkedProductSku($firstSku)
                    ->setLinkType($link)
                    ->setLinkedProductType($product);
        $secondLink ->setSku($sku)
                    ->setLinkedProductSku($secondSku)
                    ->setLinkType($link)
                    ->setLinkedProductType($product);
        $grouped = $this->productRepository->get($sku,true);
        $grouped->setProductLinks([$firstLink, $secondLink]);
        $this->productRepository->save($grouped);
    }
    public function getData()
    {
        return [
            [
                'sku_grouped' => 'WGR-001',
                'first_sku_product' => 'VIN-125',
                'second_sku_product' => 'VIN-126',
                'link_type' => 'associated',
                'product_type' => 'simple'
            ], [
                'sku_grouped' => 'WGR-002',
                'first_sku_product' => 'VIN-130',
                'second_sku_product' => 'VIN-132',
                'link_type' => 'associated',
                'product_type' => 'simple'
            ],
        ];
    }


    public function getAliases()
    {
        // TODO: Implement getAliases() method.
    }
    public static function getDependencies()
    {
        return [];
    }


}
