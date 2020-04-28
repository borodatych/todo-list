<!DOCTYPE html>
<html lang="ru">

<!-- begin::Head -->
<head>
    <base href="">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title><?=\App\Helpers\Arr::exist($title)?></title>
    <meta name="keyword" content="<?=\App\Helpers\Arr::exist($keywords)?>">
    <meta name="description" content="<?=\App\Helpers\Arr::exist($description)?>">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Raleway:300,400,500,600,700">
    <!--end::Fonts -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.bundle.css?2" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <?php foreach( (array) \App\Helpers\Arr::exist($arrCSS,[]) AS $source ): ?>
        <link href="<?=$source?>" rel="stylesheet" type="text/css" />
    <?php endforeach; ?>
    <!--end::Page Vendors Styles -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="/assets/media/logos/favicon.ico" />
    <script>
        const ready = function ( fn ) {
            if ( typeof fn !== 'function' ) return;
            if ( document.readyState === 'complete'  ) return fn();
            document.addEventListener( 'interactive', fn, false );
            document.addEventListener( 'complete', fn, false );
            window.addEventListener( 'load', fn, false );
        };
    </script>
</head>

<!-- end::Head -->


<!-- begin::Body -->
<body class="
kt-subheader--fixed
kt-subheader-mobile--fixed
kt-page--loading
">
<?php
$isAuth = (int)\App\Helpers\Arr::exist($isAuth);
?>
<!-- begin:: Page -->
<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Subheader -->
            <div id="kt_subheader" class="kt-subheader kt-grid__item ">
                <div class="kt-container  kt-container--fluid ">

                    <!-- begin:: Subheader Title -->
                    <div class="kt-subheader__title">
                        <div class="kt-subheader__breadcrumbs">
                            <a href="" class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--home">ToDo List</a>
                            <span class="kt-subheader__breadcrumbs-separator"></span>
                            <a href="" class="kt-subheader__breadcrumbs-link ">All</a>
                        </div>
                    </div>
                    <!-- end:: Subheader Title -->

                    <!-- begin:: Sub-header toolbar -->
                    <div class="kt-subheader__toolbar">
                        <div class="kt-subheader__toolbar-wrapper">
                            <?php if( $isAuth ): ?>
                                <button id="profileInfo" class="btn btn-default btn-bold btn-upper btn-font-sm" data-is-admin="<?=$isAuth?>" onClick="TDAuth.logout()">
                                    <i class="flaticon2-user"></i>
                                    Profile
                                </button>
                            <?php else: ?>
                                <button id="profileInfo"  data-toggle="modal" data-target="#authModal" class="btn btn-default btn-bold btn-upper btn-font-sm" data-is-admin="<?=$isAuth?>">
                                    <i class="flaticon2-user"></i>
                                    Profile
                                </button>
                                <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">Authorization</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <form id="kt_form_auth" class="kt-form">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Login:</label>
                                                    <input type="text" name="login" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Password:</label>
                                                    <input type="password" name="password" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="reset" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" name="authBtn" class="btn btn-outline-brand" data-ktwizard-type="action-submit">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- end:: Sub-header toolbar -->
                </div>
            </div>
            <!-- end:: Subheader -->

            <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                <!-- begin:: Content -->
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-text">
                            <h4 class="alert-heading">Dear Friend!</h4>
                            <p>
                                The project was made as part of a test task.
                                No complicated authorization. Access for admin: admin / 123.
                                Huge request not to touch other people's tasks and creating your own, be guided by the culture of speech and norms of behavior.
                                In general, you understand!
                            </p>
                            <hr>
                            <p class="mb-0">
                                <a href="https://github.com/borodatych/todo-list" target="_blank" class="kt-link">GitHub Fork</a>
                            </p>
                        </div>
                    </div>
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-text">
                            <h4 class="alert-heading">По братски!</h4>
                            <p>
                                Проект сделан в рамках тестового задания.
                                Нет сложной авторизации. Доступ для админа: admin / 123 .
                                Ограмноя просьба не трогать чужие таски и создавая свои, руководствуйтесь культурой речи и нормами поведения.
                                В общем, вы поняли!
                            </p>
                            <hr>
                            <p class="mb-0">
                                <a href="https://github.com/borodatych/todo-list" target="_blank" class="kt-link">GitHub Fork</a>
                            </p>
                        </div>
                    </div>
                    <?=\App\Helpers\Arr::exist($content)?>
                </div>
                <!-- end:: Content -->
            </div>

            <!-- begin:: Footer -->
            <div class="kt-footer kt-grid__item" id="kt_footer">
                <div class="kt-container ">
                    <div class="kt-footer__copyright">
                        2020&nbsp;&copy;&nbsp;<a href="http://demka.org" target="_blank" class="kt-link">Demka Org</a>
                    </div>
                    <div class="kt-footer__menu">
                        <a href="http://demka.org" target="_blank" class="kt-link">About</a>
                        <a href="https://github.com/borodatych/todo-list" target="_blank" class="kt-link">GitHub Fork</a>
                        <a href="https://tezarius.ru" target="_blank" class="kt-link">Tezarius Team</a>
                        <a href="https://yulsun.ru" target="_blank" class="kt-link">Yulsun Team</a>
                    </div>
                </div>
            </div>
            <!-- end:: Footer -->

        </div>
    </div>
</div>

<!-- end:: Page -->


<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>
<!-- end::Scrolltop -->


<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#1cac81",
                "metal": "#c4c5d6",
                "light": "#ffffff",
                "accent": "#00c5dc",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995",
                "focus": "#9816f4"
            },
            "base": {
                "label": [
                    "#b9bdc1",
                    "#aeb2b7",
                    "#414b4c",
                    "#343d3e"
                ],
                "shape": [
                    "#eef4f3",
                    "#e0e9e6",
                    "#80c3af",
                    "#41675c"
                ]
            }
        }
    };
</script>
<!-- end::Global Config -->


<!--begin::Global Theme Bundle(used by all pages) -->
<script src="/assets/plugins/global/plugins.bundle.js?2" type="text/javascript"></script>
<script src="/assets/js/scripts.bundle.js?2" type="text/javascript"></script>
<!--end::Global Theme Bundle -->


<!--begin::Page Vendors & Scripts (used by this page) -->
<?php foreach( (array) \App\Helpers\Arr::exist($arrJS,[]) AS $source ): ?>
    <script src="<?=$source?>" type="text/javascript"></script>
<?php endforeach; ?>
<!--end::Page Scripts -->


<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(62300935, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/62300935" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>

<!-- end::Body -->
</html>
