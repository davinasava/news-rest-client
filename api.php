<?php
/**
 * Mengirim HTTP GET request ke NewsAPI menggunakan PHP cURL.
 */
function http_request_get(string $url, string $apiKey): array
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['X-Api-Key: ' . $apiKey],
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'NewsRestClient/1.0'
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false) {
        return ['ok' => false, 'error' => 'cURL error: ' . $curlError, 'data' => null];
    }

    $decoded = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300) {
        $message = $decoded['message'] ?? ('HTTP error ' . $httpCode);
        return ['ok' => false, 'error' => $message, 'data' => $decoded];
    }

    if (!is_array($decoded) || ($decoded['status'] ?? '') !== 'ok') {
        return ['ok' => false, 'error' => 'Respons API tidak valid.', 'data' => $decoded];
    }

    return ['ok' => true, 'error' => null, 'data' => $decoded];
}

/**
 * Mengambil headline berdasarkan kategori dan kata kunci.
 */
function getNews(array $params = []): array
{
    $apiKey = NEWS_API_KEY;
    if ($apiKey === '' || $apiKey === 'MASUKKAN_API_KEY_ANDA') {
        return ['data' => null, 'error' => 'API key belum diisi. Buka config/config.php lalu masukkan API key NewsAPI Anda.'];
    }

    $query = http_build_query($params);
    $url = 'https://newsapi.org/v2/top-headlines?' . $query;
    $result = http_request_get($url, $apiKey);

    return [
        'data' => $result['data'],
        'error' => $result['ok'] ? null : $result['error']
    ];
}
