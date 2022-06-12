<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Common\Media as MediaModel;
use App\Models\Setting\Category;
use App\Scopes\Transaction as Scope;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use App\Traits\Recurring;
use App\Traits\Transactions;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use Cloneable, Currencies, DateTime, HasFactory, Media, Recurring, Transactions;

    public const INCOME_TYPE = 'income';
    public const INCOME_SPLIT_TYPE = 'income-split';
    public const INCOME_RECURRING_TYPE = 'income-recurring';
    public const EXPENSE_TYPE = 'expense';
    public const EXPENSE_SPLIT_TYPE = 'expense-split';
    public const EXPENSE_RECURRING_TYPE = 'expense-recurring';

    protected $table = 'transactions';

    protected $dates = ['deleted_at', 'paid_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'type',
        'number',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'document_id',
        'contact_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'parent_id',
        'split_id',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'currency_rate' => 'double',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['type', 'number', 'paid_at', 'amount','category.name', 'account.name', 'customer.name', 'invoice.document_number'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['recurring'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    public function bill()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Setting\Category')->withDefault(['name' => trans('general.na')]);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'parent_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\Models\Common\Contact')->withDefault(['name' => trans('general.na')]);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function document()
    {
        return $this->belongsTo('App\Models\Document\Document', 'document_id')->withoutGlobalScope('App\Scopes\Document');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'parent_id')->isRecurring();
    }

    public function recurring()
    {
        return $this->morphOne('App\Models\Common\Recurring', 'recurable');
    }

    public function splits()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'split_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'contact_id', 'id');
    }

    public function scopeType(Builder $query, $types): Builder
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    public function scopeIncome(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getIncomeTypes());
    }

    public function scopeIncomeRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::INCOME_RECURRING_TYPE);
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getExpenseTypes());
    }

    public function scopeExpenseRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', self::EXPENSE_RECURRING_TYPE);
    }

    public function scopeIsRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-recurring');
    }

    public function scopeIsNotRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-recurring');
    }

    public function scopeIsSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-split');
    }

    public function scopeIsNotSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-split');
    }

    public function scopeIsTransfer(Builder $query): Builder
    {
        return $query->where('category_id', '=', Category::transfer());
    }

    public function scopeIsNotTransfer(Builder $query): Builder
    {
        return $query->where('category_id', '<>', Category::transfer());
    }

    public function scopeIsDocument(Builder $query): Builder
    {
        return $query->whereNotNull('document_id');
    }

    public function scopeIsNotDocument(Builder $query): Builder
    {
        return $query->whereNull('document_id');
    }

    public function scopeDocumentId(Builder $query, int $document_id): Builder
    {
        return $query->where('document_id', '=', $document_id);
    }

    public function scopeAccountId(Builder $query, int $account_id): Builder
    {
        return $query->where('account_id', '=', $account_id);
    }

    public function scopeContactId(Builder $query, int $contact_id): Builder
    {
        return $query->where('contact_id', '=', $contact_id);
    }

    public function scopeCategoryId(Builder $query, int $category_id): Builder
    {
        return $query->where('category_id', '=', $category_id);
    }

    /**
     * Order by paid date.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->sum('amount');
    }

    public function scopeIsReconciled(Builder $query): Builder
    {
        return $query->where('reconciled', 1);
    }

    public function scopeIsNotReconciled(Builder $query): Builder
    {
        return $query->where('reconciled', 0);
    }

    public function onCloning($src, $child = null)
    {
        if (app()->has(\App\Console\Commands\RecurringCheck::class)) {
            $suffix = '';
        } else {
            $suffix = $src->isRecurringTransaction() ? '-recurring' : '';
        }

        $this->number       = $this->getNextTransactionNumber($suffix);
        $this->document_id  = null;
        $this->split_id     = null;
    }

    /**
     * Convert amount to double.
     *
     * @return float
     */
    public function getAmountForAccountAttribute()
    {
        $amount = $this->amount;

        // Convert amount if not same currency
        if ($this->account->currency_code != $this->currency_code) {
            $to_code = $this->account->currency_code;
            $to_rate = config('money.' . $this->account->currency_code . '.rate');

            $amount = $this->convertBetween($amount, $this->currency_code, $this->currency_rate, $to_code, $to_rate);
        }

        return $amount;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->all();
    }

    /**
     * Get the splittable status.
     *
     * @return bool
     */
    public function getIsSplittableAttribute()
    {
        return is_null($this->split_id);
    }

    public function delete_attachment()
    {
        if ($attachments = $this->attachment) {
            foreach ($attachments as $file) {
                MediaModel::where('id', $file->id)->delete();
            }
        }
    }

    /**
     * Check if the record is attached to a transfer.
     *
     * @return bool
     */
    public function getHasTransferRelationAttribute()
    {
        return (bool) ($this->category?->id == $this->category?->transfer());
    }

    /**
     * Get the title of type.
     *
     * @return string
     */
    public function getTypeTitleAttribute($value)
    {
        $type = $this->getRealTypeOfRecurringTransaction($this->type);

        return $value ?? trans_choice('general.' . Str::plural($type), 1);
    }

    /**
     * Get the route name.
     *
     * @return string
     */
    public function getRouteNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->isIncome()) {
            if (! empty($this->document_id) && $this->document->type != 'invoice') {
                return $this->getRouteFromConfig();
            } else {
                return !empty($this->document_id) ? 'invoices.show' : 'transactions.show';
            }
        }

        if ($this->isExpense()) {
            if (! empty($this->document_id) && $this->document->type != 'bill') {
                return $this->getRouteFromConfig();
            } else {
                return !empty($this->document_id) ? 'bills.show' : 'transactions.show';
            }
        }

        return 'transactions.index';
    }

    public function getRouteFromConfig()
    {
        $route = '';

        $alias = config('type.document.' . $this->document->type . '.alias');
        $prefix = config('type.document.' . $this->document->type . '.route.prefix');

        // if use module set module alias
        if (!empty($alias)) {
            $route .= $alias . '.';
        }

        if (!empty($prefix)) {
            $route .= $prefix . '.';
        }

        if ($route) {
            return $route . 'show';
        }

        return 'transactions.index';
    }

    /**
     * Get the route id.
     *
     * @return string
     */
    public function getRouteIdAttribute($value)
    {
        return !empty($value) ? $value : (!empty($this->document_id) ? $this->document_id : $this->id);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $prefix = 'transactions';

        if (Str::contains($this->type, 'recurring')) {
            $prefix = 'recurring-transactions';
        }

        try {
            $actions[] = [
                'title' => trans('general.show'),
                'icon' => 'visibility',
                'url' => route($prefix. '.show', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-more-actions-show-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            if (! $this->reconciled) {
                $actions[] = [
                    'title' => trans('general.edit'),
                    'icon' => 'edit',
                    'url' => route($prefix. '.edit', $this->id),
                    'permission' => 'update-banking-transactions',
                    'attributes' => [
                        'id' => 'index-more-actions-edit-' . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            if (empty($this->document_id)) {
                $actions[] = [
                    'title' => trans('general.duplicate'),
                    'icon' => 'file_copy',
                    'url' => route($prefix. '.duplicate', $this->id),
                    'permission' => 'create-banking-transactions',
                    'attributes' => [
                        'id' => 'index-more-actions-duplicate-' . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            if ($this->is_splittable && empty($this->document_id) && empty($this->recurring)) {
                $connect = [
                    'type' => 'button',
                    'title' => trans('general.connect'),
                    'icon' => 'sensors',
                    'permission' => 'create-banking-transactions',
                    'attributes' => [
                        'id' => 'index-transactions-more-actions-connect-' . $this->id,
                        '@click' => 'onConnect(\'' . route('transactions.dial', $this->id) . '\')',
                    ],
                ];

                $actions[] = $connect;

                $actions[] = [
                    'type' => 'divider',
                ];
            }
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.print'),
                'icon' => 'print',
                'url' => route($prefix. '.print', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-more-actions-print-' . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.download_pdf'),
                'icon' => 'picture_as_pdf',
                'url' => route($prefix. '.pdf', $this->id),
                'permission' => 'read-banking-transactions',
                'attributes' => [
                    'id' => 'index-more-actions-pdf-' . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        if ($prefix != 'recurring-transactions') {
            $actions[] = [
                'type' => 'divider',
            ];

            try {
                $actions[] = [
                    'type' => 'button',
                    'title' => trans('general.share_link'),
                    'icon' => 'share',
                    'url' => route('modals.transactions.share.create', $this->id),
                    'permission' => 'read-banking-transactions',
                    'attributes' => [
                        'id' => 'index-more-actions-share-' . $this->id,
                        '@click' => 'onShareLink("' . route('modals.transactions.share.create', $this->id) . '")',
                    ],
                ];
            } catch (\Exception $e) {}

            try {
                $actions[] = [
                    'type' => 'button',
                    'title' => trans('invoices.send_mail'),
                    'icon' => 'email',
                    'url' => route('modals.transactions.emails.create', $this->id),
                    'permission' => 'read-banking-transactions',
                    'attributes' => [
                        'id' => 'index-more-actions-send-email-' . $this->id,
                        '@click' => 'onEmail("' . route('modals.transactions.emails.create', $this->id) . '")',
                    ],
                ];
            } catch (\Exception $e) {}

            $actions[] = [
                'type' => 'divider',
            ];

            try {
                if (! $this->reconciled) {
                    $actions[] = [
                        'type' => 'delete',
                        'icon' => 'delete',
                        'text' => ! empty($this->recurring) ? 'transactions' : 'recurring_template',
                        'route' => $prefix. '.destroy',
                        'permission' => 'delete-banking-transactions',
                        'model' => $this,
                    ];
                }
            } catch (\Exception $e) {}
        } else {
            try {
                $actions[] = [
                    'title' => trans('general.end'),
                    'icon' => 'block',
                    'url' => route($prefix. '.end', $this->id),
                    'permission' => 'update-banking-transactions',
                ];
            } catch (\Exception $e) {}
        }

        return $actions;
    }

    /**
     * Get the recurring status label.
     *
     * @return string
     */
    public function getRecurringStatusLabelAttribute()
    {
        return match($this->recurring->status) {
            'active'    => 'status-partial',
            'ended'     => 'status-success',
            default     => 'status-success',
        };
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $query = $this->where('id', $value);

        if (request()->route()->hasParameter('recurring_transaction')) {
            $query->isRecurring();
        }

        return $query->firstOrFail();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Transaction::new();
    }
}
