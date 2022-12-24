<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<style>
			body {
	            font-family: arial, san serif;
	            color: #000;
			}
			hr.blue {
			    background-color: #00adf0;
			    border: 0 none;
			    height: 3px;
			}
			hr.thin {
			    background-color: #dfdfdf;
			    border: 0 none;
			    height: 1px;
			}
			hr.black {
			    background-color: #000;
			    border: 0 none;
			    height: 3px;
			}
			.menu a {
				font: 14px/10px Arial;
				padding-right: 10px;
	            text-decoration: none;
	            color: #000;
			}
			h1 {
			    font: bold 34px/34px arial;
			    margin: 30px 0;
			}
			p.content {
			    font: 14px/19px arial;
			}
			.footer {
	            float:right;
	            color: #b7b7b7;
	            font: 12px/12px arial;
	        }
	        .author {
			    font: 12px/12px arial;
			    color: #00adf0;
			}
			
             table img {width:255px;}
             table tr {vertical-align:top}
             table td {font: 14px/19px arial;}
		</style>
	</head>
	<body>
		<a title="Go home" href="{!!$host!!}">
			<img alt="kythera family" src="{!!$host!!}/assets/img/kfnlogo.png">
        </a>
        <hr class="thin"/>
        <div class="menu">
			<a href="{!!$view!!}">&raquo;View</a>
			<a href="{!!$edit!!}">&raquo;Edit</a>
			<a href="{!!$disable!!}">&raquo;Disable</a>
			<a href="{!!$promote!!}">&raquo;Top article</a>
			<a href="{!!$facebook!!}">&raquo;Post @ Facebook</a>
		</div>
		<hr class="black"/>
		<p class="author">{!!$author!!}</p>
		<hr class="thin"/>
		<h1>> {!!$title!!}</h1>
		<hr class="thin"/>
		
        <table border=0><tr>
            @if (isset($image))
            <td><img src="{!!$image!!}" align="left"></td>
            @endif
            <td>{!!$content!!}</td>
        </tr></table>
		
		<hr class="black"/>
        <div class="menu">
			<a href="{!!$view!!}">&raquo;View</a>
			<a href="{!!$edit!!}">&raquo;Edit</a>
			<a href="{!!$disable!!}">&raquo;Disable</a>
			<a href="{!!$promote!!}">&raquo;Top article</a>
			<a href="{!!$facebook!!}">&raquo;Post @ Facebook</a>
		</div>
		<hr class="thin"/>
		<span class="footer">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
	</body>
</html>
