<?php
class HTMLRobot
{
    public static function clean($html)
    {
        $html = preg_replace("/<script.*?<\/script>/is", ' ', $html);
        $html = preg_replace("/<link.*?\/>/is", ' ', $html);
        $html = preg_replace("/<style type\=\"text\/css\">.*?<\/style>/is", '', $html);
        $html = preg_replace("/<\!\-\-.*?\-\->/is", ' ', $html);
        $html = preg_replace("/\(/is", '', $html);
        $html = preg_replace("/\'/is", '', $html);
        $html = preg_replace("/\s+/is", ' ', $html);
        $html = preg_replace("/<.*?>/is", '', $html);
        $html = strip_tags($html);
        return $html;
    }

    public static function findTitle($html)
    {
        $title = '';
        if ($title == '') {
            preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        if ($title == '') {
            preg_match("|<h1>(.*?)<\/h1>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        if ($title == '') {
            preg_match("|<h2>(.*?)<\/h2>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        if ($title == '') {
            preg_match("|<title>(.*?)<\/title>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        if ($title == '') {
            preg_match("|<h3>(.*?)<\/h3>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        if ($title == '') {
            preg_match("|<h4>(.*?)<\/h4>|is", $html, $matches);
            if (sizeof($matches)) {
                $title = $matches[1];
            }
        }
        return ($title);
    }
};
?>
