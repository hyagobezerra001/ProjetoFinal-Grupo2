<?php

declare(strict_types=1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Category;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreRepository;


class CreateCategories implements DataPatchInterface
{
    private $category;
    private $repository;
    private $moduleDataSetup;
    private $storeRepository;


    public function __construct (
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryFactory $category,
        CategoryRepository $repository,
        StoreRepository $storeRepository
    ){
        $this->storeRepository = $storeRepository;
        $this->category = $category;
        $this->repository = $repository;
        $this->moduleDataSetup = $moduleDataSetup;
    }
    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /* Creating the root category Fashion */
        $fashion = $this->category->create();
        $fashion->isObjectNew(true);
        $fashion->setName('Fashion')
            ->setParentId(Category::TREE_ROOT_ID)
            ->setIsActive(true)
            ->setPosition(2)
            ->setStoreId(2)
            ->setIncludeInMenu(true);
        $this->repository->save($fashion);

        /* Setting as root category */
        $store = $this->storeRepository->get('fashion');
        $group = $store->getGroup();
        $group->setRootCategoryId($fashion->getId());
        $group->save();

        /* Creating subcategories */
        $fashionNames = ['Roupas', 'Lingerie', 'Calçados', 'Acessórios', 'Promoções'];
        foreach ($fashionNames as $name) {
            $temp = $this->category->create();
            $temp->isObjectNew(true);
            $temp->setName($name)
                ->setParentId($fashion->getId())
                ->setIsActive(true)
                ->setPosition(2);
            $this->repository->save($temp);
        }

        /* Creating the root category Wine */
        $wine = $this->category->create();
        $wine->isObjectNew(true);
        $wine->setName('Wine')
            ->setParentId(Category::TREE_ROOT_ID)
            ->setIsActive(1)
            ->setPosition(3)
            ->setStoreId(3)
            ->setIncludeInMenu(true);

        /* Setting as root category */
        $this->repository->save($wine);
        $store = $this->storeRepository->get('wine');
        $group = $store->getGroup();
        $group->setRootCategoryId($wine->getId());
        $group->save();

        /* Creating subcategories */
        $wineNames = ['Vinhos', 'Espumantes', 'Premium', 'Kits'];
        foreach ($wineNames as $name) {
          $temp = $this->category->create();
          $temp->isObjectNew(true);
          $temp ->setName($name)
                ->setParentId($wine->getId())
                ->setIsActive(true)
                ->setPosition(2);
          $this->repository->save($temp);
      }
      $this->moduleDataSetup->getConnection()->startSetup();
    }
}
