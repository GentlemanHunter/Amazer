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

    public static $message = [
        self::UNEXECUTED => 'preparing !(:>',
        self::EXECUTEDCANCEL => 'Cancelled !(:<',
        self::EXECUTEDFAIL => 'failure(:<',
        self::EXECUTEDSUCCESS => 'success!(:',
        self::EXECUTEVERSION => "Expired-:)."
    ];

    /**
     * task result
     * @param $code
     * @param null $local
     * @return string
     */
    public static function message($code, $local = null): string
    {
        if (is_numeric($code)) {
            $code = self::$message[$code];
        }
        if (is_null($local)) {
            $local = context()->get('language', 'en');
        }
        return $local == 'en' ? $code : \Swoft::t('task.' . $code, [], $local);
    }
}
