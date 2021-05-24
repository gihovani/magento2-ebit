<?php
declare(strict_types=1);

namespace Gg2\Ebit\Block\Html;

use Gg2\Ebit\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Ebit extends Template
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var bool
     */
    private $showLightbox = false;

    public function __construct(
        Context $context,
        Session $session,
        Data $helper
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->helper = $helper;
    }

    /**
     * @return bool
     */
    public function isEnableBanner(): bool
    {
        return (bool)$this->helper->getConfigValue(Data::CONFIG_ENABLE_BANNER);
    }

    /**
     * @return bool
     */
    public function isEnableSelo(): bool
    {
        return (bool)$this->helper->getConfigValue(Data::CONFIG_ENABLE_SELO);
    }

    /**
     * @return bool
     */
    public function showLightbox(): bool
    {
        return $this->showLightbox;
    }

    /**
     * @return string|null
     */
    public function getEbitUriParams(): ?string
    {
        $lastOrder = $this->session->getLastRealOrder();
        if (!$lastOrder) {
            return null;
        }
        $this->showLightbox = (bool)$this->helper->getConfigValue(Data::CONFIG_LIGHTBOX);

        $shippingAddress = $lastOrder->getShippingAddress();

        $value = 'email=' . $lastOrder->getCustomerEmail();
        $value .= '&gender=' . $lastOrder->getCustomerGender();
        $value .= '&birthDay=' . $lastOrder->getCustomerDob();
        $value .= '&zipCode=' . (($shippingAddress) ? $shippingAddress->getPostCode() : '');
        $value .= '&deliveryTax=' . $lastOrder->getShippingInclTax();
        $value .= '&totalSpent=' . $lastOrder->getGrandTotal();
        $value .= '&value=' . $lastOrder->getGrandTotal();

        $qty = [];
        $name = [];
        $sku = [];
        if ($items = $lastOrder->getItemsCollection()) {
            foreach ($items as $i => $item) {
                $qty[$i] = $item->getQtyOrdered();
                $name[$i] = $item->getName();
                $sku[$i] = $item->getSku();
            }
        }

        $value .= '&quantity=' . implode('|', $qty);
        $value .= '&productName=' . implode('|', $name);
        $value .= '&sku=' . implode('|', $sku);
        $value .= '&transactionId=' . $lastOrder->getId();
        $value .= '&buscapeId=' . $this->getBuscapeId();
        $value .= '&storeId=' . $this->getStoreId();
        $value .= '&mktSaleID=0';
        $value .= '&plataform=0';

        return trim($value);
    }

    /**
     * @return int
     */
    public function getBuscapeId(): int
    {
        return (int)$this->helper->getConfigValue(Data::CONFIG_BUSCAPE_ID);
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->helper->getConfigValue(Data::CONFIG_STORE_ID);
    }
}
