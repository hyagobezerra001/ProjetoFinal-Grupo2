<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\SalesRule\Model\ResourceModel\Rule;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory;


class CreateCartRule implements DataPatchInterface
{

    private $combineFactory;
    private $websiteRepository;
    private $ruleFactory;
    private $rule;
    private $moduleDataSetup;

    public function __construct(
        CombineFactory $combineFactory,
        WebsiteRepositoryInterface $websiteRepository,
        RuleFactory $ruleFactory,
        Rule $rule,
        ModuleDataSetupInterface $moduleDataSetup
    )
    {
        $this->combineFactory = $combineFactory;
        $this->websiteRepository = $websiteRepository;
        $this->rule = $rule;
        $this->ruleFactory = $ruleFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $fashion = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE);
        $wine = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE);

        $condition = $this->combineFactory->create();
        $condition->setData('attribute', 'total_qty')
            ->setData('operator', '>=')
            ->setData('value', '5')
            ->setData('is_value_processed', 'false');

        $ruleCart = $this->ruleFactory->create(['setup' => $this->moduleDataSetup]);
        $ruleCart->setName('10% discount for both stores and all customer groups')
            ->setDescription('rule applied when cart has more than 5 items')
            ->setIsActive(1)
            ->setWebsiteIds([$fashion->getId(), $wine->getId()])
            ->setConditions($condition)
            ->setCustomerGroupIds(['0','1','2','3'])
            ->setSimpleAction('by_percent')
            ->setDiscountAmount(10);

        $this->rule->save($ruleCart);
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
