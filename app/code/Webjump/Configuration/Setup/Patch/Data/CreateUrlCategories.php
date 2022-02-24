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
        $categoryData = $this->dataFashion();

        foreach ($categoryData as $data){
            $id = $this->getCategoryId($data['name']);

            $category = $this->categoryRepository->get($id,$fashion);
            $category->setUrlKey($data['url'])->save();
        }

    }
    public function dataFashion()
    {
        return [
            [
                'name' => 'roupas',
                'url' => 'roupasmoda'
            ],
            [
                'name' => 'lingerie',
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
                'url' => 'Promocoesmoda'
            ],
        ];
    }

    public function dataAutomotive()
    {
        return [
            [
                'original-name' => 'Vinhos',
                'url' => 'volt-3-en'
            ],
            [
                'original-name' => 'Espumantes',
                'url' => 'volt-sx-en'
            ],
            [
                'original-name' => 'Premium',
                'url' => 'roadmaster-en'
            ],
            [
                'original-name' => 'Kits',
                'url' => 'acessories-en'
            ]
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
