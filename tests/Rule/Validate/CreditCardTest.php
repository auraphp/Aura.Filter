<?php
namespace Aura\Filter\Rule\Validate;

class CreditCardTest extends AbstractValidateTest
{
    public function providerIs()
    {
        // stolen from Respect Validate testing
        return array(
            array('5376 7473 9720 8720'), // MasterCard
            array('4024.0071.5336.1885'), // Visa 16
            array('4024 007 193 879'), // Visa 13
            array('340-3161-9380-9364'), // AmericanExpress
            array('30351042633884'), // Dinners
        );
    }

    public function providerIsNot()
    {
        // stolen from Respect Validate testing
        return array(
            array(''),
            array('it isnt my credit card number'),
            array('&stR@ng3|] array(|-|@r$'),
            array(''),
            array('1234 1234 1234 1234'),
            array('1234.1234.1234.1234'),
        );
    }
}
