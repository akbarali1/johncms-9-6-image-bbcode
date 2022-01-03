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
    const VERSION = 'Akbarali BBCODE VERSION 3.1';

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
        self::versionBBcode();

        $this->render->addData(['title' => "BBCode Akbrali", 'page_title' => "BBCode Akbrali"]);

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
            $image_name = $this->saveFile($item, $user->id);
            $file_path = ($image_name['type'] == 'audio') ? '/upload/audio_akb/' : '/upload/images_akb/';
            $image_info[] = [
                'data'      => $image_name,
                'file_path' => $file_path . $image_name['name'],
            ];
        }
        return json_encode(
            [
                'success' => true,
                'message' => $image_info,
            ]
        );
    }

    public function saveFile($item, $user_id)
    {
        $file_info = new FileInfo($item->getClientFilename());
        $file_type = $file_info->getExtension();
        $filename = $user_id . '-' . time() . '-' . rand(0, 99999) . '.' . $file_type;
        $audio_extensions = ['mp3', 'wav', 'ogg'];
        $folder = $this->fileCheck($item, $audio_extensions);

        if (! file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $item->moveTo($folder . $filename);

        if (! $item->isMoved()) {
            die(json_encode(['error' => 'Ошибка при загрузке файла']));
        } else {
            $file_type = (in_array($file_type, $audio_extensions)) ? 'audio' : 'image';
            return $this->saveDataBase($filename, $user_id, $file_type);
        }
    }

    public function fileCheck($item, $audio_extensions)
    {
        $file_info = new FileInfo($item->getClientFilename());
        $file_type = $file_info->getExtension();
        $file_size = $item->getSize();
        if ($file_size > 2097152) {
            die(json_encode(['error' => true, 'message' => 'Размер файла превышает 2 Мб',]));
        }

        if ($file_info->isImage()) {
            $folder = UPLOAD_PATH . 'images_akb/';
        } elseif (in_array($file_type, $audio_extensions)) {
            $folder = UPLOAD_PATH . 'audio_akb/';
        } else {
            die(json_encode(['error' => true, 'message' => 'Недопустимый тип файла']));
        }
        return $folder;
    }

    protected function saveDataBase($name, $user_id, $type)
    {
        $response = (new ImageBBCodeModel())->create(
            [
                'name'    => $name,
                'user_id' => $user_id,
                'type'    => $type,
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
        self::versionBBcode();
        $search = [
            '/\[img\](.*?)\[\/img\]/is',
            '/\[audio\](.*?)\[\/audio\]/is',
        ];

        $replace = [
            '<img class="" src="/upload/images_akb/$1" alt="*" style=" max-width: 100%; ">',
            '<audio controls src="/upload/audio_akb/$1"> Your browser does not support the audio element. </audio>',
        ];

        return preg_replace($search, $replace, $content);
    }

    public static function versionBBcode()
    {
        header('VERSION_BBCODE: ' . self::VERSION);
    }
}
