<?php declare(strict_types=1);

namespace DJTommek\GlympseApi;

class RequestClient
{
	const API_URL = 'https://api.glympse.com';

	/**
	 * @param string $path
	 * @param array $queryParams
	 * @param string|array<string,mixed> $body array for key-value request, string for raw body
	 * @param string|null $authorization
	 * @return mixed
	 * @throws \JsonException
	 * @throws GlympseApiException
	 */
	public function makePostRequest(string $path, array $queryParams, $body = [], ?string $authorization = null)
	{
		if (is_array($body) === false && is_string($body) === false) {
			throw new \InvalidArgumentException(sprintf('Parameter "$body" must be array or string but "%s" provided.', gettype($body)));
		}
		$url = sprintf('%s%s?%s', self::API_URL, $path, http_build_query($queryParams));
		$headers = [
			'Content-type: application/json',
		];
		if ($authorization) {
			$headers[] = 'Authorization: Bearer ' . $authorization;
		}
		$response = Utils::fileGetContents($url, [
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $body,
			CURLOPT_HTTPHEADER => $headers,
		]);
		$content = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
		if ($content->result === 'ok') {
			return $content->response;
		} else {
			throw new GlympseApiException($content->meta);
		}
	}

	/**
	 * @throws \JsonException
	 * @throws GlympseApiException
	 */
	public function makeGetRequest(string $path, array $queryParams, ?string $authorization = null): \stdClass
	{
		$url = sprintf('%s%s?%s', self::API_URL, $path, http_build_query($queryParams));
		$headers = [
			'Content-type: application/json',
		];
		if ($authorization) {
			$headers[] = 'Authorization: Bearer ' . $authorization;
		}
		$response = Utils::fileGetContents($url, [
			CURLOPT_HTTPHEADER => $headers,
		]);
		$content = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
		if ($content->result === 'ok') {
			return $content->response;
		} else {
			throw new GlympseApiException($content->meta);
		}
	}
}
