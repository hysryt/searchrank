<?php

namespace SearchRank {
    interface HttpClient {
        public function download(String $url): ?String;
    }
}