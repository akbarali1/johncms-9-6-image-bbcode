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

use Image\Resources\PostResource;
use Image\Resources\ResourceCollection;
use Image\Models\ImageBBCodeModel;
use Johncms\Users\User;

class ImageService
{
    /** @var User */
    protected $user;

    /** @var array */
    protected $config;

    /** @var array */
    protected $guest_access = [];

    public function __construct()
    {
        $this->user = di(User::class);
        $this->config = di('config')['johncms'];

        // Here you can (separated by commas) add the ID of those users who are not in the administration.
        // But who are allowed to read and write in the admin club
        $this->guest_access = [];
    }

    public function getImages(): array
    {
        $messages = (new ImageBBCodeModel())
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($this->user->config->kmess);

        $images = new ResourceCollection($messages, PostResource::class);

        return [
            'images'     => $images->toArray(),
            'pagination' => $messages->render(),
        ];
    }

    public function deleteImage(int $id): array
    {
        $image = (new ImageBBCodeModel())->find($id);

        if ($image) {
            $image->delete();
            return [
                'status'  => true,
                'message' => 'Изображение удалено',
                'id'      => $id,
            ];
        }

        return false;
    }


}
