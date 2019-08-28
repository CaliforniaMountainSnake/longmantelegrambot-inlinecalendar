# longmantelegrambot-inlinecalendar
This is the calendar in form of inline keyboard intended for using with `longman/telegram-bot` and `californiamountainsnake/longmantelegrambot-inlinemenu` libraries.
![Calendar screenshot](https://raw.githubusercontent.com/CaliforniaMountainSnake/longmantelegrambot-inlinecalendar/master/screenshots/Screenshot_1.png "Calendar screenshot")


## Install:
### Require this package with Composer
Install this package through [Composer](https://getcomposer.org/).
Edit your project's `composer.json` file to require `californiamountainsnake/longmantelegrambot-inlinecalendar`:
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": "^7.1",
        "californiamountainsnake/longmantelegrambot-inlinecalendar": "*"
    }
}
```
and run `composer update`

### or
run this command in your command line:
```bash
composer require californiamountainsnake/longmantelegrambot-inlinecalendar
```

## Usage:
1. Include trait into your bot command and realise the abstract methods (mostly contains lang strings):
```php
<?php
class TestCommand extends BaseUserCommand
{
    use InlineCalendar;
}
```
2. Create the calendar config:
```php
<?php
class TestCommand extends BaseUserCommand
{
    use InlineCalendar;
    
    private function getCalendarConfig(): CalendarConfig
    {
        $min = [2019, 8, 28];
        $max = [2037, 12, 31];
        $def = $min;
        return new CalendarConfig('Date selection', $min, $max, $def);
    }
}
```
3. Use calendar!
```php
<?php
class TestCommand extends BaseUserCommand
{
    use InlineCalendar;
    
    public function execute(): ServerResponse
    {
        // Conversation start
        $this->startConversation();

        $isSelected = $this->selectDate($this->getCalendarConfig(), 'Please select the date:', $this->getMessage());
        if (!$isSelected) {
            return $this->emptyResponse();
        }
        
        $msg = $this->sendTextMessage (\print_r($this->getCalendarDate($this->getCalendarConfig()), true));
        $this->stopConversation();
        return $msg;
    }
}
```
