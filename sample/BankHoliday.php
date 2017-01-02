<?php

/*
 * The MIT License
 *
 * Copyright 2016 Daniel Popiniuc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace danielgp\bank_holidays;

/**
 * Description of BankHoliday
 *
 * @author Daniel Popiniuc
 */
class BankHoliday
{

    use Romanian;

    public function __construct()
    {
        $refDate          = new \DateTime('now');
        $thisYearHolidays = $this->setHolidays($refDate);
        echo '<h1>For ' . $refDate->format('Y') . ' the Romanian bank holidays are:</h1>'
        . '<ul>';
        foreach ($thisYearHolidays as $value) {
            echo '<li>' . $value . ' --- ' . date('l, d F Y', $value) . '</li>';
        }
        echo '</ul>';
        echo '<h1>For ' . $refDate->format('Y') . ' the Romanian working days month by month are:</h1>'
        . '<ul>';
        $wkDaysInMonth = [];
        for ($crtMonth = 1; $crtMonth <= 12; $crtMonth++) {
            $crtDate = \DateTime::createFromFormat('Y-n-j', $refDate->format('Y') . '-' . $crtMonth . '-1');
            if ($crtDate !== false) {
                $wkDaysInMonth[] = $this->setWorkingDaysInMonth($crtDate);
                echo '<li>' . $crtDate->format('M Y') . ' = '
                . $this->setWorkingDaysInMonth($crtDate) . ' working days</li>';
            }
        }
        echo '</ul>';
        echo '<p>Total # of working days in ' . $refDate->format('Y') . ' is '
        . array_sum($wkDaysInMonth) . ' days</p>';
        echo '<p>Average working days for ' . $refDate->format('Y') . ' is '
        . round((array_sum($wkDaysInMonth) / count($wkDaysInMonth)), 2) . ' days</p>';
    }
}
