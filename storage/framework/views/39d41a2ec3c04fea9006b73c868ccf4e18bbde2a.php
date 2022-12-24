<?php if($cookieConsentConfig['enabled'] && !$alreadyConsentedWithCookies): ?>

    <?php echo $__env->make('cookieConsent::dialogContents', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php $__env->startSection('javascript'); ?>
    <script>

        window.laravelCookieConsent = (function () {

            var COOKIE_VALUE = 1;

            function consentWithCookies() {
                setCookie('<?php echo e($cookieConsentConfig['cookie_name']); ?>', COOKIE_VALUE, 365 * 20);
                hideCookieDialog();
            }

            function cookieExists(name) {
                return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
            }

            function hideCookieDialog() {
                var dialogs = document.getElementsByClassName('js-cookie-consent');

                for (var i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                var date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                // document.cookie = name + '=' + value + '; ' + 'expires=' + date.toUTCString() +';path=/';
                document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/' + <?php echo config('session.secure') ? "';secure'" : "''"; ?>;
            }

            if(cookieExists('<?php echo e($cookieConsentConfig['cookie_name']); ?>')) {
                hideCookieDialog();
            }

            var buttons = document.getElementsByClassName('js-cookie-consent-agree');

            for (var i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', consentWithCookies);
            }

            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog
            };
        })();
    </script>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
