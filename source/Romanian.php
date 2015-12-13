<?php

/**
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Daniel Popiniuc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace danielgp\bank_holidays;

/**
 * Return a list of all Romanian Holidays between 2001 and 2020
 *
 * @author Daniel Popiniuc
 */
trait Romanian
{

    private function getEasterDatetime($year)
    {
        $base = new \DateTime("$year-03-21");
        $days = easter_days($year);
        return $base->add(new \DateInterval("P{$days}D"));
    }

    /**
     * List of legal holidays
     *
     * @param date $lngDate
     * @param boolean $inclCatholicEaster
     * @return array
     */
    protected function setHolidays($lngDate, $inclCatholicEaster = false, $inclWorkingHolidays = false)
    {
        $givenYear = date('Y', $lngDate);
        $daying    = $this->setHolidaysEasterBetween2001and2020($lngDate);
        $daying    = array_merge($daying, $this->setHolidaysFixed($lngDate));
        if ($inclWorkingHolidays) {
            $daying = array_merge($daying, $this->setHolidaysFixedButWorking($lngDate));
        }
        if ($inclCatholicEaster) { // Catholic easter is already known by PHP
            $firstEasterDate  = strtotime($this->getEasterDatetime($givenYear)->format('Y-m-d'));
            $secondEasterDate = strtotime('+1 day', $firstEasterDate);
            $daying           = array_merge($daying, [
                $firstEasterDate,
                $secondEasterDate,
            ]);
        }
        sort($daying);
        return array_unique($daying); // remove duplicate for when catholic and orthodox easter match
    }

    /**
     * List of all Romanian fixed holidays
     * (where fixed means every single year occur on same day of the month)
     *
     * @param date $lngDate
     * @return array
     */
    private function setHolidaysFixed($lngDate)
    {
        $givenYear = date('Y', $lngDate);
        $daying[]  = mktime(0, 0, 0, 1, 1, $givenYear); // Happy New Year
        $daying[]  = mktime(0, 0, 0, 1, 2, $givenYear); // recovering from New Year party
        $daying[]  = mktime(0, 0, 0, 5, 1, $givenYear); // May 1st
        if ($givenYear >= 2009) {
            $daying[] = mktime(0, 0, 0, 8, 15, $givenYear); // St. Marry
        }
        if ($givenYear >= 2012) {
            $daying[] = mktime(0, 0, 0, 11, 30, $givenYear); // St. Andrew
        }
        $daying[] = mktime(0, 0, 0, 12, 1, $givenYear); // Romanian National Day
        $daying[] = mktime(0, 0, 0, 12, 25, $givenYear); // December 25th
        $daying[] = mktime(0, 0, 0, 12, 26, $givenYear); // December 26th
        return $daying;
    }

    /**
     * List of Romanian fixed holidays that are still working (weird ones)
     * (where fixed means every single year occur on same day of the month)
     *
     * @param date $lngDate
     * @return array
     */
    private function setHolidaysFixedButWorking($lngDate)
    {
        $daying    = [];
        $givenYear = date('Y', $lngDate);
        if ($givenYear >= 2015) {
            $daying[] = mktime(0, 0, 0, 1, 24, $givenYear); // Unirea Principatelor Romane
        }
        if ($givenYear >= 2016) {
            $daying[] = mktime(0, 0, 0, 2, 19, $givenYear); // Constantin Brancusi birthday
        }
        return $daying;
    }

