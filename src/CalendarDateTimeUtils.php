<?php

namespace CaliforniaMountainSnake\InlineCalendar;

trait CalendarDateTimeUtils
{
    use InlineCalendarLogger;

    /**
     * @return int[] [year, month, day].
     * @throws \Exception
     */
    public function getTodayDate(): array
    {
        return $this->createDateFromDateTime($this->getTodayDateTime());
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getTodayDateTime(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * @param \DateTime $_date_time
     *
     * @return array [year, month, day].
     */
    public function createDateFromDateTime(\DateTime $_date_time): array
    {
        $year = $_date_time->format('Y');
        $month = $_date_time->format('n');
        $day = $_date_time->format('d');

        return [(int)$year, (int)$month, (int)$day];
    }

    /**
     * Create a DateTime object from given time.
     * Warning! The object always will have 00:00:00 time!
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
