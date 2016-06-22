<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carbon{

	public static function now($format = 'Y-m-d H:i:s', $zone = 'Asia/Manila')
	{
		$timestamp = new DateTime();
		$timezone = new DateTimeZone($zone);
		$timestamp->setTimezone($timezone);
		return $timestamp->format($format);
	}


	public static function dateDiffinDays($start_date, $end_date) {
		$d1 = new DateTime($start_date);
		$d2 = new DateTime($end_date);
		$difference = $d1->diff($d2);
		return (int)$difference->format('%r%a');
	}

	public static function dateDiffinHrs($start_date, $end_date) {
		$d1 = new DateTime($start_date);
		$d2 = new DateTime($end_date);

		$difference = $d1->diff($d2);
		$interval = $difference->format('%r%h');
		$interval += $this->dateDiffinDays($start_date, $end_date) * 24;

		return $interval;
	}

	public static function dateDiffinMins($start_date, $end_date) {
		$d1 = new DateTime($start_date);
		$d2 = new DateTime($end_date);

		$difference = $d1->diff($d2);
		$interval = $difference->format('%r%i');

		$interval += $this->dateDiffinHrs($start_date, $end_date) * 60;

		return $interval;
	}

	public static function dateDiffinSecs($start_date, $end_date) {
		$d1 = new DateTime($start_date);
		$d2 = new DateTime($end_date);

		$difference = $d1->diff($d2);
		$interval = $difference->format('%r%s');

		$interval += $this->dateDiffinMins($start_date, $end_date) * 60;

		return $interval;
	}
}