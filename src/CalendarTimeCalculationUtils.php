<?php

namespace CaliforniaMountainSnake\InlineCalendar;

/**
 * The mathematics functions intended to calculation dates.
 */
trait CalendarTimeCalculationUtils
{
    /**
     * Is it the leap year?
     *
     * @param int $_year
     *
     * @see https://stackoverflow.com/a/11595914/10452175
     * @return bool
     */
    public function isLeapYear(int $_year): bool
    {
        return (($_year & 3) === 0 && (($_year % 25) !== 0 || ($_year & 15) === 0));
    }

    /**
     * Get day of week by date.
     * 0 - sunday.
     * 1 - monday.
     *
     * @param int $_year
     * @param int $_month
     * @param int $_day
     *
     * @see https://en.wikipedia.org/wiki/Determination_of_the_day_of_the_week#Sakamoto's_methods
     * @return int
     */
    public function getDayOfWeekByDate(int $_year, int $_month, int $_day): int
    {
        $t = [0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4];
        $_year -= (int)($_month < 3);
        return ($_year + (int)($_year / 4) - (int)($_year / 100) + (int)($_year / 400) + $t[$_month - 1] + $_day) % 7;
    }

    /**
     * Get count of days in month.
     *
     * @param int $_year
     * @param int $_month
     *
     * @return int
     */
    public function getCountOfDaysInMonth(int $_year, int $_month): int
    {
        if ($_month === 2) {
            return ($this->isLeapYear($_year) ? 29 : 28);
        }

        $isFull = \in_array($_month, [1, 3, 5, 7, 8, 10, 12], true);
        return ($isFull ? 31 : 30);
    }

    /**
     * Get all days of month and its days of week.
     *
     * @param int $_year
     * @param int $_month
     *
     * @return int[] [day_number => day_of_week (0 - sunday, 1 - monday)].
     */
    public function getAllDaysOfMonth(int $_year, int $_month): array
    {
        $result = [];
        $daysCount = $this->getCountOfDaysInMonth($_year, $_month);
        for ($day = 1; $day <= $daysCount; $day++) {
            $result[$day] = $this->getDayOfWeekByDate($_year, $_month, $day);
        }

        return $result;
    }
}