    /**
     * List of all Orthodox holidays between 2001 and 2005
     *
     * @param date $lngDate
     * @return array
     */
    private function setHolidaysEasterBetween2001and2020($lngDate)
    {
        $givenYear        = date('Y', $lngDate);
        $variableHolidays = [
            2001 => [
                mktime(0, 0, 0, 4, 2, $givenYear),
                mktime(0, 0, 0, 4, 3, $givenYear),
            ],
            2002 => [
                mktime(0, 0, 0, 4, 22, $givenYear),
                mktime(0, 0, 0, 4, 23, $givenYear),
            ],
            2003 => [
                mktime(0, 0, 0, 4, 20, $givenYear),
                mktime(0, 0, 0, 4, 21, $givenYear),
            ],
            2004 => [
                mktime(0, 0, 0, 3, 10, $givenYear),
                mktime(0, 0, 0, 3, 11, $givenYear),
            ],
            2005 => [
                mktime(0, 0, 0, 5, 1, $givenYear),
                mktime(0, 0, 0, 5, 2, $givenYear),
            ],
            2006 => [
                mktime(0, 0, 0, 4, 23, $givenYear),
                mktime(0, 0, 0, 4, 24, $givenYear),
            ],
            2007 => [
                mktime(0, 0, 0, 4, 8, $givenYear),
                mktime(0, 0, 0, 4, 9, $givenYear),
            ],
            2008 => [
                mktime(0, 0, 0, 4, 27, $givenYear),
                mktime(0, 0, 0, 4, 28, $givenYear),
            ],
            2009 => [
                mktime(0, 0, 0, 4, 19, $givenYear),
                mktime(0, 0, 0, 4, 20, $givenYear),
                mktime(0, 0, 0, 6, 7, $givenYear),
                mktime(0, 0, 0, 6, 8, $givenYear),
            ],
            2010 => [
                mktime(0, 0, 0, 4, 4, $givenYear),
                mktime(0, 0, 0, 4, 5, $givenYear),
                mktime(0, 0, 0, 5, 23, $givenYear),
                mktime(0, 0, 0, 5, 24, $givenYear),
            ],
            2011 => [
                mktime(0, 0, 0, 4, 24, $givenYear),
                mktime(0, 0, 0, 4, 25, $givenYear),
                mktime(0, 0, 0, 6, 12, $givenYear),
                mktime(0, 0, 0, 6, 13, $givenYear),
            ],
            2012 => [
                mktime(0, 0, 0, 4, 15, $givenYear),
                mktime(0, 0, 0, 4, 16, $givenYear),
                mktime(0, 0, 0, 6, 3, $givenYear),
                mktime(0, 0, 0, 6, 4, $givenYear),
            ],
            2013 => [
                mktime(0, 0, 0, 5, 6, $givenYear),
                mktime(0, 0, 0, 5, 6, $givenYear),
                mktime(0, 0, 0, 6, 23, $givenYear),
                mktime(0, 0, 0, 6, 24, $givenYear),
            ],
            2014 => [
                mktime(0, 0, 0, 4, 20, $givenYear),
                mktime(0, 0, 0, 4, 21, $givenYear),
                mktime(0, 0, 0, 6, 8, $givenYear),
                mktime(0, 0, 0, 6, 9, $givenYear),
            ],
            2015 => [
                mktime(0, 0, 0, 4, 12, $givenYear),
                mktime(0, 0, 0, 4, 13, $givenYear),
                mktime(0, 0, 0, 5, 31, $givenYear),
                mktime(0, 0, 0, 6, 1, $givenYear),
            ],
            2016 => [
                mktime(0, 0, 0, 5, 1, $givenYear),
                mktime(0, 0, 0, 5, 2, $givenYear),
                mktime(0, 0, 0, 6, 19, $givenYear),
                mktime(0, 0, 0, 6, 20, $givenYear),
            ],
            2017 => [
                mktime(0, 0, 0, 4, 16, $givenYear),
                mktime(0, 0, 0, 4, 17, $givenYear),
                mktime(0, 0, 0, 6, 4, $givenYear),
                mktime(0, 0, 0, 6, 5, $givenYear),
            ],
            2018 => [
                mktime(0, 0, 0, 4, 8, $givenYear),
                mktime(0, 0, 0, 4, 9, $givenYear),
                mktime(0, 0, 0, 5, 27, $givenYear),
                mktime(0, 0, 0, 5, 28, $givenYear),
            ],
            2019 => [
                mktime(0, 0, 0, 4, 28, $givenYear),
                mktime(0, 0, 0, 4, 29, $givenYear),
                mktime(0, 0, 0, 6, 16, $givenYear),
                mktime(0, 0, 0, 6, 17, $givenYear),
            ],
            2020 => [
                mktime(0, 0, 0, 4, 19, $givenYear),
                mktime(0, 0, 0, 4, 20, $givenYear),
                mktime(0, 0, 0, 6, 7, $givenYear),
                mktime(0, 0, 0, 6, 8, $givenYear),
            ]
        ];
        $daying           = [];
        if (array_key_exists($givenYear, $variableHolidays)) {
            $daying = $variableHolidays[$givenYear];
        }
        return $daying;
    }

    /**
     * returns bank holidays in a given month
     *
     * @param date $lngDate
     * @param boolean $inclCatholicEaster
     * @return int
     */
    protected function setHolidaysInMonth($lngDate, $inclCatholicEaster = false)
    {
        $holidaysInGivenYear = $this->setHolidays($lngDate, $inclCatholicEaster);
        $thisMonthDayArray   = $this->setMonthAllDaysIntoArray($lngDate);
        $holidays            = 0;
        foreach ($thisMonthDayArray as $value) {
            if (in_array($value, $holidaysInGivenYear)) {
                $holidays += 1;
            }
        }
        return $holidays;
    }

    protected function setMonthAllDaysIntoArray($lngDate)
    {
        $firstDayGivenMonth  = strtotime('first day of', $lngDate);
        $lastDayInGivenMonth = strtotime('last day of', $lngDate);
        $secondsInOneDay     = 24 * 60 * 60;
        return range($firstDayGivenMonth, $lastDayInGivenMonth, $secondsInOneDay);
    }

    /**
     * returns working days in a given month
     *
     * @param date $lngDate
     * @param boolean $inclCatholicEaster
     * @return int
     */
    protected function setWorkingDaysInMonth($lngDate, $inclCatholicEaster = false)
    {
        $holidaysInGivenYear = $this->setHolidays($lngDate, $inclCatholicEaster);
        $thisMonthDayArray   = $this->setMonthAllDaysIntoArray($lngDate);
        $workingDays         = 0;
        foreach ($thisMonthDayArray as $value) {
            if (!in_array(strftime('%w', $value), [0, 6]) && !in_array($value, $holidaysInGivenYear)) {
                $workingDays += 1;
            }
        }
        return $workingDays;
    }
}
