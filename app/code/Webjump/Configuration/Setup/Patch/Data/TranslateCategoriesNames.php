<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Webjump\Configuration\app\FindCategories;

class TranslateCategoriesNames implements DataPatchInterface
{
    private $moduleDataSetup;
    private $categoryRepository;
    private $storeRepository;

    private $category;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryRepository $categoryRepository,
        StoreRepositoryInterface $storeRepository,
        FindCategories $category

)
    {
        $this->category = $category;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;

    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $fashionEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $datas = $this->data();

        $root [] = $this->category->getId('Roupas');
        $root [] = $this->category->getId('Lingerie');
        $root [] = $this->category->getId('Calçados');
        //$root [] = $this->category->getId('Acessórios');
       //$root [] = $this->category->getId('Promoções');
        foreach ($datas as $data){
            $id =$this->category->getId($data['original-name']);

            $category = $this->categoryRepository->get($id,$fashionEN);
            $category-> setName($data['name'])
                     -> setMetaTitle($data['meta'])
                     -> setUrlKey($data['url'])
                     -> save();

        }
    }
    public function data()
    {
        return [
            [
                'original-name' => 'Roupas',
                'name' => 'Clothes',
                'parent' => null,
                'meta' => 'Magnolia | Clothes',
                'url' => 'clothes'
            ],
            [
                'original-name' => 'Calçados',
                'name' => 'Shoes',
                'parent' => null,
                'meta' => 'Magnolia | Shoes',
                'url' => 'shoes'
            ],
            [
                'original-name' => 'Acessórios',
                'name' => 'Accessories',
                'parent' => null,
                'meta' => 'Magnolia | Accessories',
                'url' => 'accessories'
            ],
            [
                'original-name' => 'Promoções',
                'name' => 'Promotions',
                'parent' => null,
                'meta' => 'Magnolia | Promotions',
                'url' => 'promotions'
            ],
            /* SUBCATEGORIES of Blouses */
            [
                'original-name' => 'Blusas',
                'name' => 'Blouse',
                'parent' => 0,
                'meta' => 'Magnolia | Clothes - Blouse',
                'url' => 'blouse'
            ],
            [
                'original-name' => 'Saias',
                'name' => 'Skirt',
                'parent' => 0,
                'meta' => 'Magnolia | Clothes - Skirt',
                'url' => 'skirt'
            ],
            [
                'original-name' => 'Vestidos',
                'name' => 'Dress',
                'parent' => 0,
                'meta' => 'Magnolia | Clothes - Dress',
                'url' => 'dress'
            ],
            /*SUBCATEGORIE OF Lingerie*/
            [
                'original-name' => 'Calcinha',
                'name' => 'Panty',
                'parent' => 1,
                'meta' => 'Magnolia | Lingerie - Panty',
                'url' => 'panty'
            ],
            [
                'original-name' => 'Sutiã',
                'name' => 'Bra',
                'parent' => 1,
                'meta' => 'Magnolia | Lingerie - Bra',
                'url' => 'bra'
            ],

            /* SUBCATEGORIE OF SHOES */
            [
                'original-name' => 'Botas',
                'name' => 'Boots',
                'parent' => 2,
                'meta' => 'Magnolia | Shoes - Boots',
                'url' => 'boots'
            ],
            [
                'original-name' => 'Sapatilha',
                'name' => 'Flats',
                'parent' => 2,
                'meta' => 'Magnolia | Shoes - Flats',
                'url' => 'flats'
            ],
            [
                'original-name' => 'Tênis',
                'name' => 'Sneakers',
                'parent' => 2,
                'meta' => 'Magnolia | Shoes - Sneakers',
                'url' => 'sneakers'
            ]
        ];
    }
        /* SUBCATEGORIE OF ACESSORIES */


    public static function getDependencies()
    {
        return [
            CreateCategories:: class,
            WebsiteConfigure::class
        ];
    }

    public function getAliases()
    {
        return [];
    }
}
