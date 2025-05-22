<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'price_buy', 'count','user_id'];

    public function itemPurchases(): HasMany
    {
        // Specify 'id_item' as the foreign key
        return $this->hasMany(ItemPurchase::class, 'id_item', 'id');
    }

    public function itemSells(): HasMany
    {
        // Specify 'id_item' as the foreign key
        return $this->hasMany(ItemSell::class, 'id_item', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
