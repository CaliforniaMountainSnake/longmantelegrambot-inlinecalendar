<?php

namespace CaliforniaMountainSnake\InlineCalendar;

trait CalendarTimeUnitNames
{
    /**
     * Get short names of days of week.
     *
     * @return array The array indexed from 1 to 7. [1 => 'mon', ..., 7 => 'sun']
     */
    abstract public function getNamesOfDaysOfWeekArrayShort(): array;

    /**
     * Get full names of days of week.
     *
     * @return array The array indexed from 1 to 7. [1 => 'monday', ..., 7 => 'sunday']
     */
    abstract public function getNamesOfDaysOfWeekArrayLong(): array;

    /**
     * Get short names of months.
     *
     * @return array The array indexed from 1 to 12. [1 => 'jan', ..., 12 => 'dec']
     */
    abstract public function getNamesOfMonthsShort(): array;

    /**
     * Get full names of months.
     *
     * @return array The array indexed from 1 to 12. [1 => 'january', ..., 12 => 'december']
     */
    abstract public function getNamesOfMonthsLong(): array;

    /**
     * @param int $_day
     *
     * @return string
     */
    public function getNameOfDayOfWeekByIntShort(int $_day): string
    {
        $day = ($_day === 0 ? 7 : $_day);
        return $this->getNamesOfDaysOfWeekArrayShort()[$day];
    }

    /**
     * @param int $_day
     *
     * @return string
     */
    public function getNameOfDayOfWeekByIntLong(int $_day): string
    {
        $day = ($_day === 0 ? 7 : $_day);
        return $this->getNamesOfDaysOfWeekArrayLong()[$day];
    }

    /**
     * @param int $_month
     *
     * @return string
     */
    public function getNameOfMonthByIntShort(int $_month): string
    {
        return $this->getNamesOfMonthsShort()[$_month];
    }

    /**
     * @param int $_month
     *
     * @return string
     */
    public function getNameOfMonthByIntLong(int $_month): string
    {
        return $this->getNamesOfMonthsLong()[$_month];
    }
}
