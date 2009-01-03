<?php

// http://www.ietf.org/rfc/rfc2616.txt
// NOTE: this class should run on a standard webhost
//       (e.g. we cannot use curl )

class HTTPClient {

  public function __constructor (){
  }

  public function Get ($sUrl) {
    //grab contents of url
    $sResponse="";
    if ($fp = fopen($sUrl, "r")){
      while ($sBuf = fread($fp, 8192))
        $sResponse.= $sBuf;
      fclose($fp);
    }else{
      print("could not retrieve $sUrl \r\n");
    }
    return($sResponse);
  }


  public function Post ($sUrl, $sParams) {
    throw new Exception("not implemented yet");
  }


};
?>
