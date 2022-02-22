<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\SalesRule\Model\ResourceModel\Rule;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory;
use Magento\SalesRule\Model\Rule\Condition\AddressFactory;
use Magento\SalesRule\Model\Rule\Condition\Address;



class CreateCartRule implements DataPatchInterface
{

    private $combineFactory;
    private $websiteRepository;
    private $ruleFactory;
    private $rule;
    private $moduleDataSetup;
    private $addressFactory;


    public function __construct(
        CombineFactory $combineFactory,
        WebsiteRepositoryInterface $websiteRepository,
        RuleFactory $ruleFactory,
        Rule $rule,
        ModuleDataSetupInterface $moduleDataSetup,
        AddressFactory $addressFactory
    )
    {
        $this->combineFactory = $combineFactory;
        $this->websiteRepository = $websiteRepository;
        $this->rule = $rule;
        $this->ruleFactory = $ruleFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->addressFactory = $addressFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $fashion = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE);
        $wine = $this->websiteRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE);

        $condition = $this->combineFactory->create();
        $conditionAddress = $this->addressFactory->create();

        $conditionAddress->settype(Address::class)
            ->setData('attribute', 'total_qty')
            ->setData('operator', '>=')
            ->setData('value', '5')
            ->setData("is_value_processed", "false");

        $condition ->setData('attribute', null)
            ->setData('operator', null)
            ->setData('value', '1')
            ->setData('is_value_processed', null)
            ->setData('aggregator', 'all')
            ->setConditions([$conditionAddress]);

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
