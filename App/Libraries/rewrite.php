<?php
/**
 * Тут все сугубо индивидуально
 * Оставил как идею
 * Это нужно сто бы, к примеру, настроить тот же 302 редирект со тарого ресурса
*/

$_uri = NULL;

if( $_region = @$_COOKIE['region__'.SITE_ID] )
{
    // Замечено в каталогах от ilcats
    /// http://new.yulsun.ru/index.php?showbrends_by_nomer=E050000201
    if( isset( $_GET[ 'showbrends_by_nomer' ] ) )
    {
        $_number = $_GET[ 'showbrends_by_nomer' ];
        $_uri = "/price/office-{$_region}/number/{$_number}";
    }
    /// Замечено в каталогах Tradesoft
    /// http://new.yulsun.ru/?pcat=ctcinf&idsp_tvcrkta=0163%2053&brname=Peugeot&11
    if( isset($_GET['idsp_tvcrkta']) && isset($_GET['brname']) )
    {
        $_brand = $_GET['brname'];
        $_number = $_GET['idsp_tvcrkta'];
        $_number = urldecode($_number);
        ///$_number = iconv("CP1251", "UTF-8", $_number);
        $_number = str_replace([' ',"\\s","\\t"],'',$_number);
        $_uri = "/price/office-{$_region}/{$_number}/{$_brand}";
        ////die('URI: '.$_uri);
    }
}


$m = [];
$HTTP_HOST = @$_SERVER['HTTP_HOST'];
$REQUEST_URI = @$_SERVER['REQUEST_URI'];
if( $REQUEST_URI )
{
    $_LINKS = [
        '#^/(offices)\.php#',
        '#^/(catalogs)\.php#',
        '#^/(club)\.php#',
        '#^/(articles)\.php#',
        '#^/(articles)\.php\?id=(\d+)#',
        '#^/(news)\.php\?setregion=(\d+)#',
        '#^/(news)\.php\?setregion=(\d+)&id=(\d+)#',
        ///'#^/(kontakt)\.php#', /// Можно, но не пофеншую
        '#^/(kontakt)\.php\?setregion=(\d+)#',
        '#^/(kontakt)\.php\?hid=(\d+)&bid=(\d+)&setregion=(\d+)#',
        '#^/(price_categor)\.php\?categor_id=(\d+)&setregion=(\d+)#',
        '#^/(index)\.php\?setregion=(\d+)#',
        ///'#^/(public_oferta)\.php\?id=(\d+)#',
    ];
    foreach( $_LINKS AS $_LINK )
    {
        if( preg_match($_LINK,$REQUEST_URI,$m) )
        {
            $fileName = $m[1];
            switch($fileName)
            {
                case 'offices':{ $_uri = "/offices"; }break;
                case 'catalogs':{ $_uri = "/catalogs"; }break;
                case 'club':{ $_uri = "/club"; }break;
                case 'articles':
                {
                    if( count($m)==2 ) $_uri = "/articles";
                    if( count($m)==3 ) $_uri = "/article/{$m['2']}";
                }break;
                case 'news':
                {
                    if( count($m)==3 ) $_uri = "/news/office-{$m['2']}";
                    if( count($m)==4 ) $_uri = "/news/office-{$m['2']}/{$m['3']}";
                }break;
                case 'kontakt':
                {
                    ///if( count($m)==2 ) $_uri = "/contacts/office-0";
                    if( count($m)==3 ) $_uri = "/contacts/office-{$m['2']}";
                    if( count($m)==5 ) $_uri = "/contacts/office-{$m['4']}";
                }break;
                case 'price_categor':{
                    if( count($m)==4 ) $_uri = "/price/office-{$m['3']}/category/{$m['2']}";
                }break;
                case 'index':{
                    if( count($m)==3 ) $_uri = "/office-{$m['2']}";
                }break;
                case 'public_oferta':{
                    if( count($m)==3 ) $_uri = "/office-{$m['2']}/offer";
                }break;
                case 'zzz':{}break;
                default:
                {
                    ///
                }break;
            }
        }
    }
}


if( 'dev.yulsun.ru'==$HTTP_HOST && !$_uri )
{
    ///print'<pre>';print_r([$_uri,$m,$_SERVER]);print'</pre>';exit;
}
elseif( !$_uri )
{
    $_uri = '';
}


if( $_uri )
{
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: {$_uri}");
    exit();
}