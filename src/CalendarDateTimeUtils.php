<?php

namespace CaliforniaMountainSnake\InlineCalendar;

trait CalendarDateTimeUtils
{
    use InlineCalendarLogger;

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
     * Create a DateTime object from given time.
     * Warning! The object will have a 00:00:00 time.
     *
     * @param int $_year
     * @param int $_month
     * @param int $_day
     *
     * @return \DateTime
     */
    public function createDateTimeFromDate(int $_year, int $_month, int $_day): \DateTime
    {
        $obj = \DateTime::createFromFormat('Y.n.j', $_year . '.' . $_month . '.' . $_day);
        $obj->setTime(0, 0, 0, 0);
        return $obj;
    }
}
