<?php
class HTTPClient
{
  public $sStatus;
  public $sFinalUrl;
  public $sContentType;

  private $oSocket;
  private $sHost;
  private $iPort;
  private $sErrNo;
  private $sErrStr;
  private $iRedirects;
  private $sUrl;
  private $sReply;
  private $aHeaders;

  public function __construct($sHost)
  {
    $this->iPort=80;
    $this->iRedirects=0;
    $this->sHost=$sHost;
  }

  public function get ($sIncomingUrl)
  {
    $sHost = URL::extractHost($sIncomingUrl);
    if($sHost!=""){
      $this->sHost=$sHost;
    }
    $this->connect($sHost);
    $sUrl = URL::extractRelativeUrl($sHost,$sIncomingUrl);
    $this->sUrl=$sUrl;
    $this->SendRequest("GET $sUrl");
    $sResponse = $this->getReply();
    $this->close();

    return($sResponse);
  }

  public function getDocument ($sUrl)
  {
    $document = new Document();
    $document->sUrl = $sUrl;
    $document->sContentType=trim($this->sContentType);
    $document->sContent = $this->get($sUrl);

    return $document;
  }

  private function connect($sHost)
  {
    $this->sHost=$sHost;

    if($this->sHost==""){
      die("missing host name!\r\n");
    }
    $this->oSocket = fsockopen( $this->sHost,
      $this->iPort,
      $this->sErrNo,
      $this->sErrStr,
      30
    );
    $this->aHeaders=array();
  }

  private function close()
  {
    fclose($this->oSocket);
  }

  private function sendRequest( $sRequest )
  {
    $sRequest.=" HTTP/1.0";
    $sRequest.="\r\nUser-Agent: YASE alpha2";
    $sRequest.="\r\nHost: ".$this->sHost;
    $sRequest.="\r\nAccept-Charset: iso-8859-1";
    $sRequest.="\r\nConnection: close\r\n\r\n";

    if($this->oSocket)
      fputs( $this->oSocket, $sRequest."\r\n");
  }

  private function getHeaders ()
  {
    $this->aHeaders=array();
    $this->sContentType="";
    while( !feof($this->oSocket ) ) {
      $sLine=fgets($this->oSocket,512);
      $indx=strpos($sLine,":");
      $sKey=substr($sLine, 0, $indx);
      $sKey=strtolower($sKey);
      $sValue=substr($sLine,$indx+1, strlen($sLine)-$indx);
      $sKey=strtolower(trim($sKey));
      $sValue=trim($sValue);
      if ( $sKey=="content-type" ){
        $this->sContentType=$sValue;
      }
      $this->aHeaders[$sKey]=$sValue;
      if($sLine=="\r\n") break;
    }

  }

  private function redirect()
  {
    $this->iRedirects++;
    print "redirects#:".$this->iRedirects."\r\n";
    if($this->iRedirects<5){
      $sNewUrl=chop($this->aHeaders['location']);

      //TODO: assume that the redirect is on the same host
      print "redirecting to:".$sNewUrl."\r\n";
      if (isset($this->oSocket))
        $this->close();
      $this->Connect($this->sHost);

      //make sure we have a full url
      if(!(strpos($sNewUrl, $this->sHost)) &&
        !(strpos($sNewUrl, "/"))){
          print "EXPAND:".$this->sHost."\r\n";
          $sNewUrl="http://".$this->sHost.$sNewUrl;
          print "NEW URL:".$sNewUrl."\r\n";
        }

      $this->sFinalUrl=$sNewUrl;
      print "[".$this->sFinalUrl."]\r\n";
      return($this->Get($sNewUrl));
    }else{
      print "too many redirects \r\n";
      return("");
    }
  }

  private function getReply ()
  {
    $this->sReply="";
    if(!$this->oSocket){ return(""); }

    $sStatus=fgets($this->oSocket,24);
    $aStatus=split(" ",$sStatus, 3);
    if( preg_match("/http/i",$aStatus[0])){
      if($aStatus[1]!="200"){
        //handle redirects
        if( $aStatus[1]=="301" || $aStatus[1]=="302"){
          $this->getHeaders();
          $this->sStatus="301";
          return($this->Redirect());
        }
        if($aStatus[1]=="400"){
          print "[".$this->sUrl."] was not found!\r\n";
          return("");
        }
      }else{
        $this->getHeaders();
        $this->sReply="";

        if (  !(isset($this->aHeaders["content-length"]))||
           $this->aHeaders["content-length"] < MAX_CONTENT_LENGTH)
        {
        try{
          while( !feof($this->oSocket ) ) {
            $sLine=fgets($this->oSocket,512);
            if(strlen($this->sReply) < MAX_CONTENT_LENGTH){
              $this->sReply.=$sLine;
            }
          }
        }catch(Exception $e){
          print "failed retrieving:".$this->ssUrl."\r\n";
        }
        }else{
          print "[".$this->sUrl."] CONTENT TO BIG\r\n";
          $this->sReply="";
        }
      }
    }
    return($this->sReply);
  }
};
?>
