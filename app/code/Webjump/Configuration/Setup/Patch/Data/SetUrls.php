<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory;

class SetUrls implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $writer,
        CollectionFactory $collectionFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->writer = $writer;
        $this->collectionFactory = $collectionFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->writer->save('web/unsecure/base_url', 'http://projetofinal.develop.com.br/', 'default', 0);
        $this->writer->save('web/unsecure/base_link_url', 'http://admin.develop.com.br/', 'default', 0);

        $wine = $this->collectionFactory->create()->addFieldToFilter('name', 'Wine')->setPageSize(1);

        if ($wine->getSize()){
            $websiteWine = $wine->getFirstItem()->getId();
        }

        $this->writer->save('web/unsecure/base_url', 'http://wine.develop.com.br/', 'websites', $websiteWine);
        $this->writer->save('web/unsecure/base_link_url', 'http://wine.develop.com.br/', 'websites', $websiteWine);


        $fashion = $this->collectionFactory->create()->addFieldToFilter('name', 'Fashion')->setPageSize(1);

        if ($fashion->getSize()){
            $websiteFashionId = $fashion->getFirstItem()->getId();
        }

        $this->writer->save('web/unsecure/base_url', 'http://fashion.develop.com.br/', 'websites', $websiteFashionId);
        $this->writer->save('web/unsecure/base_link_url', 'http://fashion.develop.com.br/', 'websites', $websiteFashionId);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies():array
    {
        return [];
    }

    public function getAliases():array
    {
        return [];
    }

}
