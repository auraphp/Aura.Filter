<?php
$filter = require_once dirname(__DIR__). '/scripts/instance.php';

// set up the filter chain.
// $filter->addHardRule($field, $method, $name, $param1, $param2, $paramN);

$filter->addHardRule('username', $filter::IS, 'alnum');
$filter->addHardRule('username', $filter::IS, 'strlenBetween', 6, 12);
$filter->addHardRule('username', $filter::FIX, 'alnum');

$filter->addHardRule('birthday', $filter::IS, 'dateTime');
$filter->addHardRule('birthday', $filter::FIX, 'dateTime', 'Y-m-d');
$filter->addHardRule('birthday', $filter::IS, 'min', '1970-08-08'); // at least 42 on Aug 8

$filter->addHardRule('nickname', $filter::IS_BLANK_OR, 'string');
$filter->addHardRule('nickname', $filter::FIX_BLANK_OR, 'string');

$filter->addHardRule('accept_terms', $filter::IS, 'bool', true);
$filter->addHardRule('accept_terms', $filter::FIX, 'bool');

$filter->addHardRule('password_plaintext', $filter::IS, 'strlenMin', 6);
$filter->addHardRule('password_confirmed', $filter::IS, 'equalToField', 'password_plaintext');

$data = (object) [
    'username' => 'username',
    'birthday' => '1990-08-27',
    'nickname' => 'awesomenick',
    'something' => 'Hello World',
    'accept_terms' => true,
    'password_plaintext' => 'passwd',
    'password_confirmed' => 'passed'
];

// execute the chain on a data object or array
$success = $filter->values($data);
if (! $success) {
    // an array of failure messages, with info about the failures
    $failure = $filter->getMessages();
    var_export($failure);
}
