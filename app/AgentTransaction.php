<?php

namespace App;

use App\Scopes\BusinessScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $agents_id
 * @property integer $warehouses_id
 * @property integer $account_books_id
 * @property integer $business_id
 * @property integer $users_id
 * @property string $code
 * @property float $amount
 * @property string $payment_method
 * @property string $card_number
 * @property string $card_holder_name
 * @property string $card_transaction_no
 * @property string $card_type
 * @property string $card_month
 * @property integer $card_year
 * @property integer $csv
 * @property string $cheque_number
 * @property string $bank_account_no
 * @property string $transaction_no
 * @property mixed $description
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AccountBook $accountBook
 * @property Agent $agent
 * @property Business $business
 * @property User $user
 * @property Warehouse $warehouse
 * @property AllTransaction[] $allTransactions
 */
class AgentTransaction extends Model
{
    use SoftDeletes;
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['agents_id', 'warehouses_id', 'account_books_id', 'business_id', 'users_id', 'code', 'amount', 'payment_method', 'card_number', 'card_holder_name', 'card_transaction_no', 'card_type', 'card_month', 'card_year', 'csv', 'cheque_number', 'bank_account_no', 'transaction_no', 'description', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountBook()
    {
        return $this->belongsTo('App\AccountBook', 'account_books_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('App\Agent', 'agents_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo('App\Business');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse', 'warehouses_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allTransactions()
    {
        return $this->hasMany('App\AllTransaction', 'agent_transactions_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BusinessScope);
    }
}
