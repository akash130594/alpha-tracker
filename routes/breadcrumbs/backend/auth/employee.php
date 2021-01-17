<?php

Breadcrumbs::for('admin.auth.employee.list', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Employees'), route('admin.auth.employee.list'));
});

Breadcrumbs::for('admin.auth.employee.add', function ($trail) {
    $trail->parent('admin.auth.employee.list');
    $trail->push(__('Create Employee'), route('admin.auth.employee.add'));
});

Breadcrumbs::for('admin.auth.employee.edit', function ($trail, $id) {
    $trail->parent('admin.auth.employee.list');
    $trail->push(__('Edit Employee'), route('admin.auth.employee.edit',$id));
});
