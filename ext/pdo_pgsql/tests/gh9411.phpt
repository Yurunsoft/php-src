--TEST--
GitHub #9411 (Fix PgSQL large object resource is incorrectly closed)
--SKIPIF--
<?php
if (!extension_loaded('pdo') || !extension_loaded('pdo_pgsql')) die('skip not loaded');
require __DIR__ . '/config.inc';
require __DIR__ . '/../../../ext/pdo/tests/pdo_test.inc';
PDOTest::skip();
?>
--FILE--
<?php
require __DIR__ . '/../../../ext/pdo/tests/pdo_test.inc';
$db = PDOTest::test_factory(__DIR__ . '/common.phpt');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

$db->beginTransaction();
$oid = $db->pgsqlLOBCreate();
var_dump($lob = $db->pgsqlLOBOpen($oid, 'wb'));
fwrite($lob, 'test');
$db->commit();
$db->beginTransaction();
var_dump($lob = $db->pgsqlLOBOpen($oid, 'wb'));
var_dump(fgets($lob));
?>
--EXPECTF--
resource(%d) of type (stream)
resource(%d) of type (stream)
string(4) "test"
