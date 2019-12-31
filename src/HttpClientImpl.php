<?php

namespace SearchRank {

    use GuzzleHttp;

    class HttpClientImpl implements HttpClient {
        public function download(String $url): ?String {
            $client = new GuzzleHttp\Client();
            $res = $client->request('GET', $url, array(
                "headers" => array(
                    "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.151 Safari/535.19",
                ),
            ));
            return $res->getBody();
        }
    }
}