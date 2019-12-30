<?php

namespace SearchRank {

    use GuzzleHttp;

    class HttpClientImpl implements HttpClient {
        public function download(String $url): ?String {
            $client = new GuzzleHttp\Client();
            $res = $client->request('GET', $url);
            return $res->getBody();
        }
    }
}