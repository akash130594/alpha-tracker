<?php

Breadcrumbs::for('internal.dashboard', function ($trail) {
    $trail->push(__('Dashboard'), route('internal.dashboard'));
});

require __DIR__.'/auth.php';
/*require __DIR__.'/log-viewer.php';*/
