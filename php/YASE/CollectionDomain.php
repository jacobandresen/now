<?php
//2011, Jacob Andresen <jacob.andresen@gmail.com>
class CollectionDomain
{
    public $id;
    public $collectionId;
    public $domain;

    public function __construct()
    {
    }

    public static function create($data)
    {
        $d = new CollectionDomain();
        $SQL = "INSERT INTO collection_domain(collection_id,domain) values(" . $data->collectionId . ",'" . $data->domain . "')";
        mysql_query($SQL) or die (mysql_error());
        $d->id = mysql_insert_id();
        $d->collectionId = $data->collectionId;
        $d->domain = $data->domain;
        return $d;
    }

    public static function retrieve($data)
    {
        if (isset($data->id)) {
            $res = mysql_query("SELECT id,domain FROM collection_domain where id=" . $data->id) or die (mysql_error());
        } else {
            $res = mysql_query("SELECT id,domain from collection_domain where collection_id=" . $data->collectionId) or die (mysql_error());
        }

        $domains = array();
        while ($row = mysql_fetch_row($res)) {
            $d = new CollectionDomain();
            $d->id = $row[0];
            $d->domain = $row[1];
            array_push($domains, $d);
        }
        return $domains;
    }

    public static function update($data)
    {
        $res = mysql_query("UPDATE collection_domain WHERE id=" . $data->id . " SET collection_id=" . $data->collectionId . " and domain='" . $data->domain . "'") or die(mysql_error());
    }

    public static function destroy($data)
    {
        mysql_query("DELETE FROM collection_domain where id=" . $data->id);
    }

}
