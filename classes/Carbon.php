<?php
/**
 * Created by PhpStorm.
 * User: Sajjad
 * Date: 7/24/2015
 * Time: 2:22 AM
 */

namespace RtlWeb\Persian\Classes;
use \Carbon\Carbon as baseCarbon;

class Carbon extends baseCarbon
{
    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = 'H:i:s d-m-Y';

    /**
     * Returns date formatted according to given format.
     * @param string $format
     * @return string
     * @link http://php.net/manual/en/datetime.format.php
     */
    public function format($format)
    {
        return jDateTime::date($format,$this->getTimestamp());
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