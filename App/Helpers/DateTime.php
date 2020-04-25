<?php

/**
 * Date helper.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;
/// Еще идеи тут
/// https://rche.ru/888_php-date-vyvod-russkogo-mesyaca.html
/// https://vitalik.ws/zametki/78-nazvanie-mesyaca-data-na-russkom-yazyke-s-pomoshyu-php.html
class DateTime extends \DateTime
{
    /// Именительный / Винительный
    public static $ruMonths_1 = [1 => 'Январь' , 'Февраль' , 'Март' , 'Апрель' , 'Май' , 'Июнь' , 'Июль' , 'Август' , 'Сентябрь' , 'Октябрь' , 'Ноябрь' , 'Декабрь'];
    // Родительный
    public static $ruMonths_2 = [1 => 'Января' , 'Февраля' , 'Марта' , 'Апреля' , 'Мая' , 'Июня' , 'Июля' , 'Августа' , 'Сентября' , 'Октября' , 'Ноября' , 'Декабря'];
    /// Дательный
    public static $ruMonths_3 = [1 => 'Январю' , 'Февралю' , 'Марту' , 'Апрелю' , 'Маю' , 'Июню' , 'Июлю' , 'Августу' , 'Сентябрю' , 'Октябрю' , 'Ноябрю' , 'Декабрю'];
    /// Творительный
    public static $ruMonths_4 = [1 => 'Январём' , 'Февралём' , 'Мартом' , 'Апрелем' , 'Маем' , 'Июнем' , 'Июлем' , 'Августом' , 'Сентябрём' , 'Октябрём' , 'Ноябрём' , 'Декабрём'];
    /// Предложный
    public static $ruMonths_5 = [1 => 'Январе' , 'Феврале' , 'Марте' , 'Апреле' , 'Мае' , 'Июне' , 'Июле' , 'Августе' , 'Сентябре' , 'Октябре' , 'Ноябре' , 'Декабре'];

    public static function ruWeekday($mn)
    {
        switch( $mn )
        {
            case 1:  $m = "Понедельник"; break;
            case 2:  $m = "Вторник";     break;
            case 3:  $m = "Среда";       break;
            case 4:  $m = "Четверг";     break;
            case 5:  $m = "Пятница";     break;
            case 6:  $m = "Суббота";     break;
            case 7:  $m = "Воскресенье"; break;
            default: $m = "на этой неделе";
        }
        return $m;
    }
}
