<?php

namespace Spatie\OpeningHours;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Spatie\OpeningHours\Helpers\DataTrait;
use Spatie\OpeningHours\Exceptions\InvalidTimeString;

class Time
{
    use DataTrait;

    /** @var int */
    protected $hours;

    /** @var int */
    protected $minutes;

    protected function __construct(int $hours, int $minutes)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;
    }

    public static function fromString(string $string)
    {
        if (! preg_match('/^([0-1][0-9])|(2[0-4]):[0-5][0-9]$/', $string)) {
            throw InvalidTimeString::forString($string);
        }

        list($hours, $minutes) = explode(':', $string);

        return new self($hours, $minutes);
    }

    public function hours()
    {
        return $this->hours;
    }

    public function minutes()
    {
        return $this->minutes;
    }

    public static function fromDateTime(DateTimeInterface $dateTime)
    {
        return static::fromString($dateTime->format('H:i'));
    }

    public function isSame(self $time)
    {
        return (string) $this === (string) $time;
    }

    public function isAfter(self $time)
    {
        if ($this->isSame($time)) {
            return false;
        }

        if ($this->hours > $time->hours) {
            return true;
        }

        return $this->hours === $time->hours && $this->minutes >= $time->minutes;
    }

    public function isBefore(self $time)
    {
        if ($this->isSame($time)) {
            return false;
        }

        return ! $this->isAfter($time);
    }

    public function isSameOrAfter(self $time)
    {
        return $this->isSame($time) || $this->isAfter($time);
    }

    public function diff(self $time)
    {
        return $this->toDateTime()->diff($time->toDateTime());
    }

    public function toDateTime(DateTimeInterface $date = null)
    {
        if (! $date) {
            $date = new DateTime('1970-01-01 00:00:00');
        } elseif (! ($date instanceof DateTimeImmutable)) {
            $date = clone $date;
        }

        return $date->setTime($this->hours, $this->minutes);
    }

    public function format(string $format = 'H:i')
    {
        return $this->toDateTime()->format($format);
    }

    public function __toString()
    {
        return $this->format();
    }
}
