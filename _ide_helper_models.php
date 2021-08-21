<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserFavItem[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Picnic
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Picnic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Picnic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Picnic query()
 */
	class Picnic extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $name fname and lname sperated by space
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $image
 * @property string|null $qrcode
 * @property string|null $bio
 * @property int|null $gender 1=>male 2=>female 3=>other
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property int $is_available whether currently in picnic or not
 * @property string|null $otp
 * @property int $otp_verified
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserFavItem[] $favItems
 * @property-read int|null $fav_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $friends
 * @property-read int|null $friends_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
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
 */
	class UserFavItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\friend
 *
 * @method static \Illuminate\Database\Eloquent\Builder|friend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|friend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|friend query()
 */
	class friend extends \Eloquent {}
}

