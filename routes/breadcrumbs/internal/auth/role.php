<?php

Breadcrumbs::for('internal.dashboard', function ($trail) {
    $trail->parent('internal.dashboard');
    $trail->push(__('menus.internal.access.roles.management'), route('internal.dashboard'));
});

/*Breadcrumbs::for('admin.auth.role.create', function ($trail) {
    $trail->parent('admin.auth.role.index');
    $trail->push(__('menus.backend.access.roles.create'), route('admin.auth.role.create'));
});

Breadcrumbs::for('admin.auth.role.edit', function ($trail, $id) {
    $trail->parent('admin.auth.role.index');
    $trail->push(__('menus.backend.access.roles.edit'), route('admin.auth.role.edit', $id));
});*/
