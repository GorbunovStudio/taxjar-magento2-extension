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
 * @copyright  Copyright (c) 2020 TaxJar. TaxJar is a trademark of TPS Unlimited, Inc. (http://www.taxjar.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace Taxjar\SalesTax\Plugin\Quote\Model;

use Magento\Quote\Api\CartManagementInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Taxjar\SalesTax\Api\Data\Sales\MetadataRepositoryInterface;
use Taxjar\SalesTax\Model\Sales\Order\Metadata;
use Taxjar\SalesTax\Model\Sales\Order\MetadataFactory;

/**
 * Class QuoteManagement
 *
 * Save additional TaxJar Sales Order extension data
 */
class QuoteManagement
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var MetadataRepositoryInterface
     */
    private $metadataRepository;

    /**
     * QuoteManagement constructor.
     *
     * @param MetadataFactory $metadataFactory
     * @param MetadataRepositoryInterface $metadataRepository
     */
    public function __construct(
        MetadataFactory $metadataFactory,
        MetadataRepositoryInterface $metadataRepository
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * Parse order extension data and persist metadata entity
     *
     * @param CartManagementInterface $subject
     * @param OrderInterface|null $order
     * @return OrderInterface|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function afterSubmit(
        CartManagementInterface $subject,
        ?OrderInterface $order
    ): ?OrderInterface {
        if (!$order instanceof OrderInterface) {
            return $order;
        }

        /** @var OrderExtensionInterface|null $orderExtensionAttributes */
        $orderExtensionAttributes = $order->getExtensionAttributes();

        if (!$orderExtensionAttributes) {
            return $order;
        }

        // Plugin is a shared instance; a fresh entity per order keeps a previously saved
        // row from being updated when several orders are placed in one process.
        /** @var Metadata $metadata */
        $metadata = $this->metadataFactory->create();

        if ($orderExtensionAttributes->getTjTaxCalculationStatus()) {
            $metadata->setOrderId($order->getEntityId());
            $metadata->setTaxCalculationStatus($orderExtensionAttributes->getTjTaxCalculationStatus());
        }
        if ($orderExtensionAttributes->getTjTaxCalculationMessage()) {
            $metadata->setOrderId($order->getEntityId());
            $metadata->setTaxCalculationMessage($orderExtensionAttributes->getTjTaxCalculationMessage());
        }
        if ($orderExtensionAttributes->getTjTaxPreventTaxSync() !== null) {
            $metadata->setOrderId($order->getEntityId());
            $metadata->setPreventTaxSync($orderExtensionAttributes->getTjTaxPreventTaxSync());
        }

        if ($metadata->getOrderId() !== null) {
            $this->metadataRepository->save($metadata);
        }

        return $order;
    }
}
