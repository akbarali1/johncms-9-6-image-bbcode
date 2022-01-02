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

declare(strict_types=1);

namespace Image\Services;

class ControlService
{

    public static function return(array $data = [])
    {
        header('Content-Type: application/json');

        die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }


}
