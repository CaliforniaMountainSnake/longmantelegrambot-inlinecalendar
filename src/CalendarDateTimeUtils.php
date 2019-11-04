<?php

namespace CaliforniaMountainSnake\InlineCalendar;

trait CalendarDateTimeUtils
{
    /**
     * @return int[] [year, month, day].
     */
    public function getTodayDate(): array
    {
        return [(int)\date('Y'), (int)\date('n'), (int)\date('j')];
    }

    /**
     * @return \DateTime
     */
    public function getTodayDateTime(): \DateTime
    {
        [$todayYear, $todayMonth, $todayDay] = $this->getTodayDate();
        return $this->createDateTimeFromDate($todayYear, $todayMonth, $todayDay);
    }

    /**
     * @param int $_year
     * @param int $_month
     * @param int $_day
     *
     * @return \DateTime
     */
    public function createDateTimeFromDate(int $_year, int $_month, int $_day): \DateTime
    {
        return \DateTime::createFromFormat('Y.n.j', $_year . '.' . $_month . '.' . $_day);
    }
}
