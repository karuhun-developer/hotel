<?php

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('home');

render(function () {
    return redirect()->route('cms.dashboard');
});

?>
