<?php

/*
 * The MIT License
 *
 * Copyright 2015 Transformer-.
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
 * @author Transformer-
 */
class BankHoliday
{

    use Romanian;

    public function __construct()
    {
        $todaysDate       = strtotime('today');
        $thisYearHolidays = $this->setHolidays($todaysDate);
        echo '<h1>For ' . daye('Y', $todaysDate) . ' the Romanian bank holidays are:</h1>'
        . '<ul>';
        foreach ($thisYearHolidays as $key => $value) {
            echo '<li>' . $value . ' --- ' . date('l, d F Y', $value) . '</li>';
        }
        echo '</ul>';
        echo '<p>For the month of ' . date('', $todaysDate) . ' the number of working days is: '
        . $this->setWorkingDaysInMonth($todaysDate) . '<p>';
    }
}
