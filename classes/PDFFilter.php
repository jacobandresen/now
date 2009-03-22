<?php 

//NOTE: get xpdf from ftp.foolabs.com
class PDFFilter {
  public $iCustomerId;     

  public function __construct($iCustomerId){ 
    $this->iCustomerId=$iCustomerId;
  }
  
  public function filter($sUrl ){
    $sContent = file_get_contents($sUrl);
    $tmpFile=$this->iCustomerId."tmp"; 
    unlink($tmpFile.".pdf");
    unlink($tmpFile.".txt");
    $fh = fopen($tmpFile.".pdf",'w');
    fwrite($fh, $sContent);
    fclose($fh);
    system("pdftotext ".$tmpFile.".pdf");
    $sTxt = file_get_contents($tmpFile.".txt");
    return($sTxt);
  } 
};
?>
