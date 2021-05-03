<?php

namespace CaliforniaMountainSnake\InlineCalendar;

use DateTime;
use DateTimeZone;

class CalendarConfig
{
    use InlineCalendarLogger;
    use CalendarTimeCalculationUtils;
    use CalendarDateTimeUtils;

    /**
     * @var int[] [year, month, day].
     */
    protected $minimumDate;

    /**
     * @var int[] [year, month, day].
     */
    protected $maximumDate;

    /**
     * @var int[] [year, month, day].
     */
    protected $defaultDate;

    /**
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * CalendarConfig constructor.
     *
     * @param int[]             $minimumDate [year, month, day].
     * @param int[]             $maximumDate [year, month, day].
     * @param int[]             $defaultDate [year, month, day].
     * @param DateTimeZone|null $timezone    Target timezone. If null passed, the default server timezone will be used.
     */
    public function __construct(
        array $minimumDate,
        array $maximumDate,
        array $defaultDate,
        DateTimeZone $timezone = null
    ) {
        $this->minimumDate = $minimumDate;
        $this->maximumDate = $maximumDate;
        $this->defaultDate = $defaultDate;
        $this->timezone = $timezone ?? (new DateTime())->getTimezone();
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return DateTime
     */
    public function getMinimumDateTime(): DateTime
    {
        [$minYear, $minMonth, $minDay] = $this->getMinimumDate();
        return $this->createDateTimeFromDate($minYear, $minMonth, $minDay, $this->timezone);
    }

    /**
     * @return DateTime
     */
    public function getMaximumDateTime(): DateTime
    {
        [$maxYear, $maxMonth, $maxDay] = $this->getMaximumDate();
        return $this->createDateTimeFromDate($maxYear, $maxMonth, $maxDay, $this->timezone);
    }

    /**
     * @return DateTime
     */
    public function getDefaultDateTime(): DateTime
    {
        [$defaultYear, $defaultMonth, $defaultDay] = $this->getDefaultDate();
        return $this->createDateTimeFromDate($defaultYear, $defaultMonth, $defaultDay, $this->timezone);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Get all years that are available for picking.
     *
     * @return int[]
     */
    public function getAvailableYears(): array
    {
        $minYear = $this->getMinimumDate()[0];
        $maxYear = $this->getMaximumDate()[0];

        $result = [];
        for ($i = $minYear; $i <= $maxYear; $i++) {
            $result[] = $i;
        }
        return $result;
    }

    /**
     * @return string[]
     */
    public function getAvailableMonths(): array
    {
        return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    }

    /**
     * @param int $_year
     * @param int $_month
     *
     * @return array
     */
    public function getAvailableDays(int $_year, int $_month): array
    {
        $result = [];
        $countOfDays = $this->getCountOfDaysInMonth($_year, $_month);
        for ($i = 1; $i <= $countOfDays; $i++) {
            $result[] = $i;
        }
        return $result;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return int[] [year, month, day].
     */
    public function getMinimumDate(): array
    {
        return $this->minimumDate;
    }

    /**
     * @return int[] [year, month, day].
     */
    public function getMaximumDate(): array
    {
        return $this->maximumDate;
    }

    /**
     * @return int[] [year, month, day].
     */
    public function getDefaultDate(): array
    {
        return $this->defaultDate;
    }

    /**
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->timezone;
    }
}
