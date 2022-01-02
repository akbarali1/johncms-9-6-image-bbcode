<?php
/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 *
 * @author Akbarali
 * Date: 31.12.2021
 * @telegram @kbarli
 * @website http://akbarali.uz
 * Email: Akbarali@uzhackersw.uz
 * Johncms Профил: https://johncms.com/profile/?user=38217
 * На тему: https://johncms.com/forum/?type=topic&id=12200
 */

namespace Image\Install;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class ImageDatabaseInstall
{
    public static function install()
    {
        $schema = Capsule::Schema();
        if (! $schema->hasTable('image_bbcode_akbarali')) {
            $schema->create(
                'image_bbcode_akbarali',
                static function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('user_id');
                    $table->string('name', 255);
                    $table->dateTime('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
                }
            );
        }
    }

}
