<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\Customer\Billing;
use PagarMe\Sdk\Customer\Item;
use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\Transaction;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;
use PagarMe\Sdk\Customer\Customer;

class TransactionCreate implements RequestInterface
{
    use \PagarMe\Sdk\SplitRuleSerializer;

    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    protected $transaction;

    /**
     * @param \PagarMe\Sdk\Transaction\Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $customer = $this->transaction->getCustomer();

        $transactionData = [
            'amount'         => $this->transaction->getAmount(),
            'payment_method' => $this->transaction->getPaymentMethod(),
            'postback_url'   => $this->transaction->getPostbackUrl(),
            'metadata' => $this->transaction->getMetadata(),
            'reference_key' => $this->transaction->getReferenceKey()
        ];

        $customerData = [
            'name'            => $customer->getName(),
            'document_number' => $customer->getDocumentNumber(),
            'document_type'   => $customer->getDocumentType(),
            'email'           => $customer->getEmail(),
            'sex'             => $customer->getGender(),
            'born_at'         => $customer->getBornAt()
        ];

        if (!is_null($customer->getId())) {
            $customerData['id'] = $customer->getId();
        }

        if (!is_null($customer->getType())) {
            $customerData['type'] = $customer->getType();
        }

        if (!is_null($customer->getCountry())) {
            $customerData['country'] = $customer->getCountry();
        }

        $customerData = array_merge(
            $customerData,
            $this->getCustomerAddressData($customer),
            $this->getCustomerPhoneData($customer)
        );

        $transactionData['customer'] = $customerData;

        $billing = $this->transaction->getBilling();

        $billingData = [
            'name' => $billing->getName()
        ];

        $transactionData['billing'] = array_merge(
            $billingData,
            $this->getBillingAddressData($billing)
        );

        $items = $this->transaction->getItems();

        if ($items instanceof Item) {
            $items = [$items];
        }

        if (is_array($items)) {
            $transactionData['items'] = [];
            foreach ($items as $item) {
                /** @var Item $item */
                $itemData = [
                    'id' => $item->getId(),
                    'title' => $item->getTitle(),
                    'unit_price' => $item->getUnitPrice(),
                    'quantity' => $item->getQuantity(),
                    'tangible' => $item->isTangible()
                ];

                if (!is_null($item->getCategory())) {
                    $itemData['category'] = $item->getCategory();
                }

                if (!is_null($item->getVenue())) {
                    $itemData['venue'] = $item->getVenue();
                }

                if (!is_null($item->getDate())) {
                    $itemData['date'] = $item->getDate();
                }

                $transactionData['items'][] = $itemData;
            }
        }

        if ($this->transaction->getSplitRules() instanceof SplitRuleCollection) {
            $transactionData['split_rules'] = $this->getSplitRulesInfo(
                $this->transaction->getSplitRules()
            );
        }

        return $transactionData;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'transactions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     * @param \PagarMe\Sdk\Customer\Customer $customer
     * @return array
     */
    public function getCustomerAddressData(Customer $customer)
    {
        $address = $customer->getAddress();

        if (is_null($address)) {
            return [];
        }

        if (is_array($address)) {
            $address = new \PagarMe\Sdk\Customer\Address($address);
        }

        return [
            'address' => [
                'street'        => $address->getStreet(),
                'street_number' => $address->getStreetNumber(),
                'complementary' => $address->getComplementary(),
                'neighborhood'  => $address->getNeighborhood(),
                'zipcode'       => $address->getZipcode()
            ]
        ];
    }

    public function getBillingAddressData(Billing $billing)
    {
        $address = $billing->getAddress();

        if (is_null($address)) {
            return [];
        }

        if (is_array($address)) {
            $address = new \PagarMe\Sdk\Customer\Address($address);
        }

        return [
            'address' => [
                'street'        => $address->getStreet(),
                'street_number' => $address->getStreetNumber(),
                'complementary' => $address->getComplementary(),
                'neighborhood'  => $address->getNeighborhood(),
                'zipcode'       => $address->getZipcode(),
                'country'       => $address->getCountry(),
                'state'         => $address->getState(),
                'city'          => $address->getCity()
            ]
        ];
    }

    /**
     * @param \PagarMe\Sdk\Customer\Customer $customer
     * @return array
     */
    public function getCustomerPhoneData(Customer $customer)
    {
        $phone = $customer->getPhone();

        if (is_null($phone)) {
            return [];
        }

        if (is_array($phone)) {
            $phone = new \PagarMe\Sdk\Customer\Phone($phone);
        }

        return [
            'phone_numbers' => [
                ((string) $phone->getDdi()) .
                ((string) $phone->getDdd()) .
                ((string) $phone->getNumber())
            ]
        ];
    }
}
