<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<style>
			body {
	            font-family: arial, san serif;
	            font: 14px/19px arial;
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
			.footer {
	            float:right;
	            color: #b7b7b7;
	            font: 12px/12px arial;
	        }
	        .author {
			    font: 12px/12px arial;
			    color: #00adf0;
			}
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
		</div>
        
        <hr class="black"/>
        <p class="author">A new comment on your document was {!!$author!!}</p>
        <p class="content">{!!$content!!}</p>
        Url to document: <a href="{!!$view!!}">{!!$view!!}</a>
		<hr class="black"/>
		<span class="footer">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
	</body>
</html>