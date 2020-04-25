/**
 * YULSUN JS Library for Ajax.
 * Это то что после Ajax будет применятся к новому коду
 *
 * @package    YFW
 * @category   JS Libraries
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */


if( typeof ready !== 'function' ) const ready = function ( fn )
{
    if ( typeof fn !== 'function' ) return;
    if ( document.readyState === 'complete'  ) return fn();
    document.addEventListener( 'interactive', fn, false );
    document.addEventListener( 'complete', fn, false );
    window.addEventListener( 'load', fn, false );
};

ready(function()
{
    $('.phoneNumber').mask('+7 (999) 999-99-99');
});