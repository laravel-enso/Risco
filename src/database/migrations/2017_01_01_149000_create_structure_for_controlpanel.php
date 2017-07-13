<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForRisco extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'Risco', 'description' => 'Control Panel Group',
    ];

    protected $permissions = [
        ['name' => 'Risco.index', 'description' => 'Control Panels index', 'type' => 0, 'default' => false],
        ['name' => 'Risco.create', 'description' => 'Create Control Panel', 'type' => 1, 'default' => false],
        ['name' => 'Risco.destroy', 'description' => 'Delete Control Panel', 'type' => 1, 'default' => false],
        ['name' => 'Risco.edit', 'description' => 'Edit Control Panel', 'type' => 0, 'default' => false],
        ['name' => 'Risco.store', 'description' => 'Store Control Panel', 'type' => 1, 'default' => false],
        ['name' => 'Risco.update', 'description' => 'Update Control Panel', 'type' => 0, 'default' => false],
        ['name' => 'Risco.show', 'description' => 'Temp for Control Panel', 'type' => 0, 'default' => false],
        ['name' => 'Risco.get', 'description' => 'Get metrics for one app', 'type' => 0, 'default' => false],
        ['name' => 'Risco.getAll', 'description' => 'Get all possible metrics for one app', 'type' => 0, 'default' => false],
        ['name' => 'Risco.clearLaravelLog', 'description' => 'Clear laravel log for one app', 'type' => 1, 'default' => false],
        ['name' => 'Risco.setMaintenanceMode', 'description' => 'Set maintenance mode for one app', 'type' => 1, 'default' => false],
        ['name' => 'Risco.updatePreferences', 'description' => 'Update preferences for one app', 'type' => 1, 'default' => false],
    ];

    protected $menu = [
        'name' => 'Control Panels', 'icon' => 'fa fa-fw fa-stethoscope', 'link' => 'Risco', 'has_children' => 0,
    ];

    protected $parentMenu = '';
}
