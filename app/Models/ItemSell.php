<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemSell extends Model
{
    use HasFactory;

    protected $table = 'items_sell';
    protected $fillable = ['id_item', 'price', 'count', 'user_id'];

    

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class,'id_item','id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
