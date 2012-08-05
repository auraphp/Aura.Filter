<?php
use Aura\Filter\Value;

$filter_chain = require_once dirname(__DIR__). '/scripts/instance.php';

// set up the filter chain.
// $filter_chain->add($field, $method, $name, $param1, $param2, $paramN);

$filter_chain->add('username', Value::IS, 'alnum');
$filter_chain->add('username', Value::IS, 'strlenBetween', 6, 12);
$filter_chain->add('username', Value::FIX, 'alnum');

$filter_chain->add('birthday', Value::IS, 'isoDate');
$filter_chain->add('birthday', Value::FIX, 'isoDate');
$filter_chain->add('birthday', Value::IS, 'min', '1970-08-08'); // at least 42 on Aug 8

$filter_chain->add('nickname', Value::IS_BLANK_OR, 'string');
$filter_chain->add('nickname', Value::FIX_BLANK_OR, 'string');

$filter_chain->add('something', Value::IS, 'equalToValue', 'SPECIAL VALUE');

$filter_chain->add('accept_terms', Value::IS, 'bool', true);
$filter_chain->add('accept_terms', Value::FIX, 'bool');

$filter_chain->add('password_plaintext', Value::IS, 'strlenBetween', 6, null);
$filter_chain->add('password_confirmed', Value::IS, 'equalToField', 'password_plaintext');

$data = [
    'username' => 'username',
    'birthday' => '1990-08-27',
    'nickname' => 'awesomenick',
    'something' => 'Hello World',
    'accept_terms' => true,
    'password_plaintext' => 'passwd',
    'password_confirmed' => 'passwdx'
];


// execute the chain on a data object or array
$success = $filter_chain->exec($data);
if (! $success) {
    // an array of failure messages, with info about the failures
    $failure = $filter_chain->getMessages();
    var_dump($failure);
}
