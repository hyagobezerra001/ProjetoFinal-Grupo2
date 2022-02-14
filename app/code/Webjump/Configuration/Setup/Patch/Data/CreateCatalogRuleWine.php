<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\CatalogRule\Api\Data\RuleInterface;
use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\CatalogRule\Api\Data\RuleInterfaceFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Customer\Model\Group;


class CreateCatalogRuleWine implements DataPatchInterface
{

    private $ruleInterfaceFactory;
    private $rule;
    private $moduleDataSetup;
    private $catalogRuleRepository;
    private $websiteRepository;


    public function __construct(
        RuleInterfaceFactory $ruleInterfaceFactory,
        RuleInterface $rule,
        ModuleDataSetupInterface $moduleDataSetup,
        CatalogRuleRepository $catalogRuleRepository,
        WebsiteRepositoryInterface $websiteRepository
    )
    {
        $this->ruleInterfaceFactory = $ruleInterfaceFactory;
        $this->rule = $rule;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->catalogRuleRepository = $catalogRuleRepository;
        $this->websiteRepository = $websiteRepository;

    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $wine = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE);

        $objRule = $this->ruleInterfaceFactory->create(['setup' => $this->moduleDataSetup]);
        $objRule->setName('10% discount for users not logged in by category')
            ->setDescription('discount applied on the fashion store website')
            ->setIsActive(1)
            ->setWebsiteIds($wine->getId())
            ->setCustomerGroupIds(Group::NOT_LOGGED_IN_ID)
            ->setSimpleAction('by_percent')
            ->setDiscountAmount(10)
            ->setStopRulesProcessing(0);

        $this->catalogRuleRepository->save($objRule);

        $this->moduleDataSetup->getConnection()->endSetup();
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
