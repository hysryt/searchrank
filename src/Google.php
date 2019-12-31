<?php

namespace SearchRank {
    class Google {
        private $httpClient;

        public function __construct(HttpClient $httpClient = null) {
            if ($httpClient === null) {
                $this->httpClient = new HttpClientImpl();
            } else {
                $this->httpClient = $httpClient;
            }
        }

        /**
         * $keyword で検索し、URLが $urlPrefix で始まるサイトの順位を返す
         */
        public function rank(String $keyword, String $urlPrefix): int {
            $isRankin = false;
            $rank = 0;

            for($i = 1; $i <= 10; $i++) {
                $html = $this->downloadSerp($keyword, $i);
                $results = $this->parseHtml($html);

                foreach($results as $item) {
                    $rank++;
                    if (strpos($item, $urlPrefix) === 0) {
                        $isRankin = true;
                        break;
                    }
                }

                if ($isRankin) {
                    break;
                }

                sleep(1);
            }

            if ($isRankin) {
                return $rank;
            }

            return -1;
        }

        public function getSerpUrl(String $keyword, int $page): String {
            if ($page <= 0) {
                throw new Exception("page must be positive integer.");
            }

            $escapedKeyword = urlencode(preg_replace("/( |　)/", "+", $keyword));
            $start = ($page - 1) * 10;
            $url = "https://www.google.com/search?q={$escapedKeyword}&start={$start}";
            return $url;
        }

        /**
         * 検索結果ページのHTMLから検索結果サイトのURL一覧を取得
         */
        public function parseHtml(String $html): array {
            $xpath = $this->htmlToXPath($html);

            // div.rcがそれぞれの検索結果を指す
            // div.rcは検索結果のサイト数分存在する
            $classname = "rc";
            $pages = $xpath->query("//*[contains(@class, '$classname')]");

            $results = [];

            // div.rc内のa要素からサイトURLを取得
            foreach($pages as $page) {
                $a = $xpath->query('div/a', $page);
                $attrs = $a->item(0)->attributes;
                $url = null;
                foreach($attrs as $attr) {
                    if ($attr->name === "href") {
                        $url = $attr->textContent;
                        break;
                    }
                }
                $results[] = $url;
            }

            return $results;
        }

        private function downloadSerp(String $keyword, int $page) {
            $url = $this->getSerpUrl($keyword, $page);
            $html = $this->httpClient->download($url);
            return $html;
        }

        /**
         * HTML文字列をDomXPathインスタンスに変換する
         */
        private function htmlToXPath($html): \DomXPath {
            $dom = new \DOMDocument;
            libxml_use_internal_errors( true );
            $dom->loadHTML($html);
            libxml_clear_errors();
            return new \DomXPath($dom);
        }
    }
}