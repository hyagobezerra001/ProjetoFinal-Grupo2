<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class CreateAttrCategory implements DataPatchInterface
{
    const CATEGORY_CODE_WINE = 'Wine001';

    const CATEGORY_CODE_FASHION = 'Fashion001';

    private $moduleDataSetup;
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }


    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavConfig = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavConfig->addAttribute(Category::ENTITY, self::CATEGORY_CODE_WINE, [
            'type' => 'varchar',
            'label' => 'Produtos (wine)',
            'input' => 'text',
            'source' => '',
            'user_defined' => true,
            'visible' => true,
            'default' => '',
            'required' => false,
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'group' => 'General'
        ]);


        $eavConfig->addAttribute(Category::ENTITY, self::CATEGORY_CODE_FASHION, [
            'type' => 'varchar',
            'label' => 'Produtos (fashion)',
            'input' => 'text',
            'source' => '',
            'user_defined' => true,
            'visible' => true,
            'default' => '',
            'required' => false,
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'group' => 'General'
        ]);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}

