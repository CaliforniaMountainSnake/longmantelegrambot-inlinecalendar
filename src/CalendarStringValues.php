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
    private function getCalendarBlankString(): string
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
    private function getNoteNameCalendarTempNotes(): string
    {
        return 'inline_calendar_temp_notes';
    }

    /**
     * @return string
     */
    private function getNoteNameCalendarMsgId(): string
    {
        return $this->getNoteNameCalendarTempNotes() . '_msg_id';
    }

    /**
     * @return string
     */
    private function getNoteNameState(): string
    {
        return 'state';
    }

    /**
     * @return string
     */
    private function getNoteNameSelectedYear(): string
    {
        return 'selected_year';
    }

    /**
     * @return string
     */
    private function getNoteNameSelectedMonth(): string
    {
        return 'selected_month';
    }

    /**
     * @return string
     */
    private function getNoteNameSelectedDay(): string
    {
        return 'selected_day';
    }

    /**
     * @return string
     */
    private function getNoteNameIsFirstCalendarLaunch(): string
    {
        return 'is_first_calendar_launch';
    }
}
