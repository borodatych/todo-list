<?php

namespace App\Helpers;

class Debug
{
    public static function debug($object, $title=null, $plain=false, $limit=10, $level=0)
    {
        if (defined('DEBUG') && ((isset($_SERVER['REMOTE_ADDR']) && !(debug==$_SERVER['REMOTE_ADDR'] || strpos(debug,$_SERVER['REMOTE_ADDR'].',')===0 || strpos(debug,','.$_SERVER['REMOTE_ADDR'].',')!==false || strpos(debug,','.$_SERVER['REMOTE_ADDR'])===strlen(debug)-strlen($_SERVER['REMOTE_ADDR'])-1)) || (!isset($_SERVER['REMOTE_ADDR']) && debug===false))
        )
        {
            return;
        }
        if ($plain===false && php_sapi_name()==='cli')
        {
            $plain = true;
        }
        if (is_string($plain))
        {
            $color_black = "";
            $color_black_light = "";
            $color_red = "";
            $color_red_light = "";
            $color_green = "";
            $color_green_light = "";
            $color_yellow = "";
            $color_yellow_light = "";
            $color_blue = "";
            $color_blue_light = "";
            $color_purple = "";
            $color_purple_light = "";
            $color_cyan = "";
            $color_cyan_light = "";
            $color_gray = "";
            $color_gray_light = "";
            $color_clear = "";
        }
        else if ($plain===true)
        {
            $color_black = "\33[0;30m";
            $color_black_light = "\33[1;30m";
            $color_red = "\33[0;31m";
            $color_red_light = "\33[1;31m";
            $color_green = "\33[0;32m";
            $color_green_light = "\33[1;32m";
            $color_yellow = "\33[1;33m";
            $color_yellow_light = "\33[0;33m";
            $color_blue = "\33[0;34m";
            $color_blue_light = "\33[1;34m";
            $color_purple = "\33[0;35m";
            $color_purple_light = "\33[1;35m";
            $color_cyan = "\33[0;36m";
            $color_cyan_light = "\33[1;36m";
            $color_gray = "\33[0;37m";
            $color_gray_light = "\33[1;37m";
            $color_clear = "\033[0;39m";
        }
        $result = '';
        static $charset;
        if ($charset===null && !$plain)
        {
            echo "<meta charset=\"utf-8\">\n";
            $charset = true;
        }
        if ($level>$limit)
        {
            if ($plain)
            {
                return str_repeat(' ', 4*$level)."...\n";
            }
            else
            {
                return "...";
            }
        }
        if (is_object($object) || (is_array($object) && count($object)>0))
        {
            foreach ($object as $key => $value)
            {
                if ($plain)
                {
                    $result .= str_repeat(' ', 4*$level).$key;
                }
                else
                {
                    if (is_array($object))
                    {
                        $result .= "<span style='background:silver;padding-left:2px;padding-right:2px;'>".$key.'</span>';
                    }
                    else
                    {
                        $result .= '<span>'.$key.'</span>';
                    }
                }
                if (is_object($value) || (is_array($value) && count($value)>0))
                {
                    if (is_object($value))
                    {
                        if ($plain)
                        {
                            $result .= " (".get_class($value).")";
                        }
                        else
                        {
                            $result .= "<span style='color:grey'> ".get_class($value)."</span>";
                        }
                    }
                    else
                    {
                        if ($plain)
                        {
                            $result .= " (array)";
                        }
                        else
                        {
                            $result .= "<span style='color:grey'> array</span>";
                        }
                    }
                    if ($plain)
                    {
                        $result .= "\n";
                    }
                    else
                    {
                        $collapse = !($level+1<=1 || $title===true || (is_string($title) && substr($title,0,1)=='*'));
                        $result .= "\n<div style='".($collapse?'height:12px':'').";margin-top:2px;margin-bottom:2px;border:1px solid grey;padding:3px;margin-left:15px;background-color:rgb(".(255-($level*10)).",".(255-($level*10)).",".(255-($level*10)).");overflow:hidden;'>\n<div id='tree' style='background-color:silver;font-size:9px;cursor:pointer;float:right;border:1px solid black;width:10px;height:10px;text-align:center;padding:0px;overflow:hidden;' onclick=\"if(this.parentNode.style.height=='12px'){this.parentNode.style.height='';this.innerHTML='-'}else{this.parentNode.style.height='12px';this.innerHTML='+'}\">".($collapse?'+':'-')."</div>";
                    }
                    $result .= self::debug ($value, $title, $plain, $limit, $level+1);
                    if ($plain)
                    {
                        $result .= "";
                    }
                    else
                    {
                        $result .= "</div>\n";
                    }
                }
                else
                {
                    $result .= " : ";
                    if ($value===null)
                    {
                        if ($plain)
                        {
                            $result .= $color_cyan."null".$color_clear;
                        }
                        else
                        {
                            $result .= "<span style='color:blue'>null</span>";
                        }
                    }
                    else if (is_bool($value))
                    {
                        if ($value)
                        {
                            if ($plain)
                            {
                                $result .= $color_purple_light."true".$color_clear;
                            }
                            else
                            {
                                $result .= "<span style='color:green'>true</span>";
                            }
                        }
                        else
                        {
                            if ($plain)
                            {
                                $result .= $color_purple_light."false".$color_clear;
                            }
                            else
                            {
                                $result .= "<span style='color:red'>false</span>";
                            }
                        }
                    }
                    else if (is_integer($value) || is_float($value))
                    {
                        if ($plain)
                        {
                            $result .= $color_red_light.$value.$color_clear;
                        }
                        else
                        {
                            $result .= "<span style='color:maroon'>".$value."</span>";
                        }
                    }
                    else if (is_array($value))
                    {
                        if ($plain)
                        {
                            $result .= "[]";
                        }
                        else
                        {
                            $result .= "<span style='color:red'>[]</span>";
                        }
                    }
                    else
                    {
                        if ($plain)
                        {
                            $result .= $color_green_light."\"".$value."\"".$color_clear;
                        }
                        else
                        {
                            $result .= "\"".htmlspecialchars ($value,ENT_NOQUOTES,'UTF-8')."\"";
                        }
                    }
                    if ($plain)
                    {
                        $result .= "\n";
                    }
                    else
                    {
                        $result .= "<br>\n";
                    }
                }
            }
            if ($level>0)
            {
                return $result;
            }
        }
        else
        {
            if ($object===null)
            {
                if ($plain)
                {
                    $result = $color_cyan."null".$color_clear;
                }
                else
                {
                    $result = "<span style='color:blue'>null</span>";
                }
            }
            else if (is_bool($object))
            {
                if ($object)
                {
                    if ($plain)
                    {
                        $result = $color_purple_light."true".$color_clear;
                    }
                    else
                    {
                        $result = "<span style='color:green'>true</span>";
                    }
                }
                else
                {
                    if ($plain)
                    {
                        $result = $color_purple_light."false".$color_clear;
                    }
                    else
                    {
                        $result = "<span style='color:red'>false</span>";
                    }
                }
            }
            else if (is_integer($object) || is_float($object))
            {
                if ($plain)
                {
                    $result = $color_red_light.$object.$color_clear;
                }
                else
                {
                    $result = "<span style='color:maroon'>".$object."</span>";
                }
            }
            else if (is_array($object))
            {
                if ($plain)
                {
                    $result = "[]";
                }
                else
                {
                    $result = "<span style='color:red'>[]</span>";
                }
            }
            else
            {
                if ($plain)
                {
                    $result = $color_green_light."\"".$object."\"".$color_clear;
                }
                else
                {
                    $result = "\"".htmlspecialchars ($object,ENT_NOQUOTES,'UTF-8')."\"";
                }
            }
            if ($plain)
            {
                $result .= "\n";
            }
            else
            {
                $result .= "<br>\n";
            }
        }
        if (is_null($object))
        {
            $type = 'null';
        }
        else if (is_bool($object))
        {
            $type = 'boolean';
        }
        else if (is_object($object))
        {
            $type = get_class($object);
        }
        else if (is_array($object))
        {
            $type = 'array';
        }
        else if (is_int($object))
        {
            $type = 'integer';
        }
        else if (is_float($object))
        {
            $type = 'float';
        }
        else
        {
            $type = 'string';
        }
        if ($plain)
        {
            $header = '';
            if ($title)
            {
                $header = $color_cyan;
                $header .= "--------------------------------------\n";
                $header .= (is_bool($title) || $title===null)?$type:$title;
                $header .= "\n--------------------------------------\n";
                $header .= $color_clear;
            }
            if (is_string($plain))
            {
                if ($plain=='error_log')
                {
                    error_log ("\n".$header.$result);
                }
                else
                {
                    file_put_contents ($plain, $header.$result, FILE_APPEND);
                }
            }
            else
            {
                echo $header.$result;
            }
        }
        else
        {
            $trace = debug_backtrace();
            $node = reset ($trace);
            $file = basename($node['file']).":".$node['line'];
            $debug = "<div>";
            foreach ($trace as $key => $value)
            {
                if (isset($value['file']) && $value['line'])
                {
                    $debug .= "<a href='subl://".str_replace('\\','/',$value['file']).":".$value['line']."' style='color:black;text-decoration:none;'>".$value['file']."</a> [".$value['line']."] <font color=maroon>".$value['function']."</font><br>";
                }
                else
                {
                    $debug .= "<font color=maroon>".$value['function']."</font><br>";
                }
            }
            $debug .= "</div>";
            echo "<div style='box-shadow: 5px 5px 5px #888888;background:#f1f1f1;z-index:9999;position:relative;font-family:dejavu sans mono;font-size:12px;line-height:normal!important;/*width:550px;*/border:1px solid grey;padding:3px;margin:10px;'>\n";
            echo "<div style='background-color:grey;padding:1px;margin-bottom:5px;border:1px solid black;height:14px;overflow:hidden;'>".(($title && !is_bool($title))?$title:$type)."<div id='tree' style='background-color:silver;font-size:9px;cursor:pointer;float:right;border:1px solid black;width:10px;height:10px;text-align:center;padding:0px;overflow:hidden;margin-left:5px' onclick=\"if(this.parentNode.style.height=='14px'){this.parentNode.style.height='';this.innerHTML='-'}else{this.parentNode.style.height='14px';this.innerHTML='+'}\">+</div><a href='subl://".str_replace('\\','/',$node['file']).":".$node['line']."' style='color:silver;text-decoration:none;float:right'>".$file."</a>".$debug."</div>\n";
            echo $result;
            echo "</div>\n";
        }
    }
    public static function dd($object, $title=null, $plain=false, $limit=99, $level=0)
    {
        self::debug($object,$title,$plain,$limit,$level);
        exit;
    }
    public static function pp($object, $title=null, $plain=false, $limit=99, $level=0)
    {
        self::debug($object,$title,$plain,$limit,$level);
    }
}