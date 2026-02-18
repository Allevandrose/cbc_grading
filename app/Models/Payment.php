<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'receipt_number',
        'amount_paid',
        'balance',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'notes',
        'recorded_by',
        'is_verified',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'payment_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
