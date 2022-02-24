<?php

declare(strict_types=1);

namespace Webjump\Configuration\Setup\Patch\Data;


use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class AddColorOption implements DataPatchInterface
{
    /**
     * @var AttributeOptionInterfaceFactory
     */
    private $optionInterfaceFactory;
    /**
     * @var AttributeOptionManagementInterface
     */
    private $optionManagement;
    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    private $optionLabelInterfaceFactory;
    private $storeRepository;

    public function __construct(
        AttributeOptionInterfaceFactory $optionInterfaceFactory,
        AttributeOptionManagementInterface $optionManagement,
        AttributeOptionLabelInterfaceFactory $optionLabelInterfaceFactory,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->optionInterfaceFactory = $optionInterfaceFactory;
        $this->optionManagement = $optionManagement;
        $this->optionLabelInterfaceFactory = $optionLabelInterfaceFactory;
        $this->storeRepository = $storeRepository;
    }


    public function apply()
    {
        $options = $this->getOptions();
        foreach ($options as $op) {
            $option = $this->optionInterfaceFactory->create();
            $option->setLabel($op['label'])
                ->setValue($op['value']);
            $option->setIsDefault($op['is_default']);
            $labels = [];
            foreach ($op['store_labels'] as $la) {
                $label = $this->optionLabelInterfaceFactory->create();
                $label->setStoreId($la['store_id'])
                    ->setLabel($la['label']);
                $labels[] = $label;
            }
            $option->setStoreLabels($labels);
            $this->optionManagement->add(
                4,
                'color',
                $option
            );
        }
    }

    public function getOptions()
    {
        $fashionId = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_CODE)->getId();
        $fashionIdEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_FASHION_STORE_CODE_EN)->getId();
        $wineId =  $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_CODE)->getId();
        $wineIdEn = $this->storeRepository->get(WebsiteConfigure::WEBSITE_WINE_STORE_CODE_EN)->getId();
        return [
            [
                'label' => 'transparent',
                'value' => 'transparent',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Transparent',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Transparent',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Transparente',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Transparente',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'pink',
                'value' => 'pink',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Pink',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Pink',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Rosa',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Rosa',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'black',
                'value' => 'black',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Black',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Black',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Preto',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Preto',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'purple',
                'value' => 'purple',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Purple',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Purple',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Roxo',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Roxo',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'red',
                'value' => 'red',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Red',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Red',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Vermelho',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Vermelho',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'green',
                'value' => 'green',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Green',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Green',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Verde',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Verde',
                        'store_id' => $wineId
                    ]
                ]
            ],
            [
                'label' => 'yellow',
                'value' => 'yellow',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Yellow',
                        'store_id' => $fashionIdEn
                    ],
                    [
                        'label' => 'Yellow',
                        'store_id' => $wineIdEn
                    ],
                    [
                        'label' => 'Amarelo',
                        'store_id' => $fashionId
                    ],
                    [
                        'label' => 'Amarelo',
                        'store_id' => $wineId
                    ]
                ]
            ],

        ];

    }
    public static function getDependencies()
    {
        return [
            WebsiteConfigure::class
        ];
    }

    public function getAliases()
    {
        return [];
    }
}
