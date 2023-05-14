<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

class GlympseApiException extends \Exception
{
	public \stdClass $meta;
	public \DateTimeImmutable $time;
	public string $error;
	public ?string $error_detail;

	public function __construct(\stdClass $meta)
	{
		$this->meta = $meta;
		$this->error = $this->meta->error;
		$this->error_detail = $this->meta->error_detail ?? null;
		$this->time = (new \DateTimeImmutable)->setTimestamp((int)($this->meta->time / 1000));
		if ($this->error_detail === null) {
			$message = $this->error;
		} else {
			$message = sprintf('%s: %s', $this->error, $this->error_detail);
		}

		parent::__construct($message);
	}
}
