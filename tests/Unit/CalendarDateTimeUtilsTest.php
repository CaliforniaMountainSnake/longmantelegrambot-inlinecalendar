<?php

namespace Tests\Unit;

use CaliforniaMountainSnake\InlineCalendar\CalendarDateTimeUtils;
use PHPUnit\Framework\TestCase;

class CalendarDateTimeUtilsTest extends TestCase
{
    use CalendarDateTimeUtils;

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @covers CalendarDateTimeUtils::createDateTimeFromDate
     */
    public function testCreateDateTimeFromDate(): void
    {
        $dateTime = $this->createDateTimeFromDate(2020, 1, 15);
        $this->assertEquals(1579046400, $dateTime->getTimestamp());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @covers CalendarDateTimeUtils::createDateTimeFromDate
     * @covers CalendarDateTimeUtils::createDateFromDateTime
     */
    public function testCreateDateFromDateTime(): void
    {
        $initDate = [2020, 1, 15];
        $dateTime = $this->createDateTimeFromDate(...$initDate);
        $convertedDate = $this->createDateFromDateTime($dateTime);

        $this->assertEquals($initDate, $convertedDate);
    }
}
