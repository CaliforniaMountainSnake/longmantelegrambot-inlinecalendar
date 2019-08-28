<?php

namespace CaliforniaMountainSnake\InlineCalendar;

/**
 * Строковые значения для inline-календаря, чтобы не захламлять основной трейт.
 */
trait CalendarStringValues
{
    /**
     * Символ/строка, который будет показан на клавиатуре для пустых ячеек в таблице месяца.
     *
     * @return string
     */
    private function getBlankString(): string
    {
        return ' ';
    }

    /**
     * Команда сдвига месячного календаря влево.
     *
     * @return string
     */
    private function getCommandMoveCalendarLeft(): string
    {
        return '<';
    }

    /**
     * Команда сдвига месячного календаря вправо.
     *
     * @return string
     */
    private function getCommandMoveCalendarRight(): string
    {
        return '>';
    }

    /**
     * Команда выбора года.
     *
     * @return string
     */
    private function getCommandSelectYear(): string
    {
        return 'year';
    }

    /**
     * Команда выбора месяца.
     *
     * @return string
     */
    private function getCommandSelectMonth(): string
    {
        return 'month';
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    private function getStateDefault(): string
    {
        return $this->getStateDayOfMonth();
    }

    /**
     * @return string
     */
    private function getStateDayOfMonth(): string
    {
        return 'day_of_month';
    }

    /**
     * @return string
     */
    private function getStateMonth(): string
    {
        return 'month';
    }

    /**
     * @return string
     */
    private function getStateYear(): string
    {
        return 'year';
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return string
     */
    private function getNoteState(): string
    {
        return 'state';
    }

    /**
     * @return string
     */
    private function getNoteSelectedYear(): string
    {
        return 'selected_year';
    }

    /**
     * @return string
     */
    private function getNoteSelectedMonth(): string
    {
        return 'selected_month';
    }

    /**
     * @return string
     */
    private function getNoteSelectedDay(): string
    {
        return 'selected_day';
    }

    /**
     * @return string
     */
    private function getNoteIsFirstCalendarLaunch(): string
    {
        return 'is_first_calendar_launch';
    }
}
