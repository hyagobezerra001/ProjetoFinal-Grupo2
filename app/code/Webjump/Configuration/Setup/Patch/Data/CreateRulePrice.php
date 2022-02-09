<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\CatalogRule\Api\Data\RuleInterface;
use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\CatalogRule\Api\Data\RuleInterfaceFactory;


class CreateRulePrice implements DataPatchInterface
{

    public function __construct(
        RuleInterfaceFactory $ruleInterfaceFactory,
        RuleInterface $rule,
        ModuleDataSetupInterface $moduleDataSetup,
        CatalogRuleRepository $catalogRuleRepository
    )
    {

    }

    public function apply()
    {

    }

    public static function getDependencies():array
    {
        return [WebsiteConfigure::class,
                CreateCategories::class];
    }

    public function getAliases():array
    {
        return [];
    }
}
