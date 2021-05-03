<?php

namespace Tests\Unit;

use CaliforniaMountainSnake\InlineCalendar\CalendarDateTimeUtils;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class CalendarDateTimeUtilsTest extends TestCase
{
    use CalendarDateTimeUtils;

    public const TARGET_TIMEZONE = 'Europe/Moscow';

    /**
     * @var DateTimeZone
     */
    private $timezone;

    public function setUp()
    {
        parent::setUp();
        $this->timezone = new DateTimeZone(self::TARGET_TIMEZONE);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @covers CalendarDateTimeUtils::createDateTimeFromDate
     */
    public function testCreateDateTimeFromDate(): void
    {
        $dateTime1 = $this->createDateTimeFromDate(2020, 1, 15, $this->timezone);
        $this->assertEquals(1579035600, $dateTime1->getTimestamp());

        $dateTime2 = $this->createDateTimeFromDate(2020, 1, 15, $this->timezone, false);
        $this->assertEquals(1579121999, $dateTime2->getTimestamp());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @covers CalendarDateTimeUtils::createDateTimeFromDate
     * @covers CalendarDateTimeUtils::createDateFromDateTime
     */
    public function testCreateDateFromDateTime(): void
    {
        $dateTime = $this->createDateTimeFromDate(2020, 1, 15, $this->timezone);
        $convertedDate = $this->createDateFromDateTime($dateTime);

        $this->assertEquals([2020, 1, 15], $convertedDate);
    }
}
