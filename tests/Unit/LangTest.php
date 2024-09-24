<?php

use App\Enum\Lang;

it('can get an enum label', function () {
    expect(Lang::from('en')->label())->toBe('English');
});
