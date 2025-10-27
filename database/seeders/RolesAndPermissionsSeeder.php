<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Make sure this is your User model namespace

class RolesAndPermissionsSeeder extends Seeder
{
  public function run(): void
  {
    // Clear cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Drop all existing permissions
    // Permission::query()->delete();

    // Create some permissions
    $permissions = [

      //roles
      'role.manage',
      'role.view',
      'role.create',
      'role.edit',
      'role.delete',
      'role.assign-permission',
      'role.remove-permission',

      //User
      'user.manage',
      'user.view',
      'user.create',
      'user.edit',
      'user.delete',

      //BL
      'bl.manage',

      //import
      'import.manage',
      'import.create',
      'import.logistics',
      'import.empty-returns',

      // CRO
      'cro.manage',

      // exports Jobs
      'export.manage',
      'export.create',
      'export.logistics',
      'export.loaded-returns',

      // Fuel Payment
      'fuelPayment.manage',

      //Task Management
      'task.manage',
      'task.view',
      'task.create',
      'task.edit',
      'task.delete',

      //Fuel Management
      'fuel.manage',
      'fuel.view',
      'fuel.create',
      'fuel.edit',
      'fuel.delete',
      'fuel.issuance.view',
      'fuel.issuance.create',
      'fuel.issuance.edit',
      'fuel.issuance.delete',

      //fuel tanks
      'fuel-tank.manage',
      'fuel-tank.view',
      'fuel-tank.create',
      'fuel-tank.edit',
      'fuel-tank.delete',

      // Fleet Management
      'fleet.manage',
      'fleet.view',
      'fleet.create',
      'fleet.edit',
      'fleet.delete',
      'fleet.type.view',
      'fleet.type.create',
      'fleet.type.edit',
      'fleet.type.delete',
      'fleet.manufacturer.view',
      'fleet.manufacturer.create',
      'fleet.manufacturer.edit',
      'fleet.manufacturer.delete',

      // Route Management
      'route.manage',

      // Workshop Management
      'workshop.manage',
      'workshop.request',
      'workshop.accept/reject/payaid',
      'workshop.view',
      'workshop.edit',


      //System Managements
      'system-managements.manage',
      // User Status
      'system-managements.user-status.manage',
      'system-managements.user-status.view',
      'system-managements.user-status.create',
      'system-managements.user-status.edit',
      'system-managements.user-status.delete',

      //locations
      'system-managements.location.manage',
      'system-managements.location.view',
      'system-managements.location.create',
      'system-managements.location.edit',
      'system-managements.location.delete',

      //Operations
      'system-managements.operation.manage',
      'system-managements.operation.view',
      'system-managements.operation.create',
      'system-managements.operation.edit',
      'system-managements.operation.delete',

      //Fuel Types
      'system-managements.fuel-type.manage',
      'system-managements.fuel-type.view',
      'system-managements.fuel-type.create',
      'system-managements.fuel-type.edit',
      'system-managements.fuel-type.delete',



      //Oil Types
      'system-managements.oil-type.manage',
      'system-managements.oil-type.view',
      'system-managements.oil-type.create',
      'system-managements.oil-type.edit',
      'system-managements.oil-type.delete',

      //Projects
      'system-managements.project.manage',
      'system-managements.project.view',
      'system-managements.project.create',
      'system-managements.project.edit',
      'system-managements.project.delete',

      //Nature of Accounts
      'system-managements.nature-of-account.manage',
      'system-managements.nature-of-account.view',
      'system-managements.nature-of-account.create',
      'system-managements.nature-of-account.edit',
      'system-managements.nature-of-account.delete',

      //Job types
      'system-managements.job-type.manage',
      'system-managements.job-type.view',
      'system-managements.job-type.create',
      'system-managements.job-type.edit',
      'system-managements.job-type.delete',

      //Sales Tax Territory
      'system-managements.sales-tax-territory.manage',
      'system-managements.sales-tax-territory.view',
      'system-managements.sales-tax-territory.create',
      'system-managements.sales-tax-territory.edit',
      'system-managements.sales-tax-territory.delete',

      //Companies
      'system-managements.company.manage',
      'system-managements.company.view',
      'system-managements.company.create',
      'system-managements.company.edit',
      'system-managements.company.delete',

      //Parties
      'system-managements.party.manage',
      'system-managements.party.view',
      'system-managements.party.create',
      'system-managements.party.edit',
      'system-managements.party.delete',

      //Banks
      'system-managements.bank.manage',
      'system-managements.bank.view',
      'system-managements.bank.create',
      'system-managements.bank.edit',
      'system-managements.bank.delete',

      //Container sizes
      'system-managements.container-size.manage',
      'system-managements.container-size.view',
      'system-managements.container-size.create',
      'system-managements.container-size.edit',
      'system-managements.container-size.delete',

      //Voucher Types
      'system-managements.voucher-type.manage',
      'system-managements.voucher-type.view',
      'system-managements.voucher-type.create',
      'system-managements.voucher-type.edit',
      'system-managements.voucher-type.delete',
    ];

    foreach ($permissions as $perm) {
      Permission::firstOrCreate(['name' => $perm]);
    }

    $this->command->info('✅ Permissions seeded successfully.');
  }
}
