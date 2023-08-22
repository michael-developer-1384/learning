<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;


class FilterCriterion extends Model
{
    use HasFactory;
    protected $table = 'filter_criteria';
    
    protected $fillable = [
        'filter_id',
        'model',
        'operator',
        'value',
        'chain_operator',
        'sort_order',
        'group_start',
        'group_end',
    ];

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::addGlobalScope('tenant_id', function (Builder $builder) {
            // Überprüfen, ob die aktuelle Anfrage von Nova kommt
            if (!Request::is('nova-api*')) {
                // Hier setzen Sie die tenant_id, die Sie filtern möchten.
                $tenantId = 1; 
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
