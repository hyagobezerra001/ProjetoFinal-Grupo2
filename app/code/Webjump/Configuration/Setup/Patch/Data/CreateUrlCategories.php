<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CreateUrlCategories implements DataPatchInterface
{
    private $moduleDataSetup;
    private $categoryRepository;
    private $storeRepository;
    private $collectionFactory;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryRepository $categoryRepository,
        StoreRepositoryInterface $storeRepository,
        CollectionFactory $collectionFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $fashion = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE)->getId();
        $wine = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE)->getId();

        $urlFashion = $this->categoriesFashion();
        $urlWine = $this->categoriesWine();

        foreach ($urlFashion as $url){
            $id = $this->getCategoryId($url['name']);

            $category = $this->categoryRepository->get($id,$fashion);
            $category->setUrlKey($url['url'])->save();
        }

        foreach ($urlWine as $url){
            $id = $this->getCategoryId($url['name']);

            $category = $this->categoryRepository->get($id,$wine);
            $category->setUrlKey($url['url'])->save();

        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
    public function categoriesFashion():array
    {
        return [
            [
            'name' => 'Roupas',
            'url' => 'roupasmoda'
            ],
            [
                'name' => 'Lingerie',
                'url' => 'lingeriemoda'
            ],
            [
                'name' => 'Calçados',
                'url' => 'calcadosmoda'
            ],
            [
                'name' => 'Acessórios',
                'url' => 'acessoriosmoda'
            ],
            [
                'name' => 'Promoções',
                'url' => 'promocoesmoda'
            ],
        ];
    }

    public function categoriesWine():array
    {
        return [
            [
                'name' => 'Vinhos',
                'url' => 'vinhowine'
            ],
            [
                'name' => 'Espumantes',
                'url' => 'espumanteswine'
            ],
            [
                'name' => 'Premium',
                'url' => 'premiumwine'
            ],
            [
                'name' => 'Kits',
                'url' => 'kitswine'
            ],
        ];
    }

    public static function getDependencies()
    {
        return [
            CreateCategories:: class,
            WebsiteConfigure::class,
        ];
    }

    public function getAliases()
    {
        return [];
    }

    private function getCategoryId($nome)
    {
        $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $nome)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        return $categoryId;
    }
}
