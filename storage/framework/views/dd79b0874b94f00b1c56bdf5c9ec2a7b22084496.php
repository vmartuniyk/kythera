<!DOCTYPE html>
<html lang="<?php echo App::getLocale(); ?>"<?php echo Config::get('app.debug') ? ' class="debug"' : ''; ?>>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
    	<title>
    		<?php $__env->startSection('title'); ?>
    		<?php echo $__env->yieldSection(); ?>
    	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php $__env->startSection('meta_tags'); ?>
        <?php echo $__env->yieldSection(); ?>
        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/apple-touch-icon-114x114.png">

        <?php echo xhtml::style('assets/css/font-awesome.min.css'); ?>

        <?php /* xhtml::style('assets/css/bootstrap.min.css') */ ?>


    <?php if(!Config::get('app.cdn')): ?>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<?php else: ?>
		<?php echo xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap.min.css'); ?>

		<?php echo xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap-theme.min.css'); ?>

		<?php echo xhtml::script('assets/vendors/cdn/ajax/libs/jquery/2.1.3/jquery.min.js'); ?>

		<?php echo xhtml::script('assets/vendors/cdn/bootstrap/3.3.5/js/bootstrap.min.js'); ?>

		<?php endif; ?>

		<script>
		var debug = <?php echo Config::get('app.debug') ? 1 : 0;; ?>;
		var endpoint = "<?php echo $endpoint; ?>";
		</script>

        <!--<script src="https://www.google.com/recaptcha/api.js" async defer></script>-->
        <?php /*INVISIBLE RECAPTCHA SCRIPTA*/ ?>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>



        <?php echo xhtml::style('assets/vendors/bootstrap-languages/languages.min.css'); ?>


        <?php echo xhtml::style('assets/vendors/lightbox/css/lightbox.css'); ?>


        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <?php echo xhtml::script('assets/js/ie10-viewport-bug-workaround.js'); ?>


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <?php echo xhtml::script('assets/js/html5shiv.min.js'); ?>

            <?php echo xhtml::script('assets/js/respond.min.js'); ?>

        <![endif]-->

        <?php echo xhtml::style('assets/css/style.css?cb='.time()); ?>

        <?php echo xhtml::style('assets/css/typeahead.css?cb='.time()); ?>


        <?php echo xhtml::style('assets/vendors/jplayer/dist/skin/blue.monday/css/jplayer.blue.monday.css?cb='.time()); ?>


    	<style>
    			.loader {
    			    display:    none;
    			    position:   fixed;
    			    z-index:    1000;
    			    top:        0;
    			    left:       0;
    			    height:     100%;
    			    width:      100%;
    			    background: rgba( 255, 255, 255, .8 )
    			                url('/assets/img/ajax-loader.gif')
    			                50% 50%
    			                no-repeat;
    			}
    			#info.loading {
    			    overflow: hidden;
    			}
    			#info.loading .loader {
    			    display: block;
    			}

           <?php echo $__env->yieldContent('style'); ?>
    	</style>
    </head>

    <body>
        <?php echo $__env->make('cookieConsent::index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <a href="#" class="back-to-top"></a>

        <div class="container">
            <div class="header clearfix">
                <a href="/" title="Go home">
                    <img class="pull-left" src="<?php echo URL::asset('assets/img/kfnlogo.png'); ?>" alt="kythera family"/>
                </a>
                <img class="pull-right" src="<?php echo URL::asset('assets/img/janni_kat.jpg'); ?>" alt="kythera family"/>
            </div>
        </div>

        <div class="container">

                <?php echo xmenu::controls(); ?>


        </div>

        <div class="container mt10">

                <?php echo xmenu::header(); ?>


        </div>

        <div class="container">
                <div class="thin-line"></div>
        </div>


        <div class="container mt10">

                <?php echo xmenu::main(); ?>


        </div>

        <div class="container mt10">
                <div class="thin-line"></div>
        </div>

        <div class="container">

                <?php echo xmenu::sub(); ?>


        </div>

        <div class="container"><hr class="line black" /></div>

        <!-- CONTENT -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- /CONTENT -->

        <div class="container">
            <hr class="line black mt40 mt60" />
            <footer class="blue">
                <div class="row">

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="menu">
                                <img src="<?php echo URL::asset('assets/img/logo-small.png'); ?>" style="display: none"/>
                                <?php echo xmenu::footer(); ?>

                            </div>
                        </div>
                        <div class="col-md-6 photo">
							<?php if(!Config::get('app.debug')): ?>
								<?php echo $__env->make('site.page.footer.photos', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">

                    	<?php /*
                        <div class="col-md-6 post">
							<?php if(!Config::get('app.debug')): ?>
								<?php echo $__env->make('site.page.footer.posts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endif; ?>
                        </div>
                        */ ?>
                        <?php
                            //$a = array('aaa');
                            //print_r($a);
                            //print_r($items);
                        ?>
                        <div class="col-md-6 photo">
							<?php if(!Config::get('app.debug')): ?>
								<?php echo $__env->make('site.page.footer.photos-2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endif; ?>
                        </div>

                        <div class="col-md-6 social">
							<?php if(!Config::get('app.debug')): ?>
								<?php echo $__env->make('site.page.footer.social', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endif; ?>
						            </div>
                    </div>

                </div>
                <hr/>
                <span class="pull-right">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
            </footer>
        </div>

        <?php /* xhtml::script('assets/js/jquery.min.js') */ ?>

        <?php /* xhtml::script('assets/js/bootstrap.min.js') */ ?>
        <?php echo xhtml::script('assets/vendors/lightbox/js/lightbox.custom.js'); ?>


        <?php /* xhtml::script('assets/vendors/jquery-validation-1.13.1/dist/jquery.validate.js') */ ?>

        <?php echo xhtml::script('vendor/jsvalidation/js/jsvalidation.min.js'); ?>



        <?php echo xhtml::script('assets/vendors/typeahead.js/typeahead.bundle.custom.js?cb='.time()); ?>


        <?php echo xhtml::script('assets/vendors/jplayer/dist/jplayer/jquery.jplayer.min.js'); ?>


        <?php echo xhtml::script('assets/js/javascript.js?cb='.time()); ?>




        <!-- custom stylesheets -->
		<?php echo $__env->yieldContent('stylesheet'); ?>
		<!-- /custom stylesheets -->
        <!-- custom javascripts -->
		<?php echo $__env->yieldContent('javascript'); ?>
		<!-- /custom javascripts -->

		<div class="loader"><!-- Place at bottom of page --></div>
    </body>
</html>
