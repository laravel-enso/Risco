<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForRisco extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'Risco', 'description' => 'Risco Group',
    ];

    protected $permissions = [
        ['name' => 'risco.index', 'description' => 'Risco  index', 'type' => 0, 'default' => false],
        ['name' => 'risco.query', 'description' => 'Exec query to external API', 'type' => 0, 'default' => false],
    ];

    protected $menu = [
        'name' => 'Risco ', 'icon' => 'fa fa-fw fa-superpowers', 'link' => 'risco', 'has_children' => 0,
    ];

    protected $parentMenu = '';
}
