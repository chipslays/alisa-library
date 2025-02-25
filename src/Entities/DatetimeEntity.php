<?php

namespace Alisa\Entities;

use Alisa\Support\Render;
use DateTime;
use DateTimeZone;

class DatetimeEntity extends Entity
{
    public function year(): ?string
    {
        if (
            isset($this->entity['value']['year_is_relative']) &&
            $this->entity['value']['year_is_relative']
        ) {
            return date('Y', strtotime($this->entity['value']['year'] . ' year'));
        }

        return $this->entity['value']['year'] ?? null;
    }

    public function month(): ?string
    {
        if (
            isset($this->entity['value']['month_is_relative']) &&
            $this->entity['value']['month_is_relative']
        ) {
            return date('n', strtotime($this->entity['value']['month'] . ' month'));
        }

        return $this->entity['value']['month'] ?? null;
    }

    public function day(): ?string
    {
        if (
            isset($this->entity['value']['day_is_relative']) &&
            $this->entity['value']['day_is_relative']
        ) {
            return date('j', strtotime($this->entity['value']['day'] . ' day'));
        }

        return $this->entity['value']['day'] ?? null;
    }

    public function hour(): ?string
    {
        if (
            isset($this->entity['value']['hour_is_relative']) &&
            $this->entity['value']['hour_is_relative']
        ) {
            return date('G', strtotime($this->entity['value']['hour'] . ' hour'));
        }

        return $this->entity['value']['hour'] ?? null;
    }

    public function minute(): ?string
    {
        if (
            isset($this->entity['value']['minute_is_relative']) &&
            $this->entity['value']['minute_is_relative']
        ) {
            return intval(date('i', strtotime($this->entity['value']['minute'] . ' minute')));
        }

        return $this->entity['value']['minute'] ?? null;
    }

    public function toDateTime(
        ?string $year = null,
        ?string $month = null,
        ?string $day = null,
        ?string $hour = null,
        ?string $minute = null,
        ?string $forceTimezone = null,
        bool $useTimezoneFromRequest = false
    ): DateTime {
        $date = implode('-', [
            $this->year() ?? $year ?? date('Y'),
            $this->month() ?? $month ?? 1,
            $this->day() ?? $day ?? 1
        ]);

        $time = implode(':', [
            $this->hour() ?? $hour ?? 0,
            $this->minute() ?? $minute ?? 0
        ]);

        $dateStr = Render::trimWhitespace($date . ' ' . $time);

        if (!$forceTimezone && $useTimezoneFromRequest) {
            $timezone = new DateTimeZone($this->request->get('meta.timezone', date_default_timezone_get()));
        }

        if ($forceTimezone) {
            $timezone = new DateTimeZone($forceTimezone);
        }

        return new DateTime($dateStr, $timezone ?? null);
    }

    public function __toString(): string
    {
        return $this->toDateTime()->format('d.m.Y H:i:s');
    }
}