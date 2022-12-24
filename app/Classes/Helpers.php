<?php

namespace App\Classes;

use App\Http\Requests\Request;
use App;
use DB;
use Html;

/*
 *
 *
 * {{ Helpers::doMessage() }}
 *
 */

class Helpers
{

    public static function doMessage()
    {
        $message = 'Boe';
        $message = App::getLocale();
        $message = Request::path();
        return $message;
    }

    public static function lastQuery()
    {
        if ($queries = DB::getQueryLog()) {
            $sql = end($queries);

            if (! empty($sql['bindings'])) {
                $pdo = DB::getPdo();
                foreach ($sql['bindings'] as $binding) {
                    $sql['query'] =
                    preg_replace(
                        '/\?/',
                        $pdo->quote($binding),
                        $sql['query'],
                        1
                    );
                }
            }

            return $sql['query'];
        }
    }


    public static function obfuscateEmails($text)
    {
        #detect mailto links
        if (preg_match_all('#<a\s+(?:[^>]*?\s+)?href="mailto:([^"]*)">(.*)</a>#', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $text = str_ireplace($m[0], Html::mailto($m[1], Html::email($m[2])), $text);
            }
        }

        #detect email addresses
        if (preg_match_all('#[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b#i', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $text = str_ireplace($m[0], Html::email($m[0]), $text);
            }
        }

        return $text;
    }
}
