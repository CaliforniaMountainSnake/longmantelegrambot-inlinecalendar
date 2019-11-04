<?php

namespace CaliforniaMountainSnake\InlineCalendar;

use CaliforniaMountainSnake\LongmanTelegrambotUtils\ConversationUtils;
use Longman\TelegramBot\Exception\TelegramException;

trait CalendarNotesUtils
{
    use InlineCalendarLogger;
    use CalendarStringValues;
    use ConversationUtils;

    /**
     * @param CalendarConfig $_config
     *
     * @return array
     * @throws TelegramException
     */
    private function getCalendarNotes(CalendarConfig $_config): array
    {
        [$defaultYear, $defaultMonth, $defaultDay] = $_config->getDefaultDate();
        $notes = $this->getNote($_config->getCalendarName());
        if ($notes === null) {
            $notes = [
                $this->getNoteIsFirstCalendarLaunch() => true,
                $this->getNoteState() => $this->getStateDefault(),
                $this->getNoteSelectedYear() => $defaultYear,
                $this->getNoteSelectedMonth() => $defaultMonth,
                $this->getNoteSelectedDay() => $defaultDay,
            ];
            $this->setConversationNotes([$_config->getCalendarName() => $notes]);
        }
        return $notes;
    }

    /**
     * @param CalendarConfig $_config
     * @param array          $_notes
     *
     * @return array Updated notes.
     * @throws TelegramException
     */
    private function setCalendarNotes(CalendarConfig $_config, array $_notes): array
    {
        $currentNotes = $this->getCalendarNotes($_config);
        foreach ($_notes as $key => $note) {
            $currentNotes[$key] = $note;
        }

        $this->setConversationNotes([$_config->getCalendarName() => $currentNotes]);
        return $currentNotes;
    }

    /**
     * @param CalendarConfig $_config
     *
     * @return string
     * @throws TelegramException
     */
    private function getCalendarState(CalendarConfig $_config): string
    {
        return $this->getCalendarNotes($_config)[$this->getNoteState()];
    }

    /**
     * @param CalendarConfig $_config
     * @param string         $_new_state
     *
     * @throws TelegramException
     */
    private function setCalendarState(CalendarConfig $_config, string $_new_state): void
    {
        $this->setCalendarNotes($_config, [$this->getNoteState() => $_new_state]);
    }

    /**
     * Получить текущую дату календаря.
     *
     * @param CalendarConfig $_config
     *
     * @return array [year, month, day].
     * @throws TelegramException
     */
    protected function getCalendarDate(CalendarConfig $_config): array
    {
        $notes = $this->getCalendarNotes($_config);
        return [
            (int)$notes[$this->getNoteSelectedYear()],
            (int)$notes[$this->getNoteSelectedMonth()],
            (int)$notes[$this->getNoteSelectedDay()]
        ];
    }

    /**
     * Обновить дату календаря.
     *
     * @param CalendarConfig $_config
     * @param int|null       $_year
     * @param int|null       $_month
     * @param int|null       $_day
     *
     * @throws TelegramException
     */
    protected function updateCalendarDate(CalendarConfig $_config, ?int $_year, ?int $_month, ?int $_day): void
    {
        $notes = [];
        $_year !== null && $notes[$this->getNoteSelectedYear()] = $_year;
        $_month !== null && $notes[$this->getNoteSelectedMonth()] = $_month;
        $_day !== null && $notes[$this->getNoteSelectedDay()] = $_day;

        $this->setCalendarNotes($_config, $notes);
    }

    /**
     * @param CalendarConfig $_config
     *
     * @return bool
     * @throws TelegramException
     */
    private function isFirstCalendarLaunch(CalendarConfig $_config): bool
    {
        return $this->getCalendarNotes($_config)[$this->getNoteIsFirstCalendarLaunch()];
    }
}
