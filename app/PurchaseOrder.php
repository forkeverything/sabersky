<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\PurchaseOrder
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $approved
 * @property boolean $submitted
 * @property integer $project_id
 * @property integer $vendor_id
 * @property integer $user_id
 * @property-read \App\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LineItem[] $lineItems
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @property-read mixed $total
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rule[] $rules
 */
class PurchaseOrder extends Model
{
    protected $fillable = [
        'approved',
        'submitted',
        'total',
        'user_id',
        'project_id',
        'vendor_id'
    ];

    /**
     * A PO is made to a single Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * A PO can contain many Line Items.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Processes the submission of a Purchase Order
     *
     * @return $this
     */
    public function processSubmission()
    {
        // update purchase requests
        $this->updatePurchaseRequests();

        // Mark Submitted
        $this->submitted = true;

        // Check for Rules
        $this->attachRules();

        // try to approve if there are not rules in the way
        $this->tryAutoApprove();

        // Save & return
        $this->save();
        return $this;
    }

    protected function updatePurchaseRequests()
    {
        foreach ($this->lineItems as $lineItem) {
            $lineItem->purchaseRequest->update([
                'quantity' => $lineItem->purchaseRequest->quantity - $lineItem->quantity
            ]);
        }
    }

    public function markApproved()
    {
        $this->status = 'approved';
        $this->save();
        return $this;
    }

    public function markRejected()
    {
        $this->status = 'rejected';
        $this->save();
        return $this;
    }

    /**
     * A purchase order can have many rules which apply.
     * If any rules are flagged - it must be approved
     * by a team member who's role is pre-approved.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class);
    }

    /**
     * Quick 'string' checker for PO status
     * @param $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->status === $status;
    }

    /**
     * Wrapper function that delegates out rule assignment
     * to the Rule Model.
     *
     * @return $this
     */
    protected function attachRules()
    {
        // Get a list of all the company's rules
        $companyRules = $this->getCompanyRules();
        // For each rule - check each property
        foreach ($companyRules as $rule) {
            $rule->processPurchaseOrder($this);
        }
        // All done attaching rules...
        return $this;
    }

    /**
     * Approves a PO if it does not have any
     * active rules that apply.
     *
     * @return $this
     */
    public function tryAutoApprove()
    {
        // If PO is 'pending' and DOES NOT have rules that apply
        if($this->hasStatus('pending') && count($this->rules) === 0) {
            $this->markApproved();
        }
        return $this;
    }

    /**
     * Returns all the rules for the project's
     * company.
     *
     * @return Rule[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getCompanyRules()
    {
        return $this->project->company->rules;
    }

    /**
     * A PO Could be made out to an address that belongs to
     * either a Vendor or a Company linked to Vendor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

}
