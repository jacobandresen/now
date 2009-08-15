<?php 

/**
 * filter pdf filers to text
 * 
 * this class stores one tmp file pr account while running
 * NOTE: this requires pdftotext from xpdf (get it from foolabs.com)
 *
 * @author: Jacob Andresen <jacob.andresen@gmail.com>
 */
class YASE_PDFFilter 
{
    protected $iAccountId;     

    public function __construct($iAccountId)
    { 
        $this->iAccountId=$iAccountId;
    }
  
    public function filter($sUrl )
    {
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
