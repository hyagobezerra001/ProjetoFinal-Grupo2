<?php

declare(strict_types=1);

namespace Webjump\Configuration\Test\Unit\Model\Categories;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Data\CollectionFactory;
use PHPUnit\Framework\TestCase;
use Webjump\Configuration\Model\Categories\FindCategories;

class FindCategoriesTest extends TestCase
{
    public function setUp() : void
    {

        $this->collectionFactory = $this->getMockBuilder('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

       $this->findCategories = new FindCategories($this->collectionFactory);

    }

    public function testFindCategoryNameReturnId()
    {
        $name = 'Roupas';
        $this->collectionFactory->expects($this->once())
            ->method ('create')
            ->willReturnSelf();

        $this->collectionFactory->expects($this->once())
            ->method('addAttributeFilter')
            ->with('name',$name)
            ->willReturnSelf();

        $this->collectionFactory->expects($this->once())
            ->method('setPageSize')
            ->with(1)
            ->willReturnSelf();



    }
}
