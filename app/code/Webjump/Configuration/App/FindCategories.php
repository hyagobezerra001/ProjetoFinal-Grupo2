<?php

namespace Webjump\Configuration\App;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\Search\SearchCriteriaFactory;
use Magento\Framework\Api\FilterBuilder;

class FindCategories
{
    private $filter;
    private $categoryList;
    private $search;
    private $groupFactory;

    public function __construct(
        FilterBuilder $filter,
        SearchCriteriaFactory $search,
        CategoryListInterface $categoryList,
        FilterGroup $groupFactory
    )
    {
        $this->filter = $filter;
        $this->search = $search;
        $this->categoryList = $categoryList;
        $this->groupFactory = $groupFactory;
    }

    public function getId(string $name)
    {
        \Magento\Framework\App\ObjectManager::getInstance()
    ->get(\Psr\Log\LoggerInterface::class)->debug($name);

        $nameFilter = $this->filter->create()
            ->setField(CategoryInterface::KEY_NAME)
            ->setValue($name)
            ->setConditionType('eq');

        $groupFactory= $this->groupFactory->setFilters([$nameFilter]);

        $searchCriteria = $this->search->create()->setFilterGroups([$groupFactory]);

        $list = $this->categoryList->getList($searchCriteria)->getItems();

        foreach ($list as $category){
            $id = $category->getId();
        }
        return $id;
    }
}
