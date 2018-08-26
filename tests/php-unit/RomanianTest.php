<?php

/**
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2017 Daniel Popiniuc
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

class RomanianTest extends \PHPUnit\Framework\TestCase
{

    public static function setUpBeforeClass()
    {
        require_once str_replace('tests' . DIRECTORY_SEPARATOR . 'php-unit', 'source', __DIR__)
            . DIRECTORY_SEPARATOR . 'Romanian.php';
    }

    public function testHolidaysEaster2015CatholicEasterFirstDay()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2015-04-05'), $mock->setHolidays(new \DateTime('2015-04-01'), true));
    }

    public function testHolidaysEaster2015CatholicEasterSecondDay()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2015-04-06'), $mock->setHolidays(new \DateTime('2015-04-01'), true));
    }

    public function testHolidaysEaster2015FirstDayOfYear()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2015-01-01'), $mock->setHolidays(new \DateTime('2015-12-01')));
    }

    public function testHolidaysEaster2015LastDayOfYear()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertNotContains(strtotime('2015-12-31'), $mock->setHolidays(new \DateTime('2015-12-01')));
    }

    public function testHolidaysEaster2015OrthodoxEasterFirstDay()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2015-04-12'), $mock->setHolidays(new \DateTime('2015-04-01'), true));
    }

    public function testHolidaysEaster2015OrthodoxEasterSecondDay()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2015-04-13'), $mock->setHolidays(new \DateTime('2015-04-01'), true));
    }

    public function testHolidaysFixedButWorkingIncluded()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2016-02-19'), $mock->setHolidays(new \DateTime('2016-04-01'), true, true));
    }

    public function testHolidaysFixedNewIn2017()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertContains(strtotime('2017-01-24'), $mock->setHolidays(new \DateTime('2017-01-24'), true, true));
    }

    public function testHolidaysInMonthForMonthWithCatholicEaster()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(4, $mock->setHolidaysInMonth(new \DateTime('2015-04-01'), true));
    }

    public function testHolidaysInMonthForMonthWithoutCatholicEaster()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(2, $mock->setHolidaysInMonth(new \DateTime('2015-04-01'), false));
    }

    public function testWorkingDaysInMonthForMonthOf19Days()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(19, $mock->setWorkingDaysInMonth(new \DateTime('2001-12-01')));
    }

    public function testWorkingDaysInMonthForMonthOf20Days()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(20, $mock->setWorkingDaysInMonth(new \DateTime('2001-02-01')));
    }

    public function testWorkingDaysInMonthForMonthOf20DaysWithCatholicEaster()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(20, $mock->setWorkingDaysInMonth(new \DateTime('2015-04-01'), true));
    }

    public function testWorkingDaysInMonthForMonthOf21Days()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(21, $mock->setWorkingDaysInMonth(new \DateTime('2006-01-01')));
    }

    public function testWorkingDaysInMonthForMonthOf22Days()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(22, $mock->setWorkingDaysInMonth(new \DateTime('2016-09-01'), false));
    }

    public function testWorkingDaysInMonthForMonthOf23Days()
    {
        $mock = $this->getMockForTrait(Romanian::class);
        $this->assertEquals(23, $mock->setWorkingDaysInMonth(new \DateTime('2015-07-01')));
    }
}
