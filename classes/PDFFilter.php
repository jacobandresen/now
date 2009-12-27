<?php
class PDFFilter
{
  private $accountId;
  public function __construct($accountId)
  {
    $this->accountId=$accountId;
  }

  public function filter($url )
  {
    $content = file_get_contents($url);
    $tmpFile=$this->accountId."tmp";
    unlink($tmpFile.".pdf");
    unlink($tmpFile.".txt");
    $fh = fopen($tmpFile.".pdf",'w');
    fwrite($fh, $content);
    fclose($fh);
    system("pdftotext ".$tmpFile.".pdf");
    $txt = file_get_contents($tmpFile.".txt");
    return($txt);
  }
};
?>
