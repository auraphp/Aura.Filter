<?php
use Aura\Filter\Value;

$filter_chain = require_once dirname(__DIR__). '/scripts/instance.php';

// set up the filter chain.
// $filter_chain->addHardRule($field, $method, $name, $param1, $param2, $paramN);

$filter_chain->addHardRule('username', Value::IS, 'alnum');
$filter_chain->addHardRule('username', Value::IS, 'strlenBetween', 6, 12);
$filter_chain->addHardRule('username', Value::FIX, 'alnum');

$filter_chain->addHardRule('birthday', Value::IS, 'dateTime');
$filter_chain->addHardRule('birthday', Value::FIX, 'dateTime', 'Y-m-d');
$filter_chain->addHardRule('birthday', Value::IS, 'min', '1970-08-08'); // at least 42 on Aug 8

$filter_chain->addHardRule('nickname', Value::IS_BLANK_OR, 'string');
$filter_chain->addHardRule('nickname', Value::FIX_BLANK_OR, 'string');

$filter_chain->addHardRule('accept_terms', Value::IS, 'bool', true);
$filter_chain->addHardRule('accept_terms', Value::FIX, 'bool');

$filter_chain->addHardRule('password_plaintext', Value::IS, 'strlenMin', 6);
$filter_chain->addHardRule('password_confirmed', Value::IS, 'equalToField', 'password_plaintext');

$data = (object) [
    'username' => 'username',
    'birthday' => '1990-08-27',
    'nickname' => 'awesomenick',
    'something' => 'Hello World',
    'accept_terms' => true,
    'password_plaintext' => 'passwd',
    'password_confirmed' => 'passwd'
];

// execute the chain on a data object or array
$success = $filter_chain->exec($data);
if (! $success) {
    // an array of failure messages, with info about the failures
    $failure = $filter_chain->getMessages();
    var_export($failure);
}
