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
        $calcados = $this->createCategories('Cal??ados', $fashion->getID());
        $acessorios = $this->createCategories('Acess??rios', $fashion->getID());
        $promocoes = $this->createCategories('Promo????es', $fashion->getID());

        $this->createCategories('Blusas', $roupas->getID());
        $this->createCategories('Saias', $roupas->getID());
        $this->createCategories('Vestidos', $roupas->getID());

        $this->createCategories('BabyDoll', $lingerie->getID());
        $this->createCategories('Calcinha', $lingerie->getID());
        $this->createCategories('Suti??', $lingerie->getID());

        $this->createCategories('Botas', $calcados->getID());
        $this->createCategories('Sapatilha', $calcados->getID());
        $this->createCategories('T??nis', $calcados->getID());

        $this->createCategories('Brinco', $acessorios->getID());
        $this->createCategories('Colar', $acessorios->getID());
        $this->createCategories('Pulseiras', $acessorios->getID());

        $this->createCategories('??ltimas pe??as', $promocoes->getID());
        $this->createCategories('Pague 1 Leve 2', $promocoes->getID());

        $vinhos = $this->createCategories('Vinhos', $wine->getID());
        $espumantes = $this->createCategories('Espumantes', $wine->getID());
        $premium = $this->createCategories('Premium', $wine->getID());
        $kits = $this->createCategories('Kits', $wine->getID());

        $this->createCategories('Branco', $vinhos->getID());
        $this->createCategories('Rose', $vinhos->getID());
        $this->createCategories('Tinto', $vinhos->getID());

        $this->createCategories('Charmat', $espumantes->getID());
        $this->createCategories('Champenoise', $espumantes->getID());

        $this->createCategories('Escolhidos dos En??logos', $premium->getID());

        $this->createCategories('Harmoniza????o', $kits->getID());
        $this->createCategories('Variados', $kits->getID());

        $this->moduleDataSetup->getConnection()->startSetup();
    }
    public static function getDependencies():array
    {
        return [WebsiteConfigure::class];
    }
    public function getAliases():array
    {
        return [];
    }
}
