# Author: Akbarali
# Time: 31.12.2021
# Email: Akbarali@uzhackersw.uz
# Providing financial assistance (Webmoney:  R853215959425
# Johncms profile link: https://johncms.com/profile/?user=38217
# На тему: https://johncms.com/forum/?type=topic&id=12200

Я решил продолжить снова через 1 год.
BBCode нового изображения для JOHNCMS 9.6
Установка
1. `config/autoload/bbcode.global.php`
   Вы пишете в 16 строках
   ```//image || Rasm || Akbarali yozgan
   'img' => [
   'from' => '#\[img](.+?)\[/img]#is',
   'to'=> '<img class="" src="../upload/images_akb/$1" alt="*" style=" max-width: 100%; ">',
   'data' => '$1',
   ],
   // Akbarali yozgan kod tugadi
   ```
2. Распаковать zip в основной каталог сайта
3. Отправить в базу данных (Скоро это будет автоматизировано)
````
   CREATE TABLE `image_bbcode_akbarali` (
   `id` int(11) NOT NULL,
   `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
   `user_id` int(11) NOT NULL,
   `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `image_bbcode_akbarali`
ADD PRIMARY KEY (`id`);

ALTER TABLE `image_bbcode_akbarali`
MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;
````
BBcode был удален в некоторых областях Johncms. То есть у Johncms 9.6 нет bbcode в гостевой книге. 
Значит, вам нужно ввести bbcode вручную.

4. Вам необходимо добавить BBcode в гостевую книгу
   Файл
   `modules/guestbook/Resources/PostResource.php`
   Вы меняете 36 строк
 
`'text'=> \Image\Controllers\ImageController::bbcode($this->model->post_text),`


Спонсорство
Я тоже потратил время на написание этого. И раздал бесплатно всем. Небольшое спонсорство, которое вы мне предоставили, вдохновит меня на написание следующих бесплатных модулей, Если у вас нет возможности и условий спонсировать, просто нажмите +5 на Карму в моем профиле. Профил: https://johncms.com/profile/?user=38217 (Вы не будете платить за это).
Webmoney кошелька WMR: R853215959425
Webmoney кошелька WMZ: Z401474330355
Если вы хотите помочь через qiwi, напишите на Akbarali@uzhackersw.uz или в профиль на https://johncms.com/profile/?user=38217
 
