<?php

namespace Hyqo\Email\Test;

use Hyqo\Email\Email;
use PHPUnit\Framework\TestCase;

use function Hyqo\Email\email;

class EmailTest extends TestCase
{
    /** @dataProvider provideIsValidData */
    public function test_is_valid(string $email, bool $expected): void
    {
        $this->assertEquals($expected, (new Email($email))->isValid(), $email);
    }

    public function provideIsValidData(): array
    {
        return [
            ['foo@bar.com', true],
            ['foo+foo@bar.com', true],
            ['foo foo@bar.com', false],
        ];
    }

    /** @dataProvider provideGetAddressData */
    public function test_get_address(string $email, ?string $expected): void
    {
        $this->assertEquals($expected, (new Email($email))->getAddress(), $email);
    }

    public function provideGetAddressData(): array
    {
        return [
            ['foo@bar.com', 'foo'],
            ['foo+foo@bar.com', 'foo+foo'],
            ['foo foo@bar.com', null],
        ];
    }

    /** @dataProvider provideGetDomainData */
    public function test_get_domain(string $email, ?string $expected): void
    {
        $this->assertEquals($expected, (new Email($email))->getDomain(), $email);
    }

    public function provideGetDomainData(): array
    {
        return [
            ['foo@bar.com', 'bar.com'],
            ['foo+foo@bar.com', 'bar.com'],
            ['foo foo@bar.com', null],
        ];
    }

    /** @dataProvider provideFixTypoData */
    public function test_fix_typo(string $email, ?string $expected): void
    {
        $this->assertEquals($expected, email($email), $email);
    }

    public function provideFixTypoData(): array
    {
        return [
            ['foo@gmail.com', 'foo@gmail.com'],
            ['foo@gnail.com', 'foo@gmail.com'],
            ['foo@gail.con', 'foo@gmail.com'],
            ['foo@gnail.cok', 'foo@gmail.com'],
            ['foo+foo@yaho.co.uk', 'foo+foo@yahoo.co.uk'],
            ['foo foo@bar.com', null],
        ];
    }
}
