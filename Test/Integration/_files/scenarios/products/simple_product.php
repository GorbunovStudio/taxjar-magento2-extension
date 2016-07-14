<?php
/**
 * Taxjar_SalesTax
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Taxjar
 * @package    Taxjar_SalesTax
 * @copyright  Copyright (c) 2016 TaxJar. TaxJar is a trademark of TPS Unlimited, Inc. (http://www.taxjar.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\Config;
use Taxjar\SalesTax\Test\Integration\Model\Tax\Sales\Total\Quote\SetupUtil;

$taxCalculationData['simple_product'] = [
    'config_data' => [
        SetupUtil::CONFIG_OVERRIDES => array_merge($taxjarCredentials, [
            'shipping/origin/street_line1' => '600 Montgomery St',
            'shipping/origin/city' => 'San Francisco',
            'shipping/origin/region_id' => SetupUtil::REGION_CA,
            'shipping/origin/country_id' => 'US',
            'shipping/origin/postcode' => '94111'
        ])
    ],
    'quote_data' => [
        'billing_address' => [
            'firstname' => 'Jake',
            'lastname' => 'Johnson',
            'street' => '123 Westcreek Pkwy',
            'city' => 'Westlake Village',
            'region_id' => SetupUtil::REGION_CA,
            'postcode' => '91362',
            'country_id' => SetupUtil::COUNTRY_US,
            'telephone' => '011-899-9881'
        ],
        'shipping_address' => [
            'firstname' => 'Jake',
            'lastname' => 'Johnson',
            'street' => '123 Westcreek Pkwy',
            'city' => 'Westlake Village',
            'region_id' => SetupUtil::REGION_CA,
            'postcode' => '91362',
            'country_id' => SetupUtil::COUNTRY_US,
            'telephone' => '011-899-9881'
        ],
        'items' => [
            [
                'type' => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                'sku' => 'taxjar-tshirt',
                'price' => 29.99,
                'qty' => 1
            ]
        ],
    ],
    'expected_results' => [
        'address_data' => [
            'tax_amount' => 2.70,
            'subtotal' => 29.99,
            'subtotal_incl_tax' => 29.99 + 2.70,
            'grand_total' => 29.99 + 2.70
        ],
        'items_data' => [
            'taxjar-tshirt' => [
                'tax_amount' => 2.70,
                'tax_percent' => 9.0,
                'price' => 29.99,
                'price_incl_tax' => 29.99 + 2.70,
                'row_total' => 29.99,
                'row_total_incl_tax' => 29.99 + 2.70,
                'applied_taxes' => [
                    [
                        'id' => 'state - county - special',
                        'item_id' => null,
                        'associated_item_id' => null,
                        'item_type' => 'product',
                        'amount' => 2.70,
                        'base_amount' => 2.70,
                        'percent' => 9.0,
                        'rates' => [
                            [
                                'code' => 'state',
                                'title' => 'State Tax',
                                'percent' => 6.5
                            ],
                            [
                                'code' => 'county',
                                'title' => 'County Tax',
                                'percent' => 1
                            ],
                            [
                                'code' => 'special',
                                'title' => 'Special Tax',
                                'percent' => 1.5
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ],
];

$taxCalculationData['simple_product_multiple'] = [
    'config_data' => [
        SetupUtil::CONFIG_OVERRIDES => array_merge($taxjarCredentials, [
            'shipping/origin/street_line1' => '600 Montgomery St',
            'shipping/origin/city' => 'San Francisco',
            'shipping/origin/region_id' => SetupUtil::REGION_CA,
            'shipping/origin/country_id' => 'US',
            'shipping/origin/postcode' => '94111'
        ])
    ],
    'quote_data' => [
        'billing_address' => [
            'firstname' => 'Jake',
            'lastname' => 'Johnson',
            'street' => '123 Westcreek Pkwy',
            'city' => 'Westlake Village',
            'region_id' => SetupUtil::REGION_CA,
            'postcode' => '91362',
            'country_id' => SetupUtil::COUNTRY_US,
            'telephone' => '011-899-9881'
        ],
        'shipping_address' => [
            'firstname' => 'Jake',
            'lastname' => 'Johnson',
            'street' => '123 Westcreek Pkwy',
            'city' => 'Westlake Village',
            'region_id' => SetupUtil::REGION_CA,
            'postcode' => '91362',
            'country_id' => SetupUtil::COUNTRY_US,
            'telephone' => '011-899-9881'
        ],
        'items' => [
            [
                'type' => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                'sku' => 'taxjar-tshirt',
                'price' => 29.99,
                'qty' => 1
            ],
            [
                'type' => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                'sku' => 'taxjar-trucker-hat',
                'price' => 9.99,
                'qty' => 1
            ],
            [
                'type' => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                'sku' => 'taxjar-hoodie',
                'price' => 59.99,
                'qty' => 1
            ]
        ],
    ],
    'expected_results' => [
        'address_data' => [
            'tax_amount' => 9.00,
            'subtotal' => 99.97,
            'subtotal_incl_tax' => 99.97 + 9.00,
            'grand_total' => 99.97 + 9.00
        ],
        'items_data' => [
            'taxjar-tshirt' => [
                'tax_amount' => 2.70,
                'tax_percent' => 9.0,
                'price' => 29.99,
                'price_incl_tax' => 29.99 + 2.70,
                'row_total' => 29.99,
                'row_total_incl_tax' => 29.99 + 2.70,
                'applied_taxes' => [
                    [
                        'id' => 'state - county - special',
                        'item_id' => null,
                        'associated_item_id' => null,
                        'item_type' => 'product',
                        'amount' => 2.70,
                        'base_amount' => 2.70,
                        'percent' => 9.0,
                        'rates' => [
                            [
                                'code' => 'state',
                                'title' => 'State Tax',
                                'percent' => 6.5
                            ],
                            [
                                'code' => 'county',
                                'title' => 'County Tax',
                                'percent' => 1
                            ],
                            [
                                'code' => 'special',
                                'title' => 'Special Tax',
                                'percent' => 1.5
                            ]
                        ]
                    ]
                ]
            ],
            'taxjar-trucker-hat' => [
                'tax_amount' => 0.90,
                'tax_percent' => 9.0,
                'price' => 9.99,
                'price_incl_tax' => 9.99 + 0.90,
                'row_total' => 9.99,
                'row_total_incl_tax' => 9.99 + 0.90,
                'applied_taxes' => [
                    [
                        'id' => 'state - county - special',
                        'item_id' => null,
                        'associated_item_id' => null,
                        'item_type' => 'product',
                        'amount' => 0.90,
                        'base_amount' => 0.90,
                        'percent' => 9.0,
                        'rates' => [
                            [
                                'code' => 'state',
                                'title' => 'State Tax',
                                'percent' => 6.5
                            ],
                            [
                                'code' => 'county',
                                'title' => 'County Tax',
                                'percent' => 1
                            ],
                            [
                                'code' => 'special',
                                'title' => 'Special Tax',
                                'percent' => 1.5
                            ]
                        ]
                    ]
                ]
            ],
            'taxjar-hoodie' => [
                'tax_amount' => 5.40,
                'tax_percent' => 9.0,
                'price' => 59.99,
                'price_incl_tax' => 59.99 + 5.40,
                'row_total' => 59.99,
                'row_total_incl_tax' => 59.99 + 5.40,
                'applied_taxes' => [
                    [
                        'id' => 'state - county - special',
                        'item_id' => null,
                        'associated_item_id' => null,
                        'item_type' => 'product',
                        'amount' => 5.40,
                        'base_amount' => 5.40,
                        'percent' => 9.0,
                        'rates' => [
                            [
                                'code' => 'state',
                                'title' => 'State Tax',
                                'percent' => 6.5
                            ],
                            [
                                'code' => 'county',
                                'title' => 'County Tax',
                                'percent' => 1
                            ],
                            [
                                'code' => 'special',
                                'title' => 'Special Tax',
                                'percent' => 1.5
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ],
];
