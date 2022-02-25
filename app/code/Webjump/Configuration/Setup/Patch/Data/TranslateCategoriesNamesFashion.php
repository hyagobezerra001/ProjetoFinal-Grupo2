<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Webjump\Configuration\App\FindCategories;

class TranslateCategoriesNamesFashion implements DataPatchInterface
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
                'meta' => 'WineClub | Promotions',
                'url' => 'promotion'
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
            ],
            /* SUBCATEGORIE OF ACESSORIES */
            [
                'original-name' => 'Brinco',
                'name' => 'Earring',
                'parent' => 3,
                'meta' => 'Magnolia | Acessories - Earring',
                'url' => 'earring'
            ],
            [
                'original-name' => 'Colar',
                'name' => 'Necklace',
                'parent' => 3,
                'meta' => 'Magnolia | Acessories - Necklace',
                'url' => 'necklace'
            ],
            [
                'original-name' => 'Pulseiras',
                'name' => 'Bracelets',
                'parent' => 3,
                'meta' => 'Magnolia | Acessories - bracelets',
                'url' => 'bracelets'
            ],
            /* SUBCATEGORIE OF PROMOTIONS */
            [
                'original-name' => 'Últimas Peças',
                'name' => 'Last Pieces',
                'parent' => 4,
                'meta' => 'Magnolia | Promotions - Last Pieces',
                'url' => 'lastpieces'
            ],
            [
                'original-name' => 'Pague 1 Leve 2',
                'name' => 'Buy 1 Take 2',
                'parent' => 2,
                'meta' => 'Magnolia | Promotions - Buy 1 Take 2',
                'url' => 'buy1take2'
            ],
        ];
    }

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
