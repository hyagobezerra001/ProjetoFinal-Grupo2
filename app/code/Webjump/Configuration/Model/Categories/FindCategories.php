<?php

namespace Webjump\Configuration\Model\Categories;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class FindCategories
{
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getCategoryId($nome)
    {
        $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $nome)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        return $categoryId;
    }
}
