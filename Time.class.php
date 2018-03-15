<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Time.php
 * Date: 2018/1/31
 * Time: 18:09
 */

namespace Extend\Base;


class Time
{
    public static function weekDuration($format = '', $time = null)
    {
        $timestamp = !is_null($time) ? (is_numeric($time) ? $time : strtotime($time)): time();
        return [
            !$format ? strtotime(date('Y-m-d', strtotime("this week Monday", $timestamp))) :
                date($format, strtotime("this week Monday", $timestamp)),
            !$format ? strtotime(date('Y-m-d', strtotime("this week Sunday", $timestamp))) + 24 * 3600 - 1 :
                date($format, strtotime("this week Sunday", $timestamp) + 24 * 3600 - 1)
        ];
    }

    public static function weekDays($format = '', $time = null)
    {
        $timestamp = !is_null($time) ? (is_numeric($time) ? $time : strtotime($time)): time();
        $week_start = strtotime("this week Monday", $timestamp);
        $days = [];
        for($i = 0; $i < 7; $i++){
            $days[] = !$format ? strtotime("+{$i} day", $week_start) : date($format, strtotime("+{$i} day", $week_start));
        }
        return $days;
    }

    public function __call($method, $args)
    {
        if(is_callable("static::$method")){
            dump($method);
        }else{
            dump('no');
        }
    }
}