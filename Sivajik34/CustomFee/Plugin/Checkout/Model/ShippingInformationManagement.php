<?php
namespace Sivajik34\CustomFee\Plugin\Checkout\Model;


class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Sivajik34\CustomFee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Sivajik34\CustomFee\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Sivajik34\CustomFee\Helper\Data $dataHelper
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {

        $extensionAttributes = $addressInformation->getExtensionAttributes();

        if($extensionAttributes)
            $customFee = intval($extensionAttributes->getFee());
        else
            $customFee =  NULL;

        $quote = $this->quoteRepository->getActive($cartId);
        $totals = $quote->getTotals();
        $subtotal = $totals['subtotal']->getValue();
        if ($customFee) {

            // $fee = $this->dataHelper->getCustomFee();
            if($customFee == 1) {
                $fee = $subtotal/10;
            }     
            else if($customFee == 2) {
                $fee = $this->dataHelper->getCustomFee();
            }

            $quote->setFee($fee);

        } else {
            $quote->setFee(NULL);
        }
    }
}