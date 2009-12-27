<?php
class PDFFilter
{
  private $accountId;
  public function __construct($accountId)
  {
    $this->accountId=$accountId;
  }

  public function filter($document)
  {
    if(strlen($document->content)>0){
      print "pdf - start - [".$document->url."] \r\n";
      $tmpFile=$this->accountId."tmp";
      unlink($tmpFile.".pdf");
      unlink($tmpFile.".txt");
      $fh = fopen($tmpFile.".pdf",'w');
      fwrite($fh, $document->content);
      fclose($fh);
      system("pdftotext ".$tmpFile.".pdf");
      $txt = file_get_contents($tmpFile.".txt");
      print $txt; 
      print "pdf - end - [".$document->url."] \r\n";
      return($txt);
    } else {
      print "pdf - no content - [".$document->url."] \r\n";
    } 
  }
};
?>
