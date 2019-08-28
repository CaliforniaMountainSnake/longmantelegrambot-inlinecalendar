<?php

namespace CaliforniaMountainSnake\UtilTraits;

trait CalendarLangUtils
{
    /**
     * The date format in wrong date message.
     *
     * @see https://www.php.net/manual/ru/function.date.php
     * @return string
     */
    abstract public function calendarLangWrongDateFormat(): string;

    /**
     * The wrong date message.
     *
     * @param string $_min_date Formatted minimum available date.
     * @param string $_max_date Formatted maximum available date.
     *
     * @see CalendarLangUtils::calendarLangWrongDateFormat()
     * @return string
     */
    abstract public function calendarLangWrongDate(string $_min_date, string $_max_date): string;

    /**
     * The message when user inputs wrong value.
     *
     * @return string
     */
    abstract public function calendarLangWrongValue(): string;
}
