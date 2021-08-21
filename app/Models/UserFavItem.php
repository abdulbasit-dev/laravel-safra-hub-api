<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserFavItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFavItem whereUserId($value)
 * @mixin \Eloquent
 */
class UserFavItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
