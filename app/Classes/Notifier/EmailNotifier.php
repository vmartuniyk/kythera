<?php
/*
 *    Method #1
 *    http://phpartisan.tumblr.com/post/71932049693/laravel-4-custom-class-best-practice
 *
 *    {{ Notifier\EmailNotifier::notify() }}
 *    If we want to add it on alias so you can do EmailNotifier::notify(), we have add alias to app/config/app.php .
 *    Add 'EmailNotifier' => 'Notifier\EmailNotifier' at the end of aliases array.
 *    {{ EmailNotifier::notify() }}
 *
 *    Method #2
 *    http://andrewelkins.com/programming/php/how-to-add-a-helper-folder-to-laravel-4/
*/

namespace Notifier;

class EmailNotifier
{

    public static function notify()
    {
        return 'User Notified';
    }
}
