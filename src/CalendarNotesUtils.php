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
        $notes = $this->getNote($this->getNoteNameCalendarTempNotes());
        if ($notes === null) {
            $notes = [
                $this->getNoteNameIsFirstCalendarLaunch() => true,
                $this->getNoteNameState() => $this->getStateDefault(),
                $this->getNoteNameSelectedYear() => $defaultYear,
                $this->getNoteNameSelectedMonth() => $defaultMonth,
                $this->getNoteNameSelectedDay() => $defaultDay,
            ];
            $this->setConversationNotes([$this->getNoteNameCalendarTempNotes() => $notes]);
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

        $this->setConversationNotes([$this->getNoteNameCalendarTempNotes() => $currentNotes]);
        return $currentNotes;
    }

    /**
     * @throws TelegramException
     */
    private function deleteCalendarTempNotes(): void
    {
        $this->deleteConversationNotes([
            $this->getNoteNameCalendarTempNotes(),
            $this->getNoteNameCalendarMsgId(),
        ]);
    }

    /**
     * @param CalendarConfig $_config
     *
     * @return string
     * @throws TelegramException
     */
    private function getCalendarState(CalendarConfig $_config): string
    {
        return $this->getCalendarNotes($_config)[$this->getNoteNameState()];
    }

    /**
     * @param CalendarConfig $_config
     * @param string         $_new_state
     *
     * @throws TelegramException
     */
    private function setCalendarState(CalendarConfig $_config, string $_new_state): void
    {
        $this->setCalendarNotes($_config, [$this->getNoteNameState() => $_new_state]);
    }

    /**
     * Get current calendar's date.
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
            (int)$notes[$this->getNoteNameSelectedYear()],
            (int)$notes[$this->getNoteNameSelectedMonth()],
            (int)$notes[$this->getNoteNameSelectedDay()]
        ];
    }

    /**
     * Get the final result of the date selection and delete temp conversation notes.
     *
     * @param CalendarConfig $_config
     *
     * @return array
     * @throws TelegramException
     */
    protected function getFinalCalendarResult(CalendarConfig $_config): array
    {
        $result = $this->getCalendarDate($_config);
        $this->deleteCalendarTempNotes();
        return $result;
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
        $_year !== null && $notes[$this->getNoteNameSelectedYear()] = $_year;
        $_month !== null && $notes[$this->getNoteNameSelectedMonth()] = $_month;
        $_day !== null && $notes[$this->getNoteNameSelectedDay()] = $_day;

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
        return $this->getCalendarNotes($_config)[$this->getNoteNameIsFirstCalendarLaunch()];
    }
}
