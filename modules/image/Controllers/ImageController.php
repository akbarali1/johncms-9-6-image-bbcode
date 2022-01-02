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

namespace Image\Controllers;

use Image\Install\ImageDatabaseInstall;
use Image\Models\ImageBBCodeModel;
use Image\Services\ImageService;
use Johncms\Controller\BaseController;
use Johncms\FileInfo;
use Johncms\System\Http\Request;
use Johncms\System\Http\Session;
use Johncms\Users\User;

class ImageController extends BaseController
{
    protected $module_name = 'image';

    /** @var string */
    protected $page_title = '';

    /** @var string */
    protected $base_url = '/image/';

    public function __construct()
    {
        parent::__construct();
        $this->nav_chain->add($this->page_title, $this->base_url);
        $config = di('config')['johncms'];
        $user = di(User::class);
        // If the guest is closed, display a message and close access (except for Admins)
        if (! $user->is_valid) {
            http_response_code(403);
            echo $this->render->render(
                'system::pages/result',
                [
                    'title'    => __('Только для зарегистрированных'),
                    'message'  => __('Только для зарегистрированных'),
                    'type'     => 'alert-danger',
                    'back_url' => '/',
                ]
            );
            exit;
        }
        ImageDatabaseInstall::install();
    }

    public function index(ImageService $service): string
    {
        $this->render->addData(['title' => "salom", 'page_title' => "salom"]);

        $user = di(User::class);

        $images = $service->getImages();

        return $this->render->render(
            'image::index',
            [
                'user_rights' => $user->rights,
                'images'      => $images['images'],
                'pagination'  => $images['pagination'],
            ]
        );
    }

    public function upload(ImageService $service, Request $request, Session $session, User $user)
    {
        $files = $request->getUploadedFiles();
        $image_info = [];
        foreach ($files['image'] as $item) {
            $image_name = $this->saveImage($item, $user->id);
            $image_info[] = [
                'data'       => $image_name,
                'image_path' => '/upload/images_akb/' . $image_name['name'],
            ];
        }
        return json_encode(
            [
                'success' => true,
                'message' => $image_info,
            ]
        );
    }

    public function saveImage($item, $user_id)
    {
        $file_info = new FileInfo($item->getClientFilename());
        if ($file_info->isImage()) {
            $filename = $user_id . '-' . time() . '-' . rand(0, 99999) . '.png';
            $folder = UPLOAD_PATH . 'images_akb/';
            if (! file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $item->moveTo($folder . $filename);
            if (! $item->isMoved()) {
                die(json_encode(['error' => 'Ошибка при загрузке файла']));
            } else {
                return $this->saveDataBase($filename, $user_id);
            }
        } else {
            die(json_encode(['error' => 'Разрешены только фото']));
        }
    }

    protected function saveDataBase($name, $user_id)
    {
        $response = (new ImageBBCodeModel())->create(
            [
                'name'    => $name,
                'user_id' => $user_id,
            ]
        );

        return $response;
    }

    public function delete(ImageService $service, Request $request, User $user)
    {
        if ($user->rights >= 1) {
            $service_response = $service->deleteImage($request->getPost('id', 0, FILTER_VALIDATE_INT));
            return json_encode(['success' => true, 'data' => $service_response]);
        } else {
            return json_encode(['error' => true, 'message' => 'У вас нет прав на удаление изображений']);
        }
    }

    public static function bbcode($content)
    {
        $search = [
            '/\[img\](.*?)\[\/img\]/is',
        ];

        $replace = [
            '<img class="" src="../upload/images_akb/$1" alt="*" style=" max-width: 100%; ">',
        ];

        return preg_replace($search, $replace, $content);
    }
}
