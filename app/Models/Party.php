<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'party_type',
        'title',
        'short_name',
        'address',
        'contact',
        'email',
        'contact_person',
        'bank_name',
        'iban',
        'ntn',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'party_type' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get parties by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('party_type', $type);
    }

    /**
     * Get active parties.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get inactive parties.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get customers only.
     */
    public function scopeCustomers($query)
    {
        return $query->where('party_type', 'customer');
    }

    /**
     * Get vendors only.
     */
    public function scopeVendors($query)
    {
        return $query->where('party_type', 'vendor');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'bg-label-success' : 'bg-label-secondary';
    }

    /**
     * Get the party type badge class.
     */
    public function getPartyTypeBadgeClassAttribute()
    {
        return $this->party_type === 'customer' ? 'bg-label-primary' : 'bg-label-success';
    }

    /**
     * Get formatted bank details.
     */
    public function getBankDetailsAttribute()
    {
        if (!$this->bank_name) {
            return 'No bank information';
        }

        return $this->bank_name . ($this->iban ? ' - ' . $this->iban : '');
    }

    /**
     * Get masked IBAN for display.
     */
    public function getMaskedIbanAttribute()
    {
        if (!$this->iban) {
            return 'No IBAN';
        }

        return strlen($this->iban) > 15 ? substr($this->iban, 0, 15) . '...' : $this->iban;
    }
}
