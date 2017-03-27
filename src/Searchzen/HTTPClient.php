<?php
class HTTPClient
{
    public $status;
    public $finalUrl;
    public $contentType;

    private $socket;
    private $host;
    private $port;
    private $errNo;
    private $errStr;
    private $redirects;
    private $url;
    private $reply;
    private $headers;

    private $debug;

    public function __construct() {
        $this->port = 80;
        $this->redirects = 0;
    }

    public function get ($incomingUrl) {
        $host = URL::extractHost($incomingUrl);
        if ($host != "") {
            $this->host = $host;
        }
        $this->connect($host);
        $url = URL::extractRelativeUrl($host, $incomingUrl);
        $this->url = $url;
        $this->SendRequest("GET $url");
        $response = $this->getReply();

        $this->close();

        return ($response);
    }

    public function getDocument ($url) {
        $document = new Document();
        $document->url = $url;
        $document->content = $this->get($url);
        $document->contentType = trim($this->contentType);

        return $document;
    }

    private function connect ($host) {
        $this->host = $host;

        if ($this->host == "") {
            die("missing host name!\r\n");
        }
        $this->socket = fsockopen($this->host,
                                  $this->port,
                                  $this->errNo,
                                  $this->errStr,
                                  30
        );
        $this->headers = array();
    }

    private function close () {
        if (is_resource($this->socket))
            fclose($this->socket);
    }

    private function sendRequest ($request) {
        $request .= " HTTP/1.0";
        $request .= "\r\nUser-Agent: NOW";
        $request .= "\r\nHost: " . $this->host;
        $request .= "\r\nAccept-Charset: iso-8859-1";
        $request .= "\r\nConnection: close\r\n\r\n";

        if ($this->socket)
            fputs($this->socket, $request . "\r\n");
    }

    private function getHeaders () {
        $this->headers = array();
        $this->contentType = "";
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 512);
            $index = strpos($line, ":");
            $key = substr($line, 0, $index);
            $key = strtolower($key);
            $value = substr($line, $index + 1, strlen($line) - $index);
            $key = strtolower(trim($key));
            $value = trim($value);
            if ($key == "content-type") {
                $this->contentType = $value;
            }
            $this->headers[$key] = $value;
            if ($line == "\r\n") break;
        }
    }

    private function redirect () {
        $this->redirects++;
        if ($this->redirects < 5) {
            $newUrl = chop($this->headers['location']);

            if (!(strpos($newUrl, $this->host)) &&
                !(strpos($newUrl, "/"))) {
                $newUrl = "http://" . $this->host . $newUrl;
            }

            $this->finalUrl = $newUrl;
            $this->redirects = 0;
            return ($this->Get($newUrl));
        } else {
            return ("");
        }
    }

    private function getReply() {
        $this->reply = "";
        if (!$this->socket) {
            return ("");
        }

        $status = fgets($this->socket, 24);
        $statusArray = split(" ", $status, 3);
        if (preg_match("/http/i", $statusArray[0])) {
            if ($statusArray[1] != "200") {
                if ($statusArray[1] == "301" || $statusArray[1] == "302") {
                    $this->getHeaders();
                    $this->status = "301";
                    return ($this->Redirect());
                }
                if ($statusArray[1] == "400") {
                    return ("");
                }
            } else {
                $this->getHeaders();
                $this->sReply = "";

                if (!(isset($this->headers["content-length"])) ||
                    $this->headers["content-length"] < MAX_CONTENT_LENGTH) {
                    try {
                        while (!feof($this->socket)) {
                            $line = fgets($this->socket, 512);
                           if (strlen($this->reply) < MAX_CONTENT_LENGTH) {
                                $this->reply .= $line;
                            }
                        }
                    } catch (Exception $e) {
                        print "failed retrieving:" . $this->url . "\r\n";
                    }
                } else {
                    $this->reply = "";
                }
            }
        }
        return ($this->reply);
    }

}
?>
