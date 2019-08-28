<?php

namespace CaliforniaMountainSnake\InlineCalendar;

class CalendarConfig
{
    use CalendarTimeCalculationUtils;
    use CalendarDateTimeUtils;

    /**
     * @var string
     */
    protected $calendarName;

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
     * CalendarConfig constructor.
     *
     * @param string $calendarName
     * @param int[]  $minimumDate [year, month, day].
     * @param int[]  $maximumDate [year, month, day].
     * @param int[]  $defaultDate [year, month, day].
     */
    public function __construct(string $calendarName, array $minimumDate, array $maximumDate, array $defaultDate)
    {
        $this->calendarName = $calendarName;
        $this->minimumDate = $minimumDate;
        $this->maximumDate = $maximumDate;
        $this->defaultDate = $defaultDate;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return \DateTime
     */
    public function getMinimumDateTime(): \DateTime
    {
        [$minYear, $minMonth, $minDay] = $this->getMinimumDate();
        return $this->createDateTimeFromDate($minYear, $minMonth, $minDay);
    }

    /**
     * @return \DateTime
     */
    public function getMaximumDateTime(): \DateTime
    {
        [$maxYear, $maxMonth, $maxDay] = $this->getMaximumDate();
        return $this->createDateTimeFromDate($maxYear, $maxMonth, $maxDay);
    }

    /**
     * @return \DateTime
     */
    public function getDefaultDateTime(): \DateTime
    {
        [$defaultYear, $defaultMonth, $defaultDay] = $this->getDefaultDate();
        return $this->createDateTimeFromDate($defaultYear, $defaultMonth, $defaultDay);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Получить все доступные для выбора годы.
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
     * @return string
     */
    public function getCalendarName(): string
    {
        return $this->calendarName;
    }

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
}
