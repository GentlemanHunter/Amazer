<?php


namespace App\ExceptionCode;


class TaskStatus
{
    /** task status 1000-1999 */
    const UNEXECUTED = 1000,
        EXECUTEDCANCEL = 1001,
        EXECUTEDFAIL = 1002,
        EXECUTEDSUCCESS = 1003,
        EXECUTEVERSION = 1004;

    /**
     * task result
     * @param $code
     * @param null $local
     * @return string
     */
    public static function message($code, $local = null): string
    {
        if (is_null($local)) {
            $local = context()->get('language');
        }
        return \Swoft::t('task.' . $code, [], $local);
    }
}
