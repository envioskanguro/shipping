<?php

/**
 * Envios Kanguro Shipping
 *
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @author Javier Telio Z <jtelio118@gmail.com>
 */

namespace Envioskanguro\Shipping\Observer;


use Envioskanguro\Shipping\Plugin\Logger\Logger;
use Envioskanguro\Shipping\WebService\QuotationService;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SaveSalesOrder implements ObserverInterface
{
    /** 
     * Prefix Shipping Code
     */
    const PREFIX_SHIPPING_CODE = 'envioskanguro';

    /** 
     * @var Logger $logger
     */
    protected $logger;

    /** 
     * @var ScopeConfig
     */
    protected $scopeConfig;

    /** 
     * @var QuotationService $quotationService
     */
    protected $quotationService;

    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        QuotationService $quotationService
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->quotationService = $quotationService;
    }

    /**
     * Execute Observer
     * 
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $shippingMethod = $order->getShippingMethod();

        if (strstr($shippingMethod, self::PREFIX_SHIPPING_CODE)) {

            $this->logger->debug('Update Quote');
            $this->quotationService->setSelectedQuotation($order);
        }

        return $this;
    }
}
