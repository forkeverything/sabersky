<?php


namespace App\Factories;


use App\Address;
use App\Http\Requests\SubmitPurchaseOrderRequest;
use App\PurchaseOrder;
use App\User;

class PurchaseOrderFactory
{
    /**
     * The PurchaseOrder model either created or we receive to
     * make chanes to.
     *
     * @var PurchaseOrder
     */
    public $purchaseOrder;

    /**
     * The form request containing our data
     *
     * @var SubmitPurchaseOrderRequest
     */
    protected $request;

    /**
     * The user performing the request
     *
     * @var User
     */
    protected $user;

    /**
     * Billing - Address Model
     * @var
     */
    public $billingAddress;

    /**
     * Shipping - Address Model
     *
     * @var
     */
    public $shippingAddress;

    /**
     * PurchaseOrderFactory constructor.
     *
     * @param SubmitPurchaseOrderRequest $request
     * @param User|null $user
     * @param PurchaseOrder|null $purchaseOrder
     */
    public function __construct(SubmitPurchaseOrderRequest $request = null, User $user = null, PurchaseOrder $purchaseOrder = null)
    {
        $this->request = $request;
        $this->user = $user;
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Make a new PurchaseOrder
     *
     * @param SubmitPurchaseOrderRequest $request
     * @param User $user
     * @return mixed
     */
    public static function make(SubmitPurchaseOrderRequest $request, User $user)
    {
        $factory = new static($request, $user);
        $factory->createPurchaseOrder()
                ->createBillingAddress()
                ->createShippingAddress()
                ->createLineItems()
                ->createAdditionalCosts()
                ->processNewPurchaseOrder();

        return $factory->purchaseOrder;
    }

    /**
     * Make changes to an existing Order
     *
     * @param PurchaseOrder $purchaseOrder
     * @param User|null $user (User making changes)
     * @return static
     */
    public static function change(PurchaseOrder $purchaseOrder, User $user = null)
    {
        return new static(null, $user, $purchaseOrder);
    }

    /**
     * Create the model from the given request
     *
     * @return $this
     */
    public function createPurchaseOrder()
    {
        $this->purchaseOrder = PurchaseOrder::create([
            'vendor_id' => $this->request->input('vendor_id'),
            'vendor_address_id' => $this->request->input('vendor_address_id'),
            'vendor_bank_account_id' => $this->request->input('vendor_bank_account_id'),
            'currency_id' => $this->request->input('currency_id'),
            'user_id' => $this->user->id,
            'company_id' => $this->user->company_id
        ]);

        return $this;
    }

    /**
     * Set Billing Address to the same as the given User's Company or
     * create a new Address.
     *
     * @return $this
     */
    public function createBillingAddress()
    {
        // IF billing was not same as company
        $this->billingAddress = $this->user->company->address;
        if (!$this->request->input('billing_address_same_as_company')) {
            $this->billingAddress = Address::create([
                'contact_person' => $this->request->input('billing_contact_person'),
                'phone' => $this->request->input('billing_phone'),
                'address_1' => $this->request->input('billing_address_1'),
                'address_2' => $this->request->input('billing_address_2'),
                'city' => $this->request->input('billing_city'),
                'zip' => $this->request->input('billing_zip'),
                'state' => $this->request->input('billing_state'),
                'country_id' => $this->request->input('billing_country_id')
            ]);
        }

        return $this;
    }

    /**
     * Set Shipping Address to same as Billing or create
     * a new Address from the given request.
     *
     * @return $this
     */
    public function createShippingAddress()
    {
        $this->shippingAddress = $this->billingAddress;
        if (! $this->request->input('shipping_address_same_as_billing')) {
            $this->shippingAddress = Address::create([
                'contact_person' => $this->request->input('shipping_contact_person'),
                'phone' => $this->request->input('shipping_phone'),
                'address_1' => $this->request->input('shipping_address_1'),
                'address_2' => $this->request->input('shipping_address_2'),
                'city' => $this->request->input('shipping_city'),
                'zip' => $this->request->input('shipping_zip'),
                'state' => $this->request->input('shipping_state'),
                'country_id' => $this->request->input('shipping_country_id')
            ]);
        }

        return $this;
    }

    /**
     * Create our LineItem models
     *
     * @return $this
     */
    public function createLineItems()
    {
        // Create Line Items
        foreach ($this->request->input('line_items') as $lineItem) {
            $this->purchaseOrder->lineItems()->create([
                'quantity' => $lineItem['order_quantity'],
                'price' => $lineItem['order_price'],
                'payable' => array_key_exists('order_payable', $lineItem) ? $lineItem['order_payable'] : null,
                'delivery' => array_key_exists('order_delivery', $lineItem) ? $lineItem['order_delivery'] : null,
                'purchase_request_id' => $lineItem['id']
            ]);
        }

        return $this;
    }

    /**
     * Create PurchaseOrderAdditionalCost Models
     *
     * @return $this
     */
    public function createAdditionalCosts()
    {
        // IF any Additional Costs - add them
        if ($additionalCosts = $this->request->input('additional_costs')) {
            foreach ($additionalCosts as $cost) {
                $this->purchaseOrder->additionalCosts()->create([
                    'name' => $cost['name'],
                    'type' => $cost['type'],
                    'amount' => $cost['amount']
                ]);
            }
        }

        return $this;
    }


    /**
     * Call the necessary methods for a new PurchaseOrder
     *
     * @param Address $billingAddress
     * @param Address $shippingAddress
     * @return PurchaseOrder|null
     */
    public function processNewPurchaseOrder(Address $billingAddress = null, Address $shippingAddress = null)
    {
        if($billingAddress) $this->billingAddress = $billingAddress;
        if($shippingAddress) $this->shippingAddress = $shippingAddress;
        
        $this->purchaseOrder->setTotal()
                            ->attachBillingAndShippingAddresses($this->billingAddress, $this->shippingAddress)
                            ->updatePurchaseRequests()
                            ->attachRules()
                            ->tryAutoApprove();

        return $this->purchaseOrder;
    }
}