<?php
declare(strict_types=1);


namespace App\Domain\DomainUtils;

use DateTime;
use DateInterval;

/**
 * Class DomainUtils
 * @package App\Domain\DomainUtils
 *
 * @brief Provides utilities for creating or any handling of Domain/Entity objects
 *
 */
class DomainUtils
{
    private const DATE_TIME_FMT = "Y-m-d H:i:s";

    private const TIME_FMT = "H:i:s";


    /**
     * Create DateTime object from datetime returned by DB
     *
     * @param string|null $date
     * @return DateTime|null
     */
    public static function createDateTime(?string $date): ?DateTime
    {
        return $date !== null ?
            DateTime::createFromFormat(self::DATE_TIME_FMT, $date)
            : null;
    }

    /**
     * Create DateTime object from time returned by DB
     *
     * @param string|null $time
     * @return DateInterval|null
     */
    public static function createTime(?string $time): ?DateInterval
    {
        if ($time === null) return null;
        $date = DateTime::createFromFormat(self::TIME_FMT, $time);
        $now = new DateTime();
        $now->setTime(0,0);
        $interval = $now->diff($date);
        return $interval;
    }


	/**
	 * @param ?string $photos
	 * @return string[]
	 */
	public static function parseAuctionPhotosRecord(?string $photos): array
	{
        if ($photos === null) return [];
		$photos_array = explode(',', $photos);
		return ($photos_array !== false) ?
			$photos_array
			: [];
	}
}
