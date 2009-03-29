<?php

class Indexer { 

  protected $iCustomerId;       //customer number in database
  protected $iDomainId;         //current domain being processed 
  protected $skipStore;		//skip filters
  public $aFilterSkip;		
  public $sBodyFilter;

  public function setDomain($sDomain) {
    $res = mysql_query("SELECT id from domain where user_id='".$this->iCustomerId."' and base='".$sDomain."'") or die(mysql_error());
    if ($row = mysql_fetch_array($res) ){
     $this->iDomainId=$row['id'];
    }else{
     $this->iDomainId=-1;
    }
  }

  public function addBodyFilter ($bodyfilter ) {
    $this->sBodyFilter=$bodyfilter; 
  }

 public function __construct($iCustomerId){
   if ((!(isset($iCustomerId))) || $iCustomerId<0){
     die("index:invalid customer id \r\n");
   }
   $this->iCustomerId=$iCustomerId; 
   $this->aFilterSkip=array(); 
   $this->sBodyFilter=""; 
  }

  public function clear(){
    mysql_query("DELETE FROM document where user_id='".$this->iCustomerId."'");
  }

  public function index(){
    $this->clear(); 

   //grab the latest versions from the dump
  $res = mysql_query("select max(retrieved),url,html,level from dump where user_id='".$this->iCustomerId."' group by user_id,url") ; //or die (mysql_error());
   while($row=mysql_fetch_array($res)){
     try{ 
     $this->add(urldecode($row['url']),
     		urldecode($row['html']),
     		$row['level']
			);
      }catch(Exception $e){
        print "FAILED: $url \r\n";
        }
    }
  }

  public function add($url, $body, $level ) {
    try{
    $title="";

   //don't index same url twice
    $res= mysql_query("SELECT id from document where url='$url' and user_id='".$this->iCustomerId."'") ; //or die(mysql_error());
   if($row=mysql_fetch_array($res)){
      print "duplicate: $url <br> \r\n"; 
      return false;
   }
  
   //process skip filters
   foreach ($this->aFilterSkip as $oItem){
     preg_match("|$oItem|", $url, $aMatch);
     if ( count($aMatch) > 0){
       return false;
     }
   }

   //process content
   $orig=$body;
 
   if ($this->isUTF8($body)){
     $body = iconv("UTF-8", "ISO-8859-1", $body);
   }

   $timestmp=time();
   $sFound='';

   //find title
   if ($title == ''){
     preg_match("|<.*?content_header[^>]*?\>(.*?)<\/[^>]*?\>|is", $body, $aMatches);
     if(sizeof($aMatches)){ 
       $title = $aMatches[1];
       $sFound = 'h1';
     } 
   }   
   if ($title == ''){
     preg_match("|<h1>(.*?)<\/h1>|is", $body, $aMatches);
     if(sizeof($aMatches)){ 
       $title = $aMatches[1];
       $sFound = 'h1';
     } 
   }   
   if ($title == ''){
     preg_match("|<h2>(.*?)<\/h2>|is", $body, $aMatches);
     if(sizeof($aMatches)){ 
      $title = $aMatches[1];
      $sFound = 'h2';
     }
   }
   if ($title == ''){
     preg_match("|<title>(.*?)<\/title>|is", $body, $aMatches);
     if(sizeof($aMatches)){ 
       $title = $aMatches[1];
       $sFound = 'title';
     }
   }  
   if ($title == ''){
     preg_match("|<h3>(.*?)<\/h3>|is", $body, $aMatches);
     if(sizeof($aMatches)){ 
      $title = $aMatches[1];
      $sFound = 'h3';
     }
   }

   //clean body
   if($this->sBodyFilter!=""){
     preg_match($this->sBodyFilter, $body, $aMatches); 
     if(sizeof($aMatches)>0){
       $body= $aMatches[1];
      }
   }

  //remove title:
  $title = strip_tags($title);	

  //remove clutter 
   $body = preg_replace("/<script.*?<\/script>/is", ' ', $body);
   $body = preg_replace("/<\!\-\-.*?\-\->/is", ' ', $body);

   $body = strip_tags($body);
   $body = $this->sHtmlToRawText($body);
   $body = preg_replace("/\s+/is", ' ', $body);



  //check for duplicate 
  $md5 = md5($body); 
  $result=mysql_query("SELECT * from document where md5='$md5'");// or die(mysql_error());
    $row=mysql_fetch_row($result); 
    if($row) {
      print "\r\nduplicate found for ".$url."\r\n"; 
      return false;
    }
  
   //add documents with content
   $blength=strlen($body);
   if($blength>5 && strlen($url)>0 ){ 
     $sSQL = "INSERT INTO document(user_id,url,title,content,md5, level) values('".$this->iCustomerId."','$url','$title', '$body', '$md5', '$level');";
     print "indexing: [ $blength ] $url \r\n";  
     mysql_query( $sSQL ) ;//or die (mysql_error());
   }else{
      print $url." empty doc <br />\r\n";
    }
   }catch(Exception $e){

    print "failed adding $url\r\n";
    } 

 } 

  public function reset() {
    $sSQL = "DELETE from document where user_id='".$this->iCustomerId."'";  
    mysql_query( $sSQL ) or die(mysql_error());
  } 

  function isUTF8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
      $c=ord($str[$i]);
      if($c > 128){
        if(($c >= 254)) return false;
          elseif($c >= 252) $bits=6;
          elseif($c >= 248) $bits=5;
          elseif($c >= 240) $bits=4;
          elseif($c >= 224) $bits=3;
          elseif($c >= 192) $bits=2;
          else return false;
        if(($i+$bits) > $len) return false;
        while($bits > 1){
          $i++;
          $b=ord($str[$i]);
          if($b < 128 || $b > 191) return false;
            $bits--;
          }
       }
    }
    return true;
  }
  
  function sHtmlToRawText($sWord, $bNewLines=false, $bCleanHtml=false){
   //translate html entities to their corresponding chars
   $sWord = html_entity_decode($sWord);  
  
   if ($bCleanHtml) {
      $sWord = preg_replace("/<br\s*?\/\>/", "\n", $sWord);
      $sWord = preg_replace("/<[^>]*?\>/", " ", $sWord);  // Remove all htmltags
    }
    if (!$bNewLines){
      $sWord = preg_replace("/\n/", " ", $sWord);
      $sWord = preg_replace("/\r/", " ", $sWord);
    }
    $sWord = preg_replace("/(\¤|\#|\"|\'|\*)/", "", $sWord);  // Remove all nonword characters
    return $sWord;
  }
 };

?>

