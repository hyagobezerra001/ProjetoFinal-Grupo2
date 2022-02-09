<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Api\ProductAttributeManagementInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\AttributeSetManagementInterface;

class CreateAttrProducts implements DataPatchInterface {

    const ATTRIBUTE_WINE1 = 'ml';
    const ATTRIBUTE_WINE2 = 'tipo_vinho';
    const ATTRIBUTE_WINE3 = 'importado'; //bool
    const SET_ATTRIBUTE_WINE = 'Wine';


    const SET_ATTRIBUTE_FASHION = 'Fashion';
    const ATTRIBUTE_FASHION1 = 'tecido';
    const ATTRIBUTE_FASHION2 = 'tamanho';
    const ATTRIBUTE_FASHION3 = 'moda_verao';


    private $moduleDataSetup;
    private $eavSetupFactory;
    private $attributeSetFactory;
    private $categorySetupFactory;
    private $product;
    private $attributeSetManagement;
    private $productAttributeManagement;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory,
        Product $product,
        AttributeSetManagementInterface $attributeSetManagement,
        ProductAttributeManagementInterface $productAttributeManagement

    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->product = $product;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->productAttributeManagement = $productAttributeManagement;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->setAtribute(static::SET_ATTRIBUTE_WINE);
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_WINE, 'Ml', static::ATTRIBUTE_WINE1, 'text');
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_WINE, 'tipo_vinho', static::ATTRIBUTE_WINE2, 'text');
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_WINE, 'importado', static::ATTRIBUTE_WINE3, 'text');

        $this->setAtribute(static::SET_ATTRIBUTE_FASHION);
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_FASHION, 'tecido', static::ATTRIBUTE_FASHION1, 'text');
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_FASHION, 'tamanho', static::ATTRIBUTE_FASHION2, 'text');
        $this->setAtributeProduct($this->moduleDataSetup, static::SET_ATTRIBUTE_FASHION, 'moda_verao', static::ATTRIBUTE_FASHION3, 'text');



        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public  function setAtribute(string $nameAttr){
        $defaultAttributeSetId = $this->product->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeSet->setAttributeSetName($nameAttr);
        $this->attributeSetManagement->create($attributeSet, $defaultAttributeSetId);
    }

    public function setAtributeProduct($moduleSetup, $attrName, $label, $code, $type){
        $eavSetup = $this->eavSetupFactory->create(['setup' => $moduleSetup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            $code,
            [
                'attribute_set' => $attrName,
                'user_defined' => true,
                'type' => $type,
                'label' => $label,
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'used_in_product_listing' => true,
                'system' => false,
                'visible_on_front' => true,
            ]
        );

        $attributeSetId = $eavSetup->getAttributeSetId(Product::ENTITY, $attrName);
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $sortOrder = 50;
        $this->productAttributeManagement
            ->assign($attributeSetId, $attributeGroupId, $code, $sortOrder);
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
