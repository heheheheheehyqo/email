<?php

namespace Hyqo\Email;

function email(string $email): ?string
{
    return (new Email($email))->fixTypo();
}
