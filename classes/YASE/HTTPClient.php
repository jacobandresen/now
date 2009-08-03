<?php

class YASE_HTTPClient {
   
    public $sStatus;
    public $sFinalUrl;
    public $sContentType;
 
    protected $oSocket; 
    protected $sHost;
    protected $iPort; 
    protected $sErrNo;
    protected $sErrStr;

    protected $iRedirects;

    protected $sUrl; 
    protected $sReply;
    protected $aHeaders;

    public function __construct(){
        $this->iPort=80; 
        $this->iRedirects=0; 
    }

    public function Connect($sHost ){
        $this->sHost=$sHost;    
        if($this->sHost==""){
            die("missing host name!\r\n");
        } 
        $this->oSocket =  fsockopen( $this->sHost, 
                                     $this->iPort,
                                     $this->sErrNo, 
                                     $this->sErrStr,
                                     30 
                                    );
        $this->aHeaders=array(); 
    }

    public function Close(){
        fclose($this->oSocket);
    }
 
    private function SendRequest( $sRequest ){
        $sRequest.=" HTTP/1.0"; 
        $sRequest.="\r\nUser-Agent: YASE alpha2"; 
        $sRequest.="\r\nHost: ".$this->sHost;
        $sRequest.="\r\nAccept-Charset: iso-8859-1";
        $sRequest.="\r\nConnection: close\r\n\r\n"; 

        if($this->oSocket) 
           fputs( $this->oSocket, $sRequest."\r\n"); 
    }  

    public function extractHost($sUrl) {
        preg_match("@(http\s?\://([^\/].*?))(\/|$)@", $sUrl, $aMatch);
        if ( count($aMatch) > 1 ){
            $sHost = $aMatch[2];
        }
        if(isset($sHost)) 
        $this->sHost=$sHost;
        return($this->sHost); 
    } 

    public function extractRelativeUrl($sUrl) {
        $sUrl=preg_replace("/http\:\/\//i","", $sUrl);
        $sUrl=str_replace($this->sHost, "", $sUrl);
        if($sUrl==""){      
            $sUrl="/";
        }
        return($sUrl); 
    } 
  
    protected function getHeaders () {
        while( !feof($this->oSocket ) ) {
            $sLine=fgets($this->oSocket,512);
            $indx=strpos($sLine,":"); 
            $sKey=substr($sLine, 0, $indx); 
            $sKey=strtolower($sKey); 
            $sValue=substr($sLine,$indx+1, strlen($sLine)-$indx); 
    
            if ( $sKey=="content-type" ){
                $this->sContentType=$sValue; 
            } 
            $sValue=trim($sValue); 
            $this->aHeaders[$sKey]=$sValue; 
            if($sLine=="\r\n") break; 
        }
    }
 
    protected function Redirect() {
        $this->iRedirects++;
  	    print "redirects#:".$this->iRedirects."\r\n";	
	    if($this->iRedirects<5){ 
            $sNewUrl=chop($this->aHeaders['location']);
            print "redirecting to:".$sNewUrl."\r\n"; 
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

    protected function getReply () {
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
                try{
                    while( !feof($this->oSocket ) ) {
                        $sLine=fgets($this->oSocket,512);
                        if(strlen($this->sReply) < 1500000){ 
                            $this->sReply.=$sLine; 
                        }
                    }
                }catch(Exception $e){
                    print "failed retrieving:".$this->ssUrl."\r\n";
                }
            }
        }
        return($this->sReply);
    }  
 
    public function Get ($sIncomingUrl) {
        $sHost = $this->extractHost($sIncomingUrl);
        if($sHost!=""){ 
            $this->sHost=$sHost;
        } 
        $sUrl=$this->extractRelativeUrl($sIncomingUrl); 
        $this->sUrl=$sUrl; 
        $this->SendRequest("GET $sUrl");
        return($this->getReply()); 
    }

    public function Post ($sUrl, $sParams) {
        throw new Exception("not implemented yet");
    }

    public function Delete ($sUrl, $sParams) {
        throw new Exception("not implemented yet");
    }
};
?>
