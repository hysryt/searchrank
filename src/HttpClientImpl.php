<?php

namespace SearchRank {

    use GuzzleHttp;

    class HttpClientImpl implements HttpClient {
        private $userAgent;

        public function download(String $url): ?String {
            $client = new GuzzleHttp\Client();

            $headers = [];
            if ($this->userAgent) {
                $headers["User-Agent"] = $this->userAgent;
            }

            $res = $client->request('GET', $url, array(
                "headers" => $headers,
            ));

            return $res->getBody();
        }

        public function setUserAgent(String $ua) {
            $this->userAgent = $ua;
        }
    }
}