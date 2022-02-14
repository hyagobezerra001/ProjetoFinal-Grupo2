<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Eav\Model\AttributeSetRepository;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection;
use Magento\Framework\App\Area;
use Magento\Framework\File\Csv;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\State;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSetCollection;

class CreateProducts implements DataPatchInterface
{
    const PATH = __DIR__ . '/csvFiles/wineProducts.csv';

    private $productRepository;
    private $moduleDataSetup;
    private $csv;
    private $websiteRepository;
    private $attributeSetRepository;
    private $state;
    private $categoryRepository;
    private $collectionFactory;
    private $collection;
    private $productFactory;
    /**
     * @var AttributeSetCollection
     */
    private $collectionSetFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Csv $csv,
        WebsiteRepositoryInterface $websiteRepository,
        AttributeSetRepository $attributeSetRepository,
        State $state,
        CategoryRepository $categoryRepository,
        CollectionFactory $collectionFactory,
        Collection $collection,
        ProductFactory $productFactory,
        ProductRepository $productRepository,
        AttributeSetCollection $collectionSetFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->csv = $csv;
        $this->websiteRepository = $websiteRepository;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->state = $state;
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
        $this->collection = $collection;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->collectionSetFactory = $collectionSetFactory;

    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $products = $this->getCsv(self::PATH);

        $wine = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE)->getId();

        foreach ($products as $product){
            $attributeSetId = $this->getAttributeSetId($product[1]);
            $categoryId = $this->getIdCategory([$product[3],$product[4]]);

            $productSet = $this->productFactory->create();
            $productSet->setSku($product[0])
                        ->setCategoryIds($categoryId)
                        ->setWebsiteIds([$wine])
                        ->setVisibility(4)
                        ->setTypeId($product[2])
                       ->setName($product[6])
                       ->setDescription($product[7])
                       ->setShortDescription ($product[8])
                       ->setWeight($product[9])
                       ->setProductOnline($product[10])
                       ->setPrice($product[13])
                       ->setUrlKey($product[14])
                       ->setMetaTitle($product[15])
                       ->setMetaDescription($product[16])
                       ->addImageToMediaGallery($product[17], ['image', 'small_image', 'thumbnail'], false, false)
                       ->setCustomAttribute($product[18], $product[19])
                       ->setAttributeSetId($attributeSetId)
                       ->setStockData ([
                                'use_config_manage_stock' => 0,
                               'manage_stock' => 1,
                               'is_in_stock' => 1,
                               'qty' => $product[20]
                           ]);
            $this->productRepository->save($productSet);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
    public function getCsv ($file) {
        $data = $this->csv->getData($file);

        unset($data[0]);
        $datas = array_values($data);

        return $datas;
    }
    private function getIdCategory(array $names)
    {
        foreach($names as $name){
            $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $name)->setPageSize(1);

            if ($collection->getSize()){
                $categoryId = $collection->getFirstItem()->getId();
                $categoryIds[] = $categoryId;
            }
        }
        return $categoryIds;
    }
    private function getAttributeSetId($name)
    {
        var_dump($name);
        $attributeSetCollection = $this->collectionSetFactory->create()
            ->addFieldToSelect('attribute_set_id')
            ->addFieldToFilter('attribute_set_name', $name)
            ->getFirstItem()
            ->toArray();

        return $attributeSetCollection['attribute_set_id'];
    }
    public static function getDependencies()
    {
        return [
            WebsiteConfigure::class
        ];
    }

    public function getAliases()
    {
        return [];
    }
}
