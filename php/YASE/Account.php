<?php
class Account extends Model
{
    public $id;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
                       b
    public function __construct()
    {
    }

    public function create($data)
    {
        parent::create($data);

        if (isset($data->collections)) {
            foreach ($data->collections as $col) {
                $collection = new Collection();
                $collection->create($col);
            }
        }
    }

    public function retrieve($data)
    {
        parent::retrieve($data);
        parent::associate("Collection", "collections");
    }

    public function update($data)
    {
        parent::update($data);
        //TODO: how to update collections?
    }

}
?>
