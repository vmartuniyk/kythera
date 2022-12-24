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
		<a title="Go home" href="https://kythera-family.net/">
			<img alt="kythera family" src="https://kythera-family.net/assets/img/kfnlogo.png">
        </a>
        <hr class="black"/>
<p>
Dear Subscriber,
<br/>
<br/>You recently registered an account at Kythera-Family.net with this email address.
<br/>
<br/>Please follow the link below to verify your email address and activate your account:
<br/>
<br/><a href="{{ action('UsersController@getConfirm', ['code' => $user->confirmation_code]) }}">{{ action('UsersController@getConfirm', ['code' => $user->confirmation_code]) }}</a>
<br/>
<br/>Thanks for your interest in the Kythera-family.net website.
<br/>
<br/>James Prineas
<br/>Site Administrator
<br/>
<br/>.._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _
<br/>
<br/>>About Kythera-Family.net
<br/>With the help of interested Kytherians worldwide, Kythera-Family.net aims to preserve and reflect the rich heritage of a wonderful island. Members of the community are invited to submit their family collection of Kytherian stories, photographs, recipes, maps, oral histories, biographies, historical documents, songs and poems, home remedies etc. to the site. Uploading directly to the site is easy, but if you wish you can also send your collections to us by email or post and we will submit them for you. Thus we can help make available valuable and interesting material for current and future generations, and inspire young Kytherians to learn more about their fascinating heritage.
<br/>
<br/>.._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _..._ _ _
</p>
		<hr class="black"/>
		<span class="footer">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
	</body>
</html>
