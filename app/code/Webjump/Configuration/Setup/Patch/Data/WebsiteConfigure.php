<?php
namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\ResourceModel\Group as GroupResourceModel;
use Magento\Store\Model\ResourceModel\Store as StoreResourceModel;
use Magento\Store\Model\ResourceModel\Website as WebsiteResourceModel;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\StoreFactory;
use Magento\Store\Model\WebsiteFactory;

class WebsiteConfigure implements DataPatchInterface
{

    const WEBSITE_FASHION_CODE = 'fashion';
    const WEBSITE_FASHION_STORE_CODE = 'fashion_store';
    const WEBSITE_FASHION_STORE_CODE_EN = 'fashion_en';
    const WEBSITE_WINE_CODE = 'wine';
    const WEBSITE_WINE_STORE_CODE = 'wine_store';
    const WEBSITE_WINE_STORE_CODE_EN = 'wine_en';


    private $moduleDataSetup;
    private $websiteFactory;
    private $websiteResourceModel;
    private $groupFactory;
    private $groupResourceModel;
    private $storeFactory;
    private $storeResourceModel;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WebsiteFactory $websiteFactory,
        WebsiteResourceModel $websiteResourceModel,
        GroupFactory $groupFactory,
        GroupResourceModel $groupResourceModel,
        StoreFactory $storeFactory,
        StoreResourceModel $storeResourceModel
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->websiteFactory = $websiteFactory;
        $this->websiteResourceModel = $websiteResourceModel;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
        $this->storeFactory = $storeFactory;
        $this->storeResourceModel = $storeResourceModel;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $data = $this->getData();

        foreach ($data as $value) {
            $website = $this->websiteFactory->create();
            $this->websiteResourceModel->load($website, $value['website']['code'], 'code');

            if (!$website->getId()) {
                $website->setCode($value['website']['code'])
                    ->setName($value['website']['name'])
                    ->setSortOrder($value['website']['sort_order'])
                    ->setDefaultGroupId(0)
                    ->setIsDefault($value['website']['is_default']);

                $this->websiteResourceModel->save($website);


                $group = $this->groupFactory->create();
                $group->setWebsiteId($website->getId())
                    ->setName($value['group']['name'])
                    ->setRootCategoryId($value['group']['root_category_id'])
                    ->setDefaultStoreId($value['group']['default_store_id'])
                    ->setCode($value['group']['code']);

                $this->groupResourceModel->save($group);


                $this->websiteResourceModel->load($website, $value['website']['code'], 'code');
                $website->setDefaultGroupId($group->getId());
                $this->websiteResourceModel->save($website);


                $auxiliar = [];
                $incremento = 0;

                foreach ($value['store'] as $storeIterator) {
                    $store = $this->storeFactory->create();
                    $store->setCode($storeIterator['code'])
                        ->setWebsiteId($website->getId())
                        ->setGroupId($group->getId())
                        ->setName($storeIterator['name'])
                        ->setSortOrder($storeIterator['sort_order'])
                        ->setIsActive($storeIterator['is_active']);

                    $this->storeResourceModel->save($store);

                    $auxiliar[$incremento] = $store->getId();
                    $incremento++;
                }

                $this->groupResourceModel->load($group, $value['group']['code'], 'code');
                $group->setDefaultStoreId($auxiliar[0]);
                $this->groupResourceModel->save($group);
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    private function getData(): array
    {
        return [
            'fashion' => [
                'website' => [
                    'code' => self::WEBSITE_FASHION_CODE,
                    'name' => 'Fashion',
                    'sort_order' => '1',
                    'is_default' => '1'
                ],
                'group' => [
                    'name' => 'Fashion Store',
                    'root_category_id' => '2',
                    'code' => self::WEBSITE_FASHION_STORE_CODE,
                    'default_store_id' => 0
                ],
                'store' => [
                    'pt' => [
                        'code' => self::WEBSITE_FASHION_CODE,
                        'name' => 'Fashion pt_br',
                        'sort_order' => '1',
                        'is_active' => '1'
                    ],
                    'en' => [
                        'code' => self::WEBSITE_FASHION_STORE_CODE_EN,
                        'name' => 'Fashion en_us',
                        'sort_order' => '2',
                        'is_active' => '1'
                    ]
                ]
            ],
            'wine' => [
                'website' => [
                    'code' => self::WEBSITE_WINE_CODE,
                    'name' => 'wine',
                    'sort_order' => '2',
                    'is_default' => '0'
                ],
                'group' => [
                    'name' => 'Wine Store',
                    'root_category_id' => '2',
                    'code' => self::WEBSITE_WINE_STORE_CODE,
                    'default_store_id' => 0
                ],
                'store' => [
                    'pt' => [
                        'code' => self::WEBSITE_WINE_CODE,
                        'name' => 'Wine pt_br',
                        'sort_order' => '2',
                        'is_active' => '1'
                    ],
                    'en' => [
                        'code' => self::WEBSITE_WINE_STORE_CODE_EN,
                        'name' => 'Wine en_us',
                        'sort_order' => '2',
                        'is_active' => '1'
                    ]
                ]
            ]
        ];
    }

    public function getAliases():array
    {
        return [];
    }

    public static function getDependencies():array
    {
        return [];
    }
}
