<?php

namespace CaliforniaMountainSnake\InlineCalendar;

use CaliforniaMountainSnake\LongmanTelegrambotUtils\ConversationUtils;
use CaliforniaMountainSnake\LongmanTelegrambotUtils\SendUtils;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @TODO: выделять не сегодняшнюю дату, а дефолтную.
 *
 * Inline-календарь для выбора даты.
 */
trait InlineCalendar
{
    use InlineCalendarLogger;
    use CalendarTimeCalculationUtils;
    use CalendarTimeUnitNames;
    use CalendarDateTimeUtils;
    use CalendarStringValues;
    use CalendarNotesUtils;
    use CalendarKeyboardsUtils;
    use CalendarLangUtils;
    use SendUtils;
    use ConversationUtils;

    /**
     * @return string
     */
    abstract public static function getCommandName(): string;

    /**
     * Select the date using inline calendar.
     *
     * @param CalendarConfig $_config                 The object contains calendar configs.
     * @param string         $_message_text           The text that will be shown to user.
     * @param Message        $_user_message           User's message telegram object.
     * @param callable       $_result_callback        The callback in which will be passed the results
     *                                                of date selection as an array parameter: [year, month, day].
     *
     * @return null|mixed Return value of the result callback or null if a selection is still not completed.
     * @throws TelegramException
     */
    public function selectDate(
        CalendarConfig $_config,
        string $_message_text,
        Message $_user_message,
        callable $_result_callback
    ) {
        $text = $_user_message->getText(true) ?? '';
        $state = $this->getCalendarState($_config);

        switch ($state) {
            case $this->getStateYear():
                $isFinished = $this->processCommandSelectYear($_config, $_message_text, $text);
                break;

            case $this->getStateMonth():
                $isFinished = $this->processCommandSelectMonth($_config, $_message_text, $text);
                break;

            default:
            case $this->getStateDayOfMonth():
                $isFinished = $this->stateSelectDayOfMonth($_config, $_message_text, $text, true);
                break;
        }
        if ($isFinished === false) {
            return null;
        }

        return $_result_callback($this->getFinalCalendarResult($_config));
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * The main calendar. Selection of day of month.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     * @param bool           $_is_process_text
     *
     * @return bool
     * @throws TelegramException
     */
    protected function stateSelectDayOfMonth(
        CalendarConfig $_config,
        string $_message_text,
        string $_text,
        bool $_is_process_text
    ): bool {
        // Get params.
        $isFirstLaunch = $this->isFirstCalendarLaunch($_config);
        [$year, $month, $day] = $this->getCalendarDate($_config);
        $errors = [];

        // Just show keyboard if we don't need to process text.
        if ($isFirstLaunch || !$_is_process_text) {
            return $this->showDaysOfMonthMessage($year, $month, $_config, $_message_text, $errors);
        }

        // Process text.
        if ($this->isBlankString($_text)) {
            // Если нажали пустую ячейку.
            return $this->stateSelectDayOfMonth($_config, $_message_text, $_text, false);
        }
        if ($_text === $this->getCommandSelectYear()) {
            // Запустить state выбора года.
            return $this->processCommandSelectYear($_config, $_message_text, $_text);
        }
        if ($_text === $this->getCommandSelectMonth()) {
            // Запустить state выбора месяца.
            return $this->processCommandSelectMonth($_config, $_message_text, $_text);
        }
        if ($_text === $this->getCommandMoveCalendarLeft() || $_text === $this->getCommandMoveCalendarRight()) {
            // Сдвинуть календарь на месяц вперед/назад.
            return $this->processCommandMoveCalendar($_config, $_message_text, $_text);
        }

        // Process days of month.
        return $this->processDayOfMonthSelection($_config, $_message_text, $_text);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Обработать команды сдвига календаря на месяц в сторону.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     *
     * @return bool
     * @throws TelegramException
     */
    protected function processCommandMoveCalendar(CalendarConfig $_config, string $_message_text, string $_text): bool
    {
        $isRight = $_text === $this->getCommandMoveCalendarRight();
        [$year, $month, $day] = $this->getCalendarDate($_config);
        [$newMonth, $newYear] = $this->moveMonth($_config, $isRight);
        $errors = [];

        // Validation.
        $this->validateCalendarDate($errors, $_config, $newYear, $newMonth, $day);

        // Update date.
        if (empty($errors)) {
            $year = $newYear;
            $month = $newMonth;
            $this->updateCalendarDate($_config, $year, $month, null);
        }

        // Show message.
        return $this->showDaysOfMonthMessage($year, $month, $_config, $_message_text, $errors);
    }

    /**
     * Обработать текст с выбранным днем месяца от юзера.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     *
     * @return bool
     * @throws TelegramException
     */
    protected function processDayOfMonthSelection(CalendarConfig $_config, string $_message_text, string $_text): bool
    {
        [$year, $month, $day] = $this->getCalendarDate($_config);
        $selectedDay = (int)$_text;
        $errors = [];

        // Validation.
        $this->validateValues($errors, $_config->getAvailableDays($year, $month), $_text);
        $this->validateCalendarDate($errors, $_config, $year, $month, $selectedDay);

        // Update day.
        if (empty($errors)) {
            $this->updateCalendarDate($_config, null, null, $selectedDay);
            return true;
        }

        // Show message.
        return $this->showDaysOfMonthMessage($year, $month, $_config, $_message_text, $errors);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Выбор года.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     *
     * @return bool
     * @throws TelegramException
     */
    protected function processCommandSelectYear(CalendarConfig $_config, string $_message_text, string $_text): bool
    {
        [$year, $month, $day] = $this->getCalendarDate($_config);
        return $this->stateSelectOneValue(
            $_config,
            $_message_text,
            $_text,
            $this->getStateYear(),
            $this->createYearsKeyboard($_config),
            function (string $_text) use ($_config, $month, $day): array {
                // validation.
                $errors = [];
                $this->validateValues($errors, $_config->getAvailableYears(), $_text);
                $this->validateCalendarDate($errors, $_config, (int)$_text, $month, $day);
                return $errors;
            },
            function (string $text) use ($_config, $_message_text, $_text): bool {
                // success.
                $this->updateCalendarDate($_config, (int)$text, null, null);
                return $this->stateSelectDayOfMonth($_config, $_message_text, $_text, false);
            });
    }

    /**
     * Выбор месяца.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     *
     * @return bool
     * @throws TelegramException
     */
    protected function processCommandSelectMonth(CalendarConfig $_config, string $_message_text, string $_text): bool
    {
        [$year, $month, $day] = $this->getCalendarDate($_config);
        return $this->stateSelectOneValue(
            $_config,
            $_message_text,
            $_text,
            $this->getStateMonth(),
            $this->createMonthsKeyboard(),
            function (string $_text) use ($_config, $year, $day): array {
                // validation.
                $errors = [];
                $this->validateValues($errors, $_config->getAvailableMonths(), $_text);
                $this->validateCalendarDate($errors, $_config, $year, (int)$_text, $day);
                return $errors;
            },
            function (string $text) use ($_config, $_message_text, $_text): bool {
                // success.
                $this->updateCalendarDate($_config, null, (int)$text, null);
                return $this->stateSelectDayOfMonth($_config, $_message_text, $_text, false);
            });
    }

    /**
     * Выбрать одно значение с клавиатуры и вернуться к календарю выбора дня месяца.
     *
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param string         $_text
     * @param string         $_state
     * @param Keyboard       $_keyboard
     * @param callable       $_validation_callback
     * @param callable       $_success_callback
     *
     * @return bool
     * @throws TelegramException
     */
    protected function stateSelectOneValue(
        CalendarConfig $_config,
        string $_message_text,
        string $_text,
        string $_state,
        Keyboard $_keyboard,
        callable $_validation_callback,
        callable $_success_callback
    ): bool {
        // Get params.
        $currentState = $this->getCalendarState($_config);
        $errors = null;

        if ($currentState === $_state) {
            // validation.
            $errors = $_validation_callback ($_text);

            if (empty($errors)) {
                // Update value.
                return $_success_callback($_text);
            }
        }

        // Update state.
        $this->setCalendarState($_config, $_state);

        // Show message.
        $this->showCalendarMessage($_config, $_message_text, $errors, $_keyboard);
        return false;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param array|null     $_errors
     * @param Keyboard|null  $_keyboard
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function showCalendarMessage(
        CalendarConfig $_config,
        string $_message_text,
        ?array $_errors = null,
        Keyboard $_keyboard = null
    ): ServerResponse {
        $this->setCalendarNotes($_config, [$this->getNoteNameIsFirstCalendarLaunch() => false]);
        return $this->showTextMessage($this->getNoteNameCalendarMsgId(), $_message_text, $_errors,
            $_keyboard);
    }

    /**
     * Показать сообщение выбора дня месяца.
     *
     * @param int            $_year
     * @param int            $_month
     * @param CalendarConfig $_config
     * @param string         $_message_text
     * @param array|null     $_errors
     *
     * @return bool
     * @throws TelegramException
     */
    protected function showDaysOfMonthMessage(
        int $_year,
        int $_month,
        CalendarConfig $_config,
        string $_message_text,
        ?array $_errors = null
    ): bool {
        // Update state.
        $this->setCalendarState($_config, $this->getStateDayOfMonth());

        // Show message.
        $daysOfMonthKeyboard = $this->createDaysOfMonthKeyboard($_year, $_month, $_config);
        $this->showCalendarMessage($_config, $_message_text, $_errors, $daysOfMonthKeyboard);
        return false;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Провалидировать дату. Проверить, не выходит ли она за заданные пределы.
     *
     * @param array          $_errors
     * @param CalendarConfig $_config
     * @param int            $_year
     * @param int            $_month
     * @param int            $_day
     *
     * @return bool
     */
    private function validateCalendarDate(
        array &$_errors,
        CalendarConfig $_config,
        int $_year,
        int $_month,
        int $_day
    ): bool {
        $format = $this->calendarLangWrongDateFormat();
        $minDate = $_config->getMinimumDateTime();
        $maxDate = $_config->getMaximumDateTime();
        $targetDate = $this->createDateTimeFromDate($_year, $_month, $_day);

        if ($targetDate < $minDate || $targetDate > $maxDate) {
            $_errors[] = $this->calendarLangWrongDate($minDate->format($format), $maxDate->format($format));
            return false;
        }

        return true;
    }

    /**
     * Проверить, содержится ли значение в массиве допустимых значений.
     *
     * @param array  $_errors
     * @param array  $_available_values
     * @param string $_value
     *
     * @return bool
     */
    private function validateValues(array &$_errors, array $_available_values, string $_value): bool
    {
        if (!\in_array($_value, $_available_values, false)) {
            $_errors[] = $this->calendarLangWrongValue();
            return false;
        }
        return true;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Сдвинуть месяц на 1 единицу влево или вправо.
     *
     * @param CalendarConfig $_config
     * @param bool           $_is_right
     *
     * @return array [new_month_value, new_year_value].
     * @throws TelegramException
     */
    private function moveMonth(CalendarConfig $_config, bool $_is_right): array
    {
        [$year, $month, $day] = $this->getCalendarDate($_config);

        $_is_right && ++$month;
        !$_is_right && --$month;

        if ($month < 1) {
            $month = 12;
            --$year;
        }
        if ($month > 12) {
            $month = 1;
            ++$year;
        }

        return [$month, $year];
    }

    /**
     * Начинается ли текст с пустой строки?
     *
     * @param string $_text
     *
     * @return bool
     */
    private function isBlankString(string $_text): bool
    {
        $blank = $this->getCalendarBlankString();
        return \strpos($_text, $blank) === 0;
    }
}
