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

    public function createRootCategory(String $name, int $id, string $storeRepository)
    {
        $categoryName = $this->category->create();
        $categoryName->isObjectNew(true);
        $categoryName->setName ($name)
                    ->setParentId(Category::TREE_ROOT_ID)
                    ->setIsActive(true)
                    ->setPosition($id)
                    ->setStoreId($id)
                    ->setIncludeInMenu(true);
        $this->repository->save($categoryName);
        $store = $this->storeRepository->get($storeRepository);
        $group = $store->getGroup();
        $group->setRootCategoryId($categoryName->getId());
        $group->save();
        return $categoryName;
    }

    public function createCategories(string $name, $idRoot)
    {
        $temp = $this->category->create();
        $temp->isObjectNew(true);
        $temp->setName($name)
            ->setParentId($idRoot)
            ->setIsActive(true)
            ->setPosition(2);
        $this->repository->save($temp);
        return $temp;

    }
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $fashion = $this->createRootCategory('Fashion', 2, 'fashion');
        $wine = $this->createRootCategory('Wine', 3, 'wine');

        $roupas = $this->createCategories('Roupas', $fashion->getID());
        $lingerie = $this->createCategories('Lingerie', $fashion->getID());
        $calcados = $this->createCategories('Calçados', $fashion->getID());
        $acessorios = $this->createCategories('Acessórios', $fashion->getID());
        $promocoes = $this->createCategories('Promoções', $fashion->getID());

        $this->createCategories('Blusas', $roupas->getID());
        $this->createCategories('Saias', $roupas->getID());
        $this->createCategories('Vestidos', $roupas->getID());

        $this->createCategories('BabyDoll', $lingerie->getID());
        $this->createCategories('Calcinha', $lingerie->getID());
        $this->createCategories('Sutiã', $lingerie->getID());

        $this->createCategories('Botas', $calcados->getID());
        $this->createCategories('Sapatilha', $calcados->getID());
        $this->createCategories('Tênis', $calcados->getID());

        $this->createCategories('Brinco', $acessorios->getID());
        $this->createCategories('Colar', $acessorios->getID());
        $this->createCategories('Pulseiras', $acessorios->getID());

        $this->createCategories('Últimas peças', $promocoes->getID());
        $this->createCategories('Pague 1 Leve 2', $promocoes->getID());

        $vinhos = $this->createCategories('Vinhos', $wine->getID());
        $espumantes = $this->createCategories('Espumantes', $wine->getID());
        $premium = $this->createCategories('Premium', $wine->getID());
        $kits = $this->createCategories('Kits', $wine->getID());

        $this->createCategories('País', $vinhos->getID());
        $this->createCategories('Tipo', $vinhos->getID());
        $this->createCategories('Uva', $vinhos->getID());

        $this->createCategories('País', $espumantes->getID());
        $this->createCategories('Tipo', $espumantes->getID());
        $this->createCategories('Uva', $espumantes->getID());

        $this->createCategories('Escolhidos dos Enólogos', $premium->getID());

        $this->createCategories('Harmonização', $kits->getID());
        $this->createCategories('Promoções', $kits->getID());

        $this->moduleDataSetup->getConnection()->startSetup();
    }
    public static function getDependencies()
    {
        return [];
    }
    public function getAliases()
    {
        return [];
    }
}
