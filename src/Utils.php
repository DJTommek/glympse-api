<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

class Utils
{
	/**
	 * CURL request with very simple settings
	 *
	 * @param string $url URL to be loaded
	 * @param array<int, mixed> $curlOpts indexed array of options to curl_setopt()
	 * @return string content of requested page
	 * @throws GlympseApiException if error occured or page returns no content
	 * @author https://gist.github.com/DJTommek/97048e875a91b67123b0c544bc46c116
	 */
	public static function fileGetContents(string $url, array $curlOpts = []): string
	{
		$curl = curl_init($url);
		if ($curl === false) {
			throw new \GlympseApiException('CURL can\'t be initialited.');
		}
		$curlOpts[CURLOPT_RETURNTRANSFER] = true;
		$curlOpts[CURLOPT_HEADER] = true;
		curl_setopt_array($curl, $curlOpts);
		/** @var string|false $curlResponse */
		$curlResponse = curl_exec($curl);
		if ($curlResponse === false) {
			$curlErrno = curl_errno($curl);
			throw new GlympseApiException(sprintf('CURL request error %s: "%s"', $curlErrno, curl_error($curl)));
		}
		$curlInfo = curl_getinfo($curl);
		list($header, $body) = explode("\r\n\r\n", $curlResponse, 2);
		if ($curlInfo['http_code'] >= 500) {
			throw new GlympseApiException(sprintf('Page responded with HTTP code %d: Text response: "%s"', $curlInfo['http_code'], $body));
		}
		if (!$body) {
			$responseCode = trim(explode(PHP_EOL, $header)[0]);
			throw new GlympseApiException(sprintf('Bad response from CURL request from URL "%s": "%s".', $url, $responseCode));
		}
		return $body;
	}

	public static function camelize(string $input, string $separator = '_'): string
	{
		return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
	}

	/**
	 * Convert any input to it's stringify value as Glympse API is expecting
	 *
	 * @param string|bool $input
	 */
	public static function stringify($input): string {
		if (is_bool($input)) {
			return $input ? 'true' : 'false';
		}
		// @TODO add support for datetime to UNIX timestamp in milliseconds
		// @TODO add support for duration as DateInterval to milliseconds
		return $input;
	}
}
