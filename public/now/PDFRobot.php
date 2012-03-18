<?php
class PDFRobot
{
    private $parentId;

    public function __construct($parentId)
    {
        $this->parentId = $parentId;
    }

    public function clean($document)
    {
        if (strlen($document->content) > 0) {
            $tmpFile = "/tmp/now/" . $this->parentId . "tmp";
            $tmpFilePdf = $tmpFile . ".pdf";
            $tmpFileTxt = $tmpFile . ".txt";

            unlink($tmpFile . ".pdf");
            unlink($tmpFile . ".txt");

            $fh = fopen($tmpFile . ".pdf", 'w');
            fwrite($fh, $document->content);
            fclose($fh);

            system("pdftotext " . $tmpFilePdf);
            $txt = file_get_contents($tmpFileTxt);
            return ($txt);
        }
    }
}
?>
