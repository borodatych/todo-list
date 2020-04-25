<!DOCTYPE HTML>
<html>
<head>
    <title>Страница не найдена</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="/assets/css/404.css?8" rel="stylesheet" type="text/css" media="all" />
    <link rel="shortcut icon" href="/assets/img/favicon.ico">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="/assets/js/404.js?3"></script>
</head>
<body>
	<div class="wrap">
		<div class="content">
			<div class="logo">
				<h1><a href="/"><img src="/assets/img/logo_full.svg"/></a></h1>
				<span><img src="/assets/img/404/signal.png"/>Oops! Страница не найдена :(</span>
			</div>
			<div class="buttom">
				<div class="seach_bar">
					<p>Перейти на <span><a href="/">Главную</a></span> или продолжить поиск</p>
                    <div class="search_box">
                        <form method="post" onSubmit="page404.submit(this,event)">
                            <input type="text" id="number" value="Поиск по номеру" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Поиск по номеру';}">
                            <input type="submit" value="" formaction="/price/office-0/number/" onsubmit="this.formaction = this.formaction + document.getElementById('number').value;">
                        </form>
                    </div>
				</div>
			</div>
		</div>
	    <p class="copy_right">&#169; 2011–<?=date('Y')?> DEMKA.ORG, <a href="mailto:boodatych@demka.org">Техподдержка</a></p>
	</div>
</body>
</html>