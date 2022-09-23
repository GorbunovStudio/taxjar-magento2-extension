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
 * @copyright  Copyright (c) 2022 TaxJar. TaxJar is a trademark of TPS Unlimited, Inc. (http://www.taxjar.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace Taxjar\SalesTax\Model\ResourceModel\Sales\Order\Creditmemo;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface;

class Metadata extends AbstractDb
{
    const TABLE = 'tj_sales_creditmemo_metadata';

    protected function _construct()
    {
        $this->_init(self::TABLE, MetadataInterface::ID);
    }
}
