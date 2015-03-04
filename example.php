<?php

require __DIR__ . '/vendor/autoload.php';

use Aura\Filter\FilterFactory;

$filter_factory = new FilterFactory();
$filter = $filter_factory->newFilter();

// set up the filter chain.

$filter->validate('username')->is('alnum');
$filter->validate('username')->is('strlenBetween', 6, 12);
$filter->sanitize('username')->to('alnum');

$filter->validate('birthday')->is('dateTime');
$filter->sanitize('birthday')->to('dateTime', 'Y-m-d');
$filter->validate('birthday')->is('min', '1970-08-08'); // at least 42 on Aug 8

$filter->validate('nickname')->isBlankOr('string');
$filter->validate('nickname')->isBlankOr('string');

$filter->validate('accept_terms')->is('bool', true);
$filter->sanitize('accept_terms')->to('bool');

$filter->validate('password_plaintext')->is('strlenMin', 6);
$filter->validate('password_confirmed')->is('equalToField', 'password_plaintext');

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
$success = $filter->apply($data);
if (! $success) {
    // an array of failure messages, with info about the failures
    $failure = $filter->getMessages();
    var_export($failure);
}
