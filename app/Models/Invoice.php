<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_queue_id',
        'customer_id',
        'invoice_date',
        'invoice_file',
    ];

    public function jobQueue()
    {
        return $this->belongsTo(JobQueue::class);
    }

    public function customer()
    {
        return $this->belongsTo(Party::class, 'customer_id');
    }
}
