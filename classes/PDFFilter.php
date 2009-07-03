<?php 

class PDFFilter {
  public $iAccountId;     

  public function __construct($iAccountId){ 
    $this->iAccountId=$iAccountId;
  }
  
  public function filter($sUrl ){
    $sContent = file_get_contents($sUrl);
    $tmpFile=$this->iAccountId."tmp"; 
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
