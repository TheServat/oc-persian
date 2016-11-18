<?php namespace RtlWeb\Persian\Classes;

use Jenssegers\Date\Date as DateBase;

/**
 * Persian Argon class.
 */
class Argon extends DateBase
{
    public $eDateTime;

    public static  $jalali = true;

    public function __construct($time = null, $tz = null)
    {        
        $a = parent::__construct($time, $tz);
        $this->eDateTime = new \EasyDateTime($this->tzName, 'jalali');
        return $a;
    }
    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
//    protected static $toStringFormat = 'H:i:s d-m-Y';

    /**
     * Returns date formatted according to given format.
     * @param string $format
     * @return string
     * @link http://php.net/manual/en/datetime.format.php
     */
    public function format($format)
    {
        if(self::$jalali == false){
            // dd(self::$jalali);
        }
        if(self::$jalali === true){
            return $this->eDateTime->date($format,$this->getTimeStamp());
        }
        return parent::format($format);
    }

    /**
     * Format the instance with day, date and time
     *
     * @return string
     */
    public function toDayDateTimeString()
    {
        return $this->format('D, j F, Y g:i A');
    }
}
