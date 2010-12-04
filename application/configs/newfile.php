<?php

require_once 'Zend/Http/Client.php';

$client = new Zend_Http_Client('http://localhost:5984/stoa/_design/post/_view/by-tag');
echo $client->request()->getBody();

Sopha_Db::createDb('mydb', 'localhost', Sopha_Db::COUCH_PORT);
$db = new Sopha_Db('mydb'); // when opening an existing DB

$doc = $db->retrieve($docId, 'MyDocumentClass', $revision);
$doc->myparam = 'some new value';
$doc->save();

$doc->delete();

$phpValue = array(
    'kak' => 'dila',
    'ma'  => 'nishma'
);
$doc = $db->create($phpValue, 'myDocuemtnId');

