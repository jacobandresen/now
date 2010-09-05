<?php
class PDFRobot
{
  private $collectionId;
  public function __construct($collectionId)
  {
    $this->collectionId=$collectionId;
  }

  public function clean($document)
  {
   if(strlen($document->content)>0){
      $tmpFile="/tmp/YASE".$this->collectionId."tmp";
      $tmpFilePdf = $tmpFile.".pdf";
      $tmpFileTxt = $tmpFile.".txt";

      unlink($tmpFile.".pdf");
      unlink($tmpFile.".txt");

      $fh = fopen($tmpFile.".pdf",'w');
      fwrite($fh, $document->content);
      fclose($fh);

      system("pdftotext ".$tmpFilePdf);
      $txt = file_get_contents($tmpFileTxt);
      return($txt);
    }
  }
};
?>
