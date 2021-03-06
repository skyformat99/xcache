--TEST--
xcache_set/get test for reference for PHP 4
--SKIPIF--
<?php
require("skipif.inc");
if (!(version_compare(phpversion(), '5.0', '<'))) {
	echo 'skip for PHP 4 only';
}
?>
--INI--
xcache.test = 1
xcache.size = 32M
xcache.var_size = 2M
--FILE--
<?php
$ref = array();
$ref['ref'] = &$ref;
$ref['array'] = array(&$ref);
var_dump(xcache_set("ref", $ref));

unset($ref);
$ref = xcache_get("ref");
var_dump(array(&$ref));

$ref['test'] = 1;
var_dump($ref['ref']['test']);
var_dump($ref['array'][0]['test']);
?>
--EXPECT--
bool(true)
array(1) {
  [0]=>
  &array(2) {
    ["ref"]=>
    &array(2) {
      ["ref"]=>
      *RECURSION*
      ["array"]=>
      array(1) {
        [0]=>
        *RECURSION*
      }
    }
    ["array"]=>
    array(1) {
      [0]=>
      &array(2) {
        ["ref"]=>
        *RECURSION*
        ["array"]=>
        array(1) {
          [0]=>
          *RECURSION*
        }
      }
    }
  }
}
int(1)
int(1)
