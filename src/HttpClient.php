<?php

namespace SearchRank {
    interface HttpClient {
        public function download(String $url): ?String;
        public function setUserAgent(String $ua);
    }
}