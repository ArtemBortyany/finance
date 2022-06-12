<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use App\Builders\Category as Builder;
use App\Models\Document\Document;
use App\Relations\HasMany\Category as HasMany;
use App\Scopes\Category as Scope;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Category extends Model
{
    use HasFactory, Transactions;

    public const INCOME_TYPE = 'income';
    public const EXPENSE_TYPE = 'expense';
    public const ITEM_TYPE = 'item';
    public const OTHER_TYPE = 'other';

    protected $table = 'categories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'type', 'color', 'enabled', 'created_from', 'created_by', 'parent_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'type', 'enabled'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \App\Builders\Category
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Instantiate a new HasMany relationship.
     *
     * @param  EloquentBuilder  $query
     * @param  EloquentModel  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return HasMany
     */
    protected function newHasMany(EloquentBuilder $query, EloquentModel $parent, $foreignKey, $localKey)
    {
        return new HasMany($query, $parent, $foreignKey, $localKey);
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
        return $this->resolveRouteBindingQuery($this, $value, $field)
            ->withoutGlobalScope(Scope::class)
            ->first();
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->withSubCategory();
    }

    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->withSubCategory()->with('categories')->orderBy('name');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document');
    }

    public function bills()
    {
        return $this->documents()->where('documents.type', Document::BILL_TYPE);
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }

    public function invoices()
    {
        return $this->documents()->where('documents.type', Document::INVOICE_TYPE);
    }

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    /**
     * Scope to only include categories of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    /**
     * Scope to include only income.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncome($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'income');
    }

    /**
     * Scope to include only expense.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpense($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'expense');
    }

    /**
     * Scope to include only item.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeItem($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'item');
    }

    /**
     * Scope to include only other.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOther($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'other');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    /**
     * Scope transfer category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTransfer($query)
    {
        return (int) $query->other()->pluck('id')->first();
    }

    /**
     * Scope gets only parent categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSubCategory($query)
    {
        return $query->withoutGlobalScope(new Scope);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'create',
            'url' => route('categories.edit', $this->id),
            'permission' => 'update-settings-categories',
        ];

        $transfer_id = Category::transfer();

        if ($this->id == $transfer_id) {
            return $actions;
        }

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'categories.destroy',
            'permission' => 'delete-settings-categories',
            'model' => $this,
        ];

        return $actions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Category::new();
    }
}
