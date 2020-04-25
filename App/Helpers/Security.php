<?php

/**
 * Security helper.
 *
 * @package    YFW
 * @category   Helpers
 * @author     borodatych@demka.org
 * @copyright  (c) 2017 Yulsun Team
 * @license    http://yulsun.ru/license
 */

namespace App\Helpers;


class Security
{
    public static $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
	public static function encryptIt($q)
	{
		$cryptKey = static::$cryptKey;
		$qEncoded = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
		return( $qEncoded );
	}
    public static function decryptIt($q)
    {
        $cryptKey = static::$cryptKey;
        $qDecoded = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        return( $qDecoded );
    }
    public static function base64Encode($str)
    {
        return base64_encode($str);
    }
    public static function base64Decode($str)
    {
        return base64_decode($str);
    }
    /** Simple Encryption */
    public static function arrEncryption($array,$method=0){
        $method = (int) $method;
        switch($method){
            case 1:
                $jArr  = json_encode($array);
                $rjArr = strrev($jArr);
                $sArr  = serialize($rjArr);
                $rsArr = strrev($sArr);
                $bArr  = base64_encode($rsArr);
                $rbArr = strrev($bArr);
                $enArr = str_replace("=","$",$rbArr);
                break;
            default:
                $jArr  = json_encode($array);
                $rjArr = strrev($jArr);
                $sArr  = serialize($rjArr);
                $rsArr = strrev($sArr);
                $bArr  = base64_encode($rsArr);
                $rbArr = strrev($bArr);
                $enArr = str_replace("=","-",$rbArr);
                $enArr = substr($enArr,0,7).'0'.substr($enArr,7,strlen($enArr));
                $enArr = substr($enArr,0,24).'1'.substr($enArr,24,strlen($enArr));
        }
        return $enArr;
    }
    /** Simple Decryption */
    public static function arrDecryption($array,$method=0){
        $method = (int) $method;
        switch($method){
            case 1:
                $rbArr = str_replace("$","=",$array);
                $bArr  = strrev($rbArr);
                $rsArr = base64_decode($bArr);
                $sArr  = strrev($rsArr);
                $rjArr = @unserialize($sArr);
                $jArr  = strrev($rjArr);
                $deArr = json_decode($jArr);
                break;
            default:
                $array = Text::cutSymbol($array,[7,24])->string;
                $rbArr = str_replace("-","=",$array);
                $bArr  = strrev($rbArr);
                $rsArr = base64_decode($bArr);
                $sArr  = strrev($rsArr);
                $rjArr = @unserialize($sArr);
                $jArr  = strrev($rjArr);
                $deArr = json_decode($jArr);
        }
        return $deArr;
    }

    public static function genPass( $sym = 'allchars', $max = 16 ){
        $arrSym = array(
            'allchars' => 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP',
            'chars'    => 'qazxswedcvfrtgbnhyujmkiolpQAZXSWEDCVFRTGBNHYUJMKIOLP',
            'number'   => '1234567890',
        );
        $chars = $arrSym[$sym];
        $size  = StrLen($chars)-1;
        $pass  = '';
        while( $max-- ){ $pass .= $chars[ rand(0,$size) ]; }
        return $pass;
    }
}
