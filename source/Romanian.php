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

    /**
     *
     * @param int $year
     * @return type
     */
    private function getEasterDatetime($year) {
        $base = new \DateTime("$year-03-21");
        $days = easter_days($year);
        return $base->add(new \DateInterval("P{$days}D"));
    }

    /**
     * returns an array with non-standard holidays from a JSON file
     *
     * @param string $fileBaseName
     * @return mixed
     */
    private function readTypeFromJsonFile($fileBaseName) {
        $fName       = __DIR__ . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . $fileBaseName . '.min.json';
        $fJson       = fopen($fName, 'r');
        $jSonContent = fread($fJson, filesize($fName));
        fclose($fJson);
        return json_decode($jSonContent, true);
    }

    /**
     * List of legal holidays
     *
     * @param \DateTime $lngDate
     * @param boolean $inclCatholicEaster
     * @return array
     */
    protected function setHolidays(\DateTime $lngDate, $inclCatholicEaster = false, $inclWorkingHolidays = false) {
        $givenYear = $lngDate->format('Y');
        $daying    = array_merge($this->setHolidaysOrthodoxEaster($lngDate), $this->setHolidaysFixed($lngDate));
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
     * @param \DateTime $lngDate
     * @return array
     */
    private function setHolidaysFixed(\DateTime $lngDate) {
        $givenYear = $lngDate->format('Y');
        $daying    = [
            mktime(0, 0, 0, 1, 1, $givenYear), // Happy New Year
            mktime(0, 0, 0, 1, 2, $givenYear), // recovering from New Year party
            mktime(0, 0, 0, 5, 1, $givenYear), // May 1st
            mktime(0, 0, 0, 12, 1, $givenYear), // Romanian National Day
            mktime(0, 0, 0, 12, 25, $givenYear), // Christmas Day
            mktime(0, 0, 0, 12, 26, $givenYear), // Christmas 2nd Day
        ];
        if ($givenYear >= 2009) {
            $daying[] = mktime(0, 0, 0, 8, 15, $givenYear); // St. Marry
        }
        if ($givenYear >= 2012) {
            $daying[] = mktime(0, 0, 0, 11, 30, $givenYear); // St. Andrew
        }
        sort($daying);
        return $daying;
    }

    /**
     * List of Romanian fixed holidays that are still working (weird ones)
     * (where fixed means every single year occur on same day of the month)
     *
     * @param \DateTime $lngDate
     * @return array
     */
    private function setHolidaysFixedButWorking(\DateTime $lngDate) {
        $daying    = [];
        $givenYear = $lngDate->format('Y');
        if ($givenYear >= 2015) {
            $daying[] = mktime(0, 0, 0, 1, 24, $givenYear); // Unirea Principatelor Romane
        }
        if ($givenYear >= 2016) {
            $daying[] = mktime(0, 0, 0, 2, 19, $givenYear); // Constantin Brancusi birthday
        }
        return $daying;
    }

    /**
     * List of all Orthodox holidays and Pentecost
     *
     * @param \DateTime $lngDate
     * @return array
     */
    private function setHolidaysOrthodoxEaster(\DateTime $lngDate) {
        $givenYear      = $lngDate->format('Y');
        $daying         = [];
        $statmentsArray = $this->readTypeFromJsonFile('RomanianBankHolidays');
        if (array_key_exists($givenYear, $statmentsArray)) {
            foreach ($statmentsArray[$givenYear] as $value) {
                $daying[] = strtotime($value);
            }
        }
        return $daying;
    }

    /**
     * returns bank holidays in a given month
     *
     * @param \DateTime $lngDate
     * @param boolean $inclCatholicEaster
     * @return int
     */
    protected function setHolidaysInMonth(\DateTime $lngDate, $inclCatholicEaster = false) {
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

    /**
     * return an array with all days within a month from a given date
     *
     * @param \DateTime $lngDate
     * @return array
     */
    protected function setMonthAllDaysIntoArray(\DateTime $lngDate) {
        $firstDayGivenMonth  = strtotime($lngDate->modify('first day of this month')->format('Y-m-d'));
        $lastDayInGivenMonth = strtotime($lngDate->modify('last day of this month')->format('Y-m-d'));
        $secondsInOneDay     = 24 * 60 * 60;
        return range($firstDayGivenMonth, $lastDayInGivenMonth, $secondsInOneDay);
    }

    /**
     * returns working days in a given month
     *
     * @param \DateTime $lngDate
     * @param boolean $inclCatholicEaster
     * @return int
     */
    protected function setWorkingDaysInMonth(\DateTime $lngDate, $inclCatholicEaster = false) {
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
