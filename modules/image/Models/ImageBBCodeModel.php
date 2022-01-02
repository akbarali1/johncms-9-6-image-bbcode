<?php

/**
 * Image Guestbook Controller Class
 *
 * @author Akbarali
 * Date: 31.12.2021
 * @telegram @kbarli
 * @website http://akbarali.uz
 * Email: Akbarali@uzhackersw.uz
 * Johncms Профил: https://johncms.com/profile/?user=38217
 * На тему: https://johncms.com/forum/?type=topic&id=12200
 */

declare(strict_types=1);

namespace Image\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Johncms\Users\User;

class ImageBBCodeModel extends Model
{
    protected $table = 'image_bbcode_akbarali';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'created_at',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
