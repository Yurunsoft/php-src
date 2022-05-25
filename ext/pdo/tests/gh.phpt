--TEST--
GH-XXX: PDOStatement->execute() failed, then execute successfully, errorInfo() information is incorrect
--SKIPIF--
<?php
if (!extension_loaded('pdo')) die('skip');
$dir = getenv('REDIR_TEST_DIR');
if (false == $dir) die('skip no driver');
require_once $dir . 'pdo_test.inc';
PDOTest::skip();
?>
--FILE--
<?php
if (getenv('REDIR_TEST_DIR') === false) putenv('REDIR_TEST_DIR='.__DIR__ . '/../../pdo/tests/');
require_once getenv('REDIR_TEST_DIR') . 'pdo_test.inc';

$db = PDOTest::factory();

@$db->exec("DROP TABLE test");
$db->exec("CREATE TABLE test (x int)");

$stmt = $db->prepare('INSERT INTO TB_TEST VALUES(?)');

// fail
var_dump($stmt->execute([PHP_INT_MIN]), $stmt->errorCode(), $stmt->errorInfo());

// success
var_dump($stmt->execute([1]), $stmt->errorCode(), $stmt->errorInfo());
?>
===DONE===
--EXPECTF--
===FAIL===

bool(false)
string(%d) "%s"
array(3) {
  [0]=>
  string(%d) "%s"
  [1]=>
  int(%d)
  [2]=>
  string(%d) "%s"
}
bool(true)
string(5) "00000"
array(3) {
  [0]=>
  string(5) "00000"
  [1]=>
  NULL
  [2]=>
  NULL
}
===DONE===
