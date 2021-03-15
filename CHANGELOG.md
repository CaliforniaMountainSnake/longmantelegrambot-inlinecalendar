# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security


## [1.4.2] - 2021-03-15
### Fixed
- Fix of the last calendar message deletion.

## [1.4.1] - 2021-03-15
### Added
- Added an optional parameter "is_delete_last_calendar_message" to the InlineCalendar::selectDate() method.

## [1.4.0] - 2021-03-14
### Fixed
- Now the calendar correctly process month/year selection with the borders of allowable range.

## [1.3.0] - 2021-03-14
### Changed
- Updated composer dependencies.
- InlineCalendar now uses the showAnyMessage method to render messages.

## [1.2.1] - 2019-11-12
### Fixed
- Added forgotten deletion of the conversation note with calendar msg id.

## [1.2.0] - 2019-11-12
### Changed
- !CalendarConfig::$calendarName variable has been deleted from the class.
- InlineCalendar now mark the default day, not today day.
- Conversation notes' names have been changed.

## [1.1.2] - 2019-11-12
- Added CalendarDateTimeUtils::createDateFromDateTime() method.

## [1.1.1] - 2019-11-11
### Changed
- CalendarDateTimeUtils::createDateTimeFromDate() method now always return a DateTime object that have 00:00:00 time.

## [1.1.0] - 2019-11-11
### Changed
- InlineCalendar::selectDate() method now returns the result date using a callback. And deletes temp conversation notes.

## [1.0.6] - 2019-11-04
### Added
- Added more log messages in CalendarKeyboardsUtils.

## [1.0.5] - 2019-11-04
### Changed
- InlineCalendarLogger has been moved to the separate trait (This allows to initialize the logger without initialization of the main calendar trait).

## [1.0.4] - 2019-11-04
### Fixed
- Fixed a bug with CalendarConfig.

## [1.0.3] - 2019-11-03
### Added
- Added possibility to set the logger for InlineCalendar.

## [1.0.2] - 2019-08-28
### Fixed
- Fixed the bug with composer's namespace.

## [1.0.1] - 2019-08-28
### Fixed
- Fixed the README bug.

## [1.0.0] - 2019-08-28
### Added
- The library has been created.
