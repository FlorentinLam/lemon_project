<?php

class MoviesDatabaseApiService
{
    private $apiKey;
    private $baseApiUrl;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseApiUrl = 'https://api.themoviedb.org/3/';
    }

    private function buildQuery(array $params = []): array
    {
        return array_merge($params, ['api_key' => $this->apiKey]);
    }

    private function sendRequest(string $endpoint, array $query = []): ?array
    {
        $url = $this->baseApiUrl . $endpoint;
        $query = $this->buildQuery($query);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '?' . http_build_query($query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
        ]);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return null;
        } elseif ($statusCode !== 200) {
            echo "HTTP Error #: $statusCode\n";
            echo "Response from API: $response\n";
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    public function getPopularMovies(): ?array
    {
        $endpoint = 'movie/popular';
        return $this->sendRequest($endpoint);
    }

    public function getRandomMovies(): ?array
    {
        $endpoint = 'movie/now_playing';
        return $this->sendRequest($endpoint);
    }
}
