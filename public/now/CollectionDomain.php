<?php
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
        $SQL = "INSERT INTO collection_domain(collection_id,domain) values(" . $data->collectionId . ",'" . $data->domain . "') returning collection_domain_id";
        $res = pg_query($SQL);
        $row = pg_fetch_array($res);
        $d->id = $row[0];
        $d->collectionId = $data->collectionId;
        $d->domain = $data->domain;
        return $d;
    }

    public static function retrieve($data)
    {
        if (isset($data->id)) {
            $res = pg_query("SELECT collection_domain_id,domain FROM collection_domain where id=" . $data->id);
        } else {
            $res = pg_query("SELECT collection_domain_id,domain from collection_domain where collection_id=" . $data->collectionId);
        }

        $domains = array();
        while ($row = pg_fetch_array($res)) {
            $d = new CollectionDomain();
            $d->id = $row[0];
            $d->domain = $row[1];
            array_push($domains, $d);
        }
        return $domains;
    }

    public static function update($data)
    {
        $res = pg_query("UPDATE collection_domain WHERE id=" . $data->id . " SET collection_id=" . $data->collectionId . " and domain='" . $data->domain . "'");
    }

    public static function destroy($data)
    {
        pg_query("DELETE FROM collection_domain where id=" . $data->id);
    }
}
