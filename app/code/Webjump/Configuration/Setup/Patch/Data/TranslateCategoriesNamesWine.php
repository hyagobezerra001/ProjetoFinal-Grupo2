<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Webjump\Configuration\App\FindCategories;

class TranslateCategoriesNamesWine implements DataPatchInterface
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
        $wineEN = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();
        $datas = $this->data();

        foreach ($datas as $data){
            $id =$this->category->getId($data['original-name'],$data['parent']);
            $category = $this->categoryRepository->get($id,$wineEN);
            $category-> setName($data['name'])
                -> setMetaTitle($data['meta'])
                -> setUrlKey($data['url'])
                -> save();
        }
    }
    public function data()
    {
        return [
            /* Root Categories */
            [
                'original-name' => 'Vinhos',
                'name' => 'Wines',
                'parent' => null,
                'meta' => 'WineClub | Wines',
                'url' => 'wines'
            ],
            [
                'original-name' => 'Espumantes',
                'name' => 'Sparkling Wines',
                'parent' => null,
                'meta' => 'WineClub | Sparkling Wines',
                'url' => 'sparklingwines'
            ],
            /*SUBCATEGORIES OF WINES*/
            [
                'original-name' => 'Branco',
                'name' => 'White',
                'parent' => 65,
                'meta' => 'WineClub | Country',
                'url' => 'country'
            ],

            [
                'original-name' => 'Tinto',
                'name' => 'Red',
                'parent' => 0,
                'meta' => 'WineClub | Grape',
                'url' => 'grape'
            ],

            /*SUBCATEGORIES OF PREMIUM */
            [
                'original-name' => 'Escolhidos dos Enólogos',
                'name' => 'Chosen by the Oenologists',
                'parent' => 2,
                'meta' => 'WineClub | Chosen by the Oenologists',
                'url' => 'oenologists'
            ],
            /*SUBCATEGORIES OF KITS*/
            [
                'original-name' => 'Harmonização',
                'name' => 'Harmonization',
                'parent' => 3,
                'meta' => 'WineClub | Harmonization',
                'url' => 'harmonization'
            ],

            [
                'original-name' => 'Variados',
                'name' => 'Sundry',
                'parent' => 3,
                'meta' => 'WineClub | Promotions',
                'url' => 'promotion'
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
