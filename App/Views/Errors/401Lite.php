<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Page Not Found :(</title>
        <?php if ( !\App\Helpers\HTTP::isAjax() ) : ?>
            <style>
                ::-moz-selection {
                    background: #b3d4fc;
                    text-shadow: none;
                }
                ::selection {
                    background: #b3d4fc;
                    text-shadow: none;
                }
                html {
                    padding: 30px 10px;
                    font-size: 20px;
                    line-height: 1.4;
                    color: #737373;
                    background: #f0f0f0;
                    -webkit-text-size-adjust: 100%;
                    -ms-text-size-adjust: 100%;
                }
                html, input{ font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
                body {
                    max-width: 500px;
                    _width: 500px;
                    padding: 30px 20px 50px;
                    border: 1px solid #b3b3b3;
                    border-radius: 4px;
                    margin: 0 auto;
                    box-shadow: 0 1px 10px #a7a7a7, inset 0 1px 0 #fff;
                    background: #fcfcfc;
                }
                h1{ margin: 0 10px; font-size: 30px; text-align: center; }
                h1 span{ color: #bbb; }
                h3{ margin: 1.5em 0 0.5em; }
                p{ margin: 1em 0; }
                ul{ padding: 0 0 0 40px; margin: 1em 0; }
                .container{ max-width: 380px; _width: 380px; margin: 0 auto; text-align: left; }
                a.ref{ color:#777777 }
                a.ref:hover{ color:#444444 }

                /* google search */
                #goog-fixurl ul{ list-style: none; padding: 0; margin: 0; }
                #goog-fixurl form{ margin: 0; }
                #goog-wm-qt,
                #goog-wm-sb {
                    border: 1px solid #bbb;
                    font-size: 16px;
                    line-height: normal;
                    vertical-align: top;
                    color: #444;
                    border-radius: 2px;
                }
                #goog-wm-qt {
                    width: 220px;
                    height: 20px;
                    padding: 5px;
                    margin: 5px 10px 0 0;
                    box-shadow: inset 0 1px 1px #ccc;
                }
                #goog-wm-sb {
                    display: inline-block;
                    height: 32px;
                    padding: 0 10px;
                    margin: 5px 0 0;
                    white-space: nowrap;
                    cursor: pointer;
                    background-color: #f5f5f5;
                    background-image: -webkit-linear-gradient(rgba(255,255,255,0), #f1f1f1);
                    background-image: -moz-linear-gradient(rgba(255,255,255,0), #f1f1f1);
                    background-image: -ms-linear-gradient(rgba(255,255,255,0), #f1f1f1);
                    background-image: -o-linear-gradient(rgba(255,255,255,0), #f1f1f1);
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    *overflow: visible;
                    *display: inline;
                    *zoom: 1;
                }
                #goog-wm-sb:hover,
                #goog-wm-sb:focus {
                    border-color: #aaa;
                    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
                    background-color: #f8f8f8;
                }
                #goog-wm-qt:hover,
                #goog-wm-qt:focus {
                    border-color: #105cb6;
                    outline: 0;
                    color: #222;
                }
                input::-moz-focus-inner { padding: 0; border: 0; }
            </style>
        <?php else: ?>
            <style>
                p{ text-align: left; }
                p.center{ text-align: center; }
                .causes { width: 220px; margin: auto; text-align: left; }
            </style>
        <?php endif; ?>
    </head>
    <body>
        <div class="container">

            <h1>Доступ запрещен <span>:(</span></h1>
            <p class="center">К сожалению, страница, которую вы пытались просмотреть, требует авторизацию.</p>

            <?php if( @($msg) ){?>
                <h3>Answer with Server:</h3>
                <p><?php if( is_array($msg) || is_object($msg) ){ print'<pre>';print_r($msg);print'</pre>'; }else echo $msg; ?></p>

                <?php if( @($ref) ){?>
                    <?php if( is_array($ref) ){
                print'<pre>';print_r($ref);print'</pre>';exit;
            }else{?>
                        <a class="ref" href="<?=$ref?>">Return You Page</a>
                    <?php }?>
                <?php }?>

            <?php }else{?>

                <div class="causes">
                    <p>Возможные причины:</p>
                    <ul>
                        <li>нет доступа к запросу</li>
                        <li>нет доступа к странице</li>
                        <li>устаревшая ссылка</li>
                    </ul>
                </div>

            <?php }?>

            <pre><?=@$debugInfo?></pre>
        </div>
    </body>
</html>
