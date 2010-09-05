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
   //TODO:construct the path automatically if it is not there
   if(TMP_YASE==''){
     die ('missing temporary storage path: TMP_YASE');
   }

   if(strlen($document->content)>0){
      $tmpFile=TMP_YASE."/".$this->accountId."tmp";
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
