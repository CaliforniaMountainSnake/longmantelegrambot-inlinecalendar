<?php

namespace CaliforniaMountainSnake\InlineCalendar;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait InlineCalendarLogger
{
    /**
     * @var LoggerInterface
     */
    protected $inlineCalendarLogger;

    /**
     * @param LoggerInterface $_logger
     */
    public function setInlineCalendarLogger(LoggerInterface $_logger): void
    {
        $this->inlineCalendarLogger = $_logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getInlineCalendarLogger(): LoggerInterface
    {
        if ($this->inlineCalendarLogger === null) {
            $this->inlineCalendarLogger = new NullLogger();
        }
        return $this->inlineCalendarLogger;
    }
}
