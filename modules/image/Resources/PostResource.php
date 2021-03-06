<?php

/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

declare(strict_types=1);

namespace Image\Resources;

use Guestbook\Models\Guestbook;
use Johncms\Users\User;

/**
 * Class PostResource
 *
 * @package Guestbook\Resources
 * @property Guestbook $model
 */
class PostResource extends BaseResource
{
    public function toArray(): array
    {
        $file_path = ($this->model->type == 'audio') ? '/upload/audio_akb/' . $this->model->name : '/upload/images_akb/' . $this->model->name;
        return [
            'id'         => $this->model->id,
            'name'       => $this->model->name,
            'file_path'  => $file_path,
            'created_at' => $this->model->created_at,
            'type'       => $this->model->type,
            'user_id'    => $this->model->user_id,
            'user'       => $this->getUser(),
//            'meta'         => $this->getMeta(),
        ];
    }

    protected function getUser(): array
    {
        $user_model = $this->model->user;
        if ($user_model !== null) {
            $user = [
                'id'          => $user_model->id,
                'name'        => $user_model->name,
                'profile_url' => $user_model->profile_url,
                'rights_name' => $user_model->rights_name,
                'rights'      => $user_model->rights,
                'status'      => $user_model->status,
            ];
        }

        return $user ?? [];
    }

    protected function getMeta(): array
    {
        $current_user = di(User::class);
        if ($current_user->rights > 0) {
            $meta = [
                'ip'            => $this->model->ip,
                'search_ip_url' => '/admin/search_ip/?ip=' . $this->model->ip,
                'user_agent'    => $this->model->browser,
            ];

            if ($this->model->user === null || $current_user->rights >= $this->model->user->rights) {
                $meta['can_manage'] = true;
                $meta['edit_url'] = '/guestbook/edit?id=' . $this->model->id;
                $meta['delete_url'] = '/guestbook/delpost?id=' . $this->model->id;

                if ($current_user->rights >= 6) {
                    $meta['reply_url'] = '/guestbook/otvet?id=' . $this->model->id;
                }
            }
        }

        return $meta ?? [];
    }
}
