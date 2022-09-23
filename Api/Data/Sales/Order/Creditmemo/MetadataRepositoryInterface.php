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

namespace Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo;

/**
 * @api
 */
interface MetadataRepositoryInterface
{
    /**
     * @param int $id
     * @return \Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface $metadata
     * @return \Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface $metadata);

    /**
     * @param \Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface $metadata
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataInterface $metadata);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Taxjar\SalesTax\Api\Data\Sales\Order\Creditmemo\MetadataSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
