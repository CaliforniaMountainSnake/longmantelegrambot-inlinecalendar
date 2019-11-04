<?php

namespace CaliforniaMountainSnake\InlineCalendar;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\InlineButton;
use CaliforniaMountainSnake\UtilTraits\ArrayUtils;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Psr\Log\LoggerInterface;

trait CalendarKeyboardsUtils
{
    use InlineCalendarLogger;
    use CalendarTimeUnitNames;
    use CalendarDateTimeUtils;
    use ArrayUtils;

    /**
     * @return LoggerInterface
     */
    abstract public function getInlineCalendarLogger(): LoggerInterface;

    /**
     * Создать клавиатуру календаря выбора дня месяца.
     *
     * @param int            $_year
     * @param int            $_month
     *
     * @param CalendarConfig $_config
     *
     * @return InlineKeyboard
     */
    public function createDaysOfMonthKeyboard(int $_year, int $_month, CalendarConfig $_config): InlineKeyboard
    {
        $result = $this->createEmptyCalendar();
        $days = $this->getAllDaysOfMonth($_year, $_month);

        $currentRow = 1;
        foreach ($days as $day => $dayOfWeek) {
            $dayOfWeek = ($dayOfWeek === 0 ? 7 : $dayOfWeek);
            $result[$currentRow][$dayOfWeek] = $day;

            if ($dayOfWeek === 7) {
                ++$currentRow;
            }
        }

        $this->deleteEmptyCalendarRows($result);
        $this->addDaysOfWeekKeyboardRow($result);
        $this->preprocessCalendarKeyboard($result, $_config);
        $this->addCommandKeyboardRow($_year, $_month, $result);

        $this->getInlineCalendarLogger()->debug('DaysOfMonthKeyboardArray', $result);
        return InlineButton::buttonsArray(self::getCommandName(), $result);
    }

    /**
     * @return InlineKeyboard
     */
    public function createMonthsKeyboard(): InlineKeyboard
    {
        $months = $this->getNamesOfMonthsLong();
        $cols = 4;

        $result = [];
        $index = 0;
        foreach ($months as $month) {
            $row = (int)\floor($index / $cols);
            $result[$row][$index + 1] = $month;
            ++$index;
        }

        $this->getInlineCalendarLogger()->debug('MonthsKeyboardArray', $result);
        return InlineButton::buttonsArray(self::getCommandName(), $result);
    }

    /**
     * @param CalendarConfig $_config
     *
     * @return InlineKeyboard
     */
    public function createYearsKeyboard(CalendarConfig $_config): InlineKeyboard
    {
        $years = $_config->getAvailableYears();
        $cols = 5;

        $result = [];
        $index = 0;
        foreach ($years as $year) {
            $row = (int)\floor($index / $cols);
            $result[$row][$year] = $year;
            ++$index;
        }

        $this->getInlineCalendarLogger()->debug('YearsKeyboardArray', $result);
        return InlineButton::buttonsArray(self::getCommandName(), $result);
    }

    /**
     * Получить строку клавиатуры с названиями дней недели.
     *
     * @return string[]
     */
    protected function getDaysOfWeekKeyboardRow(): array
    {
        return $this->getNamesOfDaysOfWeekArrayShort();
    }

    /**
     * Подготовить клавиатуру календаря для передачи в Telegram.
     *
     * @param array          $_keyboard
     * @param CalendarConfig $_config
     *
     * @return void
     */
    private function preprocessCalendarKeyboard(array &$_keyboard, CalendarConfig $_config): void
    {
        [$year, $month, $day] = $this->getCalendarDate($_config);
        [$todayYear, $todayMonth, $todayDay] = $this->getTodayDate();

        $this->modify_array_recursive($_keyboard,
            function ($key, $value) use ($year, $month, $day, $todayYear, $todayMonth): array {
                // Reset blank strings' keys.
                if ($value === $this->getBlankString()) {
                    return [$this->getBlankString() . $key, $value];
                }

                // Mark today day.
                if ($year === $todayYear && $month === $todayMonth && $value === $day) {
                    return [$value, '>' . $value . '<'];
                }

                return [$value, $value];
            });
    }

    /**
     * Удалить пустые строки календаря.
     *
     * @param array $_keyboard
     */
    private function deleteEmptyCalendarRows(array &$_keyboard): void
    {
        foreach ($_keyboard as $rowNumber => $row) {
            $isFull = false;
            foreach ($row as $value) {
                if ($value !== $this->getBlankString()) {
                    $isFull = true;
                }
            }

            if (!$isFull) {
                unset($_keyboard[$rowNumber]);
            }
        }
    }

    /**
     * Добавить к календару строку с названиями дней недели.
     *
     * @param array $_keyboard
     *
     */
    private function addDaysOfWeekKeyboardRow(array &$_keyboard): void
    {
        \array_unshift($_keyboard, $this->getDaysOfWeekKeyboardRow());
    }

    /**
     * Добавить строку управления в календарь.
     *
     * @param int   $_year
     * @param int   $_month
     * @param array $_keyboard
     *
     * @return void
     */
    private function addCommandKeyboardRow(int $_year, int $_month, array &$_keyboard): void
    {
        $row = [
            $this->getCommandSelectYear() => $_year,
            $this->getCommandSelectMonth() => $this->getNameOfMonthByIntLong($_month),
            $this->getCommandMoveCalendarLeft() => $this->getCommandMoveCalendarLeft(),
            $this->getCommandMoveCalendarRight() => $this->getCommandMoveCalendarRight(),
        ];
        \array_unshift($_keyboard, $row);
    }

    /**
     * Создать пустую заготовку календаря.
     *
     * @return array
     */
    private function createEmptyCalendar(): array
    {
        $result = [];
        for ($i = 1; $i <= 6; $i++) {
            for ($j = 1; $j <= 7; $j++) {
                $result[$i][$j] = $this->getBlankString();
            }
        }
        return $result;
    }
}
