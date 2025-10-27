@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/app-access-roles.js', 'resources/assets/js/modal-add-role.js'])
@endsection

@section('content')
<h4 class="mb-1">Roles List</h4>

<p class="mb-6">
  A role provided access to predefined menus and features so that depending on
  assigned role an administrator can have access to what user needs.
</p>
<!-- Role cards -->
<div class="row g-6">
  @foreach ($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-normal mb-0 text-body">
                        Total {{ $role->users->count() }} users
                    </h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        @foreach ($role->users->take(4) as $user)
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                title="{{ $user->name }}" class="avatar pull-up">

                                @php
                                    $output = '';

                                    if (!empty($user->avatar) && file_exists(public_path('storage/' . $user->avatar))) {
                                        // Avatar exists
                                        $output = '<img src="' . asset('storage/' . $user->avatar) . '" alt="Avatar" class="rounded-circle" style="width:40px; height:40px;">';
                                    } else {
                                        // Generate random background state
                                        $states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                                        $state = $states[array_rand($states)];

                                        // Get initials from user name
                                        $nameParts = explode(' ', trim($user->name));
                                        $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));

                                        $output = '<span class="avatar-initial rounded-circle bg-label-' . $state .
                                                  '" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#fff;">' .
                                                  $initials . '</span>';
                                    }
                                @endphp

                                {!! $output !!}

                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h5 class="mb-1">{{ $role->name }}</h5>
                        {{-- <small>
                            {{ $role->permissions->pluck('name')->implode(', ') ?: 'No permissions assigned' }}
                        </small> --}}
                        @can('role.edit')
                        <a href="javascript:;"
                          data-bs-toggle="modal"
                          data-bs-target="#editRoleModal"
                          class="role-edit-modal"
                          data-role-id="{{ $role->id }}"
                          data-role-name="{{ $role->name }}"
                          data-role-permissions="{{ $role->permissions->pluck('id')->implode(',') }}">
                          <span>Edit Role</span>
                        </a>
                        @endcan
                    </div>
                    @can('role.delete')
                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-role" title="Delete">
                        <i class="ti tabler-trash"></i>
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
  @endforeach

  @can('role.create')
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
              <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid" alt="Image" width="83" />
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</button>
              <p class="mb-0">
                Add new role, <br />
                if it doesn't exist.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endcan
  <div class="col-12">
    <h4 class="mt-6 mb-1">Total users with their roles</h4>
    <p class="mb-0">Find all of your company’s administrator accounts and their associate roles.</p>
  </div>
  <div class="col-12">
    <!-- Role Table -->
    <div class="card">
      <div class="card-datatable">
        <table class="datatables-users table border-top">
          <thead>
            <tr>
              <th></th>
              <th>User</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <!--/ Role Table -->
  </div>
</div>
<!--/ Role cards -->
@can('role.create')
<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
  <form id="addRoleForm" action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title role-title" id="addRoleModalLabel">Add New Role</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <p class="text-body-secondary">Set role permissions</p>
          </div>
          <div class="row g-3">
            <!-- Add role form -->
            <div class="col-12 form-control-validation mb-3">
              <label class="form-label" for="modalRoleName">Role Name</label>
              <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Enter a role name" />
            </div>
            <div class="col-12">
              <h5 class="mb-4">Role Permissions</h5>
              <!-- Permission table -->
              <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-flush-spacing mb-0">
                  <tbody>
                    <tr>
                      <td class="text-nowrap fw-medium">
                        Administrator Access
                        <i class="icon-base ti tabler-info-circle icon-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                      </td>
                      <td>
                        <div class="d-flex justify-content-end">
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAll" />
                            <label class="form-check-label" for="selectAll"> Select All </label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @php
                      $grouped = [];
                      $flatPermissions = $permissions->flatten();
                      foreach ($flatPermissions as $perm) {
                          if (!empty($perm->name) && strpos($perm->name, '.') !== false) {
                              [$module, $action] = explode('.', $perm->name, 2);
                              $grouped[$module][] = ucfirst($action);
                          }
                      }
                    @endphp
                    @foreach ($grouped as $module => $actions)
                      <tr>
                        <td class="text-nowrap fw-medium text-heading">
                          @if(in_array('manage', array_map('strtolower', $actions)))
                            <input class="form-check-input me-2 manage-checkbox" type="checkbox"
                              id="{{ $module }}-manage"
                              name="permissions[]"
                              value="{{ $module.'.manage' }}"
                              data-module="{{ $module }}">
                          @endif
                          <label class="form-check-label" for="{{ $module }}-manage">
                            <b>{{ ucfirst($module) }}</b>
                          </label>
                        </td>
                        <td>
                          <div class="row">
                            @foreach ($actions as $action)
                              @if(strtolower($action) !== 'manage')
                                <div class="form-check mb-2 col-3">
                                  <input class="form-check-input action-checkbox" type="checkbox"
                                    id="{{ $module }}{{ $action }}"
                                    name="permissions[]"
                                    value="{{ $module.'.'.strtolower($action) }}"
                                    data-module="{{ $module }}">
                                  <label class="form-check-label" for="{{ $module }}{{ $action }}">
                                    {{ $action }}
                                  </label>
                                </div>
                              @endif
                            @endforeach
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- Permission table -->
            </div>
          </div>
          <!--/ Add role form -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary me-sm-4 me-1">Submit</button>
          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- / Add Role Modal -->
@endcan

@can('role.edit')
<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
  <form id="editRoleForm" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="editRoleModalLabel">Edit Role</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <p class="text-body-secondary">Update role permissions</p>
          </div>
          <div class="row g-3">
            <div class="col-12 form-control-validation mb-3">
              <label class="form-label" for="editRoleName">Role Name</label>
              <input type="text" id="editRoleName" name="name" class="form-control" placeholder="Enter a role name" />
            </div>
            <div class="col-12">
              <h5 class="mb-4">Role Permissions</h5>
              <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-flush-spacing mb-0">
                  <tbody id="edit-permissions-table">
                    <!-- Permissions will be loaded here by JS -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary me-sm-4 me-1">Update</button>
          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- / Edit Role Modal -->
@endcan

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this record?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Add new user -->
<div class="modal fade" id="addNewUserModal" tabindex="-1" aria-labelledby="addNewUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewUserModalLabel">Add New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addNewUserForm" method="POST" action="{{ route('users.store') }}">
          @csrf
          <div class="row">
            <div class="mb-3 col-12 col-md-6">
              <label for="userName" class="form-label">Name</label>
              <input type="text" class="form-control" id="userName" name="name" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="userEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="userEmail" name="email" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="status_id" class="form-label">Status</label>
              <select class="form-select" id="status_id" name="status_id" required>
                <option value="" disabled selected>Select a status</option>
                @foreach ($statuses as $status)
                  <option value="{{ $status->id }}">{{ $status->label }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="role" class="form-label">Role</label>
              <select class="form-select" id="role" name="role" required>
                <option value="" disabled selected>Select a role</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="addNewUserForm">Add User</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Add new user -->

<!-- Edit user -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" method="POST">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="mb-3 col-12 col-md-6">
              <label for="editUserName" class="form-label">Name</label>
              <input type="text" class="form-control" id="editUserName" name="name" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="editUserEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="editUserEmail" name="email" required>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="editUserStatus" class="form-label">Status</label>
              <select class="form-select" id="editUserStatus" name="status_id" required>
                <option value="" disabled selected>Select a status</option>
                @foreach ($statuses as $status)
                  <option value="{{ $status->id }}">{{ $status->label }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3 col-12 col-md-6">
              <label for="editUserRole" class="form-label">Role</label>
              <select class="form-select" id="editUserRole" name="role" required>
                <option value="" disabled selected>Select a role</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="editUserForm">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Edit user -->

<!-- Toast Container -->
<x-toast-container />
<script>
  /**
   * App user list
 */

  'use strict';

  // Datatable (js)
  document.addEventListener('DOMContentLoaded', function (e) {
    const dtUserTable = document.querySelector('.datatables-users'),
      statusObj = {
        1: { title: 'Pending', class: 'bg-label-warning' },
        2: { title: 'Active', class: 'bg-label-success' },
        3: { title: 'Inactive', class: 'bg-label-secondary' }
      };
    let dt_User,
      userView = baseUrl + '';

    // Users List datatable
    if (dtUserTable) {
      const userRole = document.createElement('div');
      userRole.classList.add('user_role');
      const userPlan = document.createElement('div');
      userPlan.classList.add('user_plan');
      dt_User = new DataTable(dtUserTable, {
        ajax: '/users', // Laravel endpoint that returns JSON user data
        columns: [
          { data: 'id' },
          { data: 'name' },
          { data: 'roles.name' },
          { data: 'status.label' },
          {
            data: null,
            defaultContent: '',
            orderable: false,
            searchable: false
          }
        ],
        columnDefs: [
          {
            className: 'control',
            orderable: false,
            searchable: false,
            responsivePriority: 5,
            targets: 0,
            render: function () {
              return '';
            }
          },
          {
            targets: 1,
            responsivePriority: 1,
            render: function (data, type, full) {
              const name = full.name;
              const email = full.email;
              const image = full.avatar;
              let output;

              if (image) {
                output = `<img src="${assetsPath}img/avatars/${image}" alt="Avatar" class="rounded-circle">`;
              } else {
                const stateNum = Math.floor(Math.random() * 6) + 1;
                const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                const state = states[stateNum];
                const initials = (name.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
                output = `<span class="avatar-initial rounded-circle bg-label-${state}">${initials}</span>`;
              }

              return `
                <div class="d-flex justify-content-left align-items-center role-name">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      ${output}
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <a href="${userView}" class="text-heading text-truncate"><span class="fw-medium">${name}</span></a>
                    <small>@${email}</small>
                  </div>
                </div>
              `;
            }
          },
          {
            targets: 2,
            render: function (data, type, full) {
              const role = full.roles.length ? full.roles[0].name : '';
              const roleBadgeObj = {
                Subscriber: '<span class="me-2"><i class="icon-base ti tabler-user icon-22px text-success"></i></span>',
                Author: '<span class="me-2"><i class="icon-base ti tabler-device-desktop icon-22px text-danger"></i></span>',
                Maintainer: '<span class="me-2"><i class="icon-base ti tabler-chart-pie icon-22px text-info"></i></span>',
                Editor: '<span class="me-2"><i class="icon-base ti tabler-edit icon-22px text-warning"></i></span>',
                Admin: '<span class="me-2"><i class="icon-base ti tabler-crown icon-22px text-primary"></i></span>'
              };
              return `<span class='text-truncate d-flex align-items-center'>${roleBadgeObj[role] || ''}${role}</span>`;
            }
          },
          {
            targets: 3,
            render: function (data, type, full) {
              const status = full.status || {};
              const label = status.label || 'Unknown';
              const color = status.color || 'secondary';
              return `<span class="badge bg-label-${color}" text-capitalized>${label}</span>`;
            }
          },
          {
            targets: -1,
            title: 'Actions',
            searchable: false,
            orderable: false,
            render: function (data, type, full) {
              const userId = full.id;
              return `
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-user" data-id="${userId}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  @can('user.edit')
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-user"  data-id="${userId}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  @endcan
                  @can('user.delete')
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-user" data-user-id="${userId}" title="Delete">
                    <i class="ti tabler-trash"></i>
                  </button>
                  @endcan
                </div>
              `;
            }
          }
        ],
        select: {
          style: 'multi',
          selector: 'td:nth-child(2)'
        },
        order: [[2, 'desc']],
        layout: {
          topStart: {
            rowClass: 'row my-md-0 me-3 ms-0 justify-content-between',
            features: [
              {
                pageLength: {
                  menu: [10, 25, 50, 100],
                  text: '_MENU_'
                }
              }
            ]
          },
          topEnd: {
            features: [
              {
                search: {
                  placeholder: 'Search User',
                  text: '_INPUT_'
                }
              },
              {
                buttons: [
                  // {
                  //   extend: 'collection',
                  //   className: 'btn btn-label-secondary dropdown-toggle me-4',
                  //   text: '<span class="d-flex align-items-center gap-1"><i class="icon-base ti tabler-upload icon-xs"></i> <span class="d-inline-block">Export</span></span>',
                  //   buttons: [
                  //     {
                  //       extend: 'print',
                  //       text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>`,
                  //       className: 'dropdown-item',
                  //       exportOptions: {
                  //         columns: [3, 4, 5, 6, 7],
                  //         format: {
                  //           body: function (inner, coldex, rowdex) {
                  //             if (inner.length <= 0) return inner;

                  //             // Check if inner is HTML content
                  //             if (inner.indexOf('<') > -1) {
                  //               const parser = new DOMParser();
                  //               const doc = parser.parseFromString(inner, 'text/html');

                  //               // Get all text content
                  //               let text = '';

                  //               // Handle specific elements
                  //               const userNameElements = doc.querySelectorAll('.role-name');
                  //               if (userNameElements.length > 0) {
                  //                 userNameElements.forEach(el => {
                  //                   // Get text from nested structure
                  //                   const nameText =
                  //                     el.querySelector('.fw-medium')?.textContent ||
                  //                     el.querySelector('.d-block')?.textContent ||
                  //                     el.textContent;
                  //                   text += nameText.trim() + ' ';
                  //                 });
                  //               } else {
                  //                 // Get regular text content
                  //                 text = doc.body.textContent || doc.body.innerText;
                  //               }

                  //               return text.trim();
                  //             }

                  //             return inner;
                  //           }
                  //         }
                  //       },
                  //       customize: function (win) {
                  //         win.document.body.style.color = config.colors.headingColor;
                  //         win.document.body.style.borderColor = config.colors.borderColor;
                  //         win.document.body.style.backgroundColor = config.colors.bodyBg;
                  //         const table = win.document.body.querySelector('table');
                  //         table.classList.add('compact');
                  //         table.style.color = 'inherit';
                  //         table.style.borderColor = 'inherit';
                  //         table.style.backgroundColor = 'inherit';
                  //       }
                  //     },
                  //     {
                  //       extend: 'csv',
                  //       text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file me-1"></i>Csv</span>`,
                  //       className: 'dropdown-item',
                  //       exportOptions: {
                  //         columns: [3, 4, 5, 6, 7],
                  //         format: {
                  //           body: function (inner, coldex, rowdex) {
                  //             if (inner.length <= 0) return inner;

                  //             // Parse HTML content
                  //             const parser = new DOMParser();
                  //             const doc = parser.parseFromString(inner, 'text/html');

                  //             let text = '';

                  //             // Handle role-name elements specifically
                  //             const userNameElements = doc.querySelectorAll('.role-name');
                  //             if (userNameElements.length > 0) {
                  //               userNameElements.forEach(el => {
                  //                 // Get text from nested structure - try different selectors
                  //                 const nameText =
                  //                   el.querySelector('.fw-medium')?.textContent ||
                  //                   el.querySelector('.d-block')?.textContent ||
                  //                   el.textContent;
                  //                 text += nameText.trim() + ' ';
                  //               });
                  //             } else {
                  //               // Handle other elements (status, role, etc)
                  //               text = doc.body.textContent || doc.body.innerText;
                  //             }

                  //             return text.trim();
                  //           }
                  //         }
                  //       }
                  //     },
                  //     {
                  //       extend: 'excel',
                  //       text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-export me-1"></i>Excel</span>`,
                  //       className: 'dropdown-item',
                  //       exportOptions: {
                  //         columns: [3, 4, 5, 6, 7],
                  //         format: {
                  //           body: function (inner, coldex, rowdex) {
                  //             if (inner.length <= 0) return inner;

                  //             // Parse HTML content
                  //             const parser = new DOMParser();
                  //             const doc = parser.parseFromString(inner, 'text/html');

                  //             let text = '';

                  //             // Handle role-name elements specifically
                  //             const userNameElements = doc.querySelectorAll('.role-name');
                  //             if (userNameElements.length > 0) {
                  //               userNameElements.forEach(el => {
                  //                 // Get text from nested structure - try different selectors
                  //                 const nameText =
                  //                   el.querySelector('.fw-medium')?.textContent ||
                  //                   el.querySelector('.d-block')?.textContent ||
                  //                   el.textContent;
                  //                 text += nameText.trim() + ' ';
                  //               });
                  //             } else {
                  //               // Handle other elements (status, role, etc)
                  //               text = doc.body.textContent || doc.body.innerText;
                  //             }

                  //             return text.trim();
                  //           }
                  //         }
                  //       }
                  //     },
                  //     {
                  //       extend: 'pdf',
                  //       text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Pdf</span>`,
                  //       className: 'dropdown-item',
                  //       exportOptions: {
                  //         columns: [3, 4, 5, 6, 7],
                  //         format: {
                  //           body: function (inner, coldex, rowdex) {
                  //             if (inner.length <= 0) return inner;

                  //             // Parse HTML content
                  //             const parser = new DOMParser();
                  //             const doc = parser.parseFromString(inner, 'text/html');

                  //             let text = '';

                  //             // Handle role-name elements specifically
                  //             const userNameElements = doc.querySelectorAll('.role-name');
                  //             if (userNameElements.length > 0) {
                  //               userNameElements.forEach(el => {
                  //                 // Get text from nested structure - try different selectors
                  //                 const nameText =
                  //                   el.querySelector('.fw-medium')?.textContent ||
                  //                   el.querySelector('.d-block')?.textContent ||
                  //                   el.textContent;
                  //                 text += nameText.trim() + ' ';
                  //               });
                  //             } else {
                  //               // Handle other elements (status, role, etc)
                  //               text = doc.body.textContent || doc.body.innerText;
                  //             }

                  //             return text.trim();
                  //           }
                  //         }
                  //       }
                  //     },
                  //     {
                  //       extend: 'copy',
                  //       text: `<i class="icon-base ti tabler-copy me-1"></i>Copy`,
                  //       className: 'dropdown-item',
                  //       exportOptions: {
                  //         columns: [3, 4, 5, 6, 7],
                  //         format: {
                  //           body: function (inner, coldex, rowdex) {
                  //             if (inner.length <= 0) return inner;

                  //             // Parse HTML content
                  //             const parser = new DOMParser();
                  //             const doc = parser.parseFromString(inner, 'text/html');

                  //             let text = '';

                  //             // Handle role-name elements specifically
                  //             const userNameElements = doc.querySelectorAll('.role-name');
                  //             if (userNameElements.length > 0) {
                  //               userNameElements.forEach(el => {
                  //                 // Get text from nested structure - try different selectors
                  //                 const nameText =
                  //                   el.querySelector('.fw-medium')?.textContent ||
                  //                   el.querySelector('.d-block')?.textContent ||
                  //                   el.textContent;
                  //                 text += nameText.trim() + ' ';
                  //               });
                  //             } else {
                  //               // Handle other elements (status, role, etc)
                  //               text = doc.body.textContent || doc.body.innerText;
                  //             }

                  //             return text.trim();
                  //           }
                  //         }
                  //       }
                  //     }
                  //   ]
                  // },
                @can('user.create')
                  {
                    text: '<i class="icon-base ti tabler-plus me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Add New User</span>',
                    className: 'add-new btn btn-primary rounded-2 waves-effect waves-light',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#addNewUserModal'
                    }
                  }
                @endcan
                ]
              }
            ]
          },
          bottomStart: {
            rowClass: 'row mx-3 justify-content-between',
            features: ['info']
          },
          bottomEnd: 'paging'
        },
        language: {
          paginate: {
            next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
            previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
            first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
            last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
          }
        },
        // For responsive popup
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function (row) {
                const data = row.data();
                return 'Details of ' + data['full_name'];
              }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
              const data = columns
                .map(function (col) {
                  return col.title !== '' // Do not show row in modal popup if title is blank (for check box)
                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td>${col.title}:</td>
                        <td>${col.data}</td>
                      </tr>`
                    : '';
                })
                .join('');

              if (data) {
                const div = document.createElement('div');
                div.classList.add('table-responsive');
                const table = document.createElement('table');
                div.appendChild(table);
                table.classList.add('table');
                const tbody = document.createElement('tbody');
                tbody.innerHTML = data;
                table.appendChild(tbody);
                return div;
              }
              return false;
            }
          }
        }
      });

      //? The 'delete-record' class is necessary for the functionality of the following code.
      function deleteRecord(event) {
        let row = document.querySelector('.dtr-expanded');
        if (event) {
          row = event.target.parentElement.closest('tr');
        }
        if (row) {
          dt_User.row(row).remove().draw();
        }
      }

      function bindDeleteEvent() {
        const userTable = document.querySelector('.datatables-users');
        const modal = document.querySelector('.dtr-bs-modal');

        if (userTable && userTable.classList.contains('collapsed')) {
          if (modal) {
            modal.addEventListener('click', function (event) {
              if (event.target.parentElement.classList.contains('delete-record')) {
                deleteRecord();
                const closeButton = modal.querySelector('.btn-close');
                if (closeButton) closeButton.click(); // Simulates a click on the close button
              }
            });
          }
        } else {
          const tableBody = userTable?.querySelector('tbody');
          if (tableBody) {
            tableBody.addEventListener('click', function (event) {
              if (event.target.parentElement.classList.contains('delete-record')) {
                deleteRecord(event);
              }
            });
          }
        }
      }

      // Initial event binding
      bindDeleteEvent();

      // Re-bind events when modal is shown or hidden
      document.addEventListener('show.bs.modal', function (event) {
        if (event.target.classList.contains('dtr-bs-modal')) {
          bindDeleteEvent();
        }
      });

      document.addEventListener('hide.bs.modal', function (event) {
        if (event.target.classList.contains('dtr-bs-modal')) {
          bindDeleteEvent();
        }
      });
    }

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
      const elementsToModify = [
        { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
        { selector: '.dt-buttons.btn-group .btn-group', classToRemove: 'btn-group' },
        { selector: '.dt-buttons.btn-group', classToRemove: 'btn-group', classToAdd: 'd-flex' },
        { selector: '.dt-search .form-control', classToRemove: 'form-control-sm' },
        { selector: '.dt-length .form-select', classToRemove: 'form-select-sm' },
        { selector: '.dt-length', classToAdd: 'mb-md-6 mb-0' },
        { selector: '.dt-layout-start', classToAdd: 'ps-3 mt-0' },
        {
          selector: '.dt-layout-end',
          classToRemove: 'justify-content-between',
          classToAdd: 'justify-content-md-between justify-content-center d-flex flex-wrap gap-4 mt-0 mb-md-0 mb-6'
        },
        { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
        { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
      ];

      // Delete record
      elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
        document.querySelectorAll(selector).forEach(element => {
          if (classToRemove) {
            classToRemove.split(' ').forEach(className => element.classList.remove(className));
          }
          if (classToAdd) {
            classToAdd.split(' ').forEach(className => element.classList.add(className));
          }
        });
      });
    }, 100);


  });

</script>
<script>
  // Only one DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function () {
      // Manage checkbox logic
      document.querySelectorAll('.manage-checkbox').forEach(manage => {
          manage.addEventListener('change', function () {
              const module = this.dataset.module;
              document.querySelectorAll(`.action-checkbox[data-module="${module}"]`)
                  .forEach(action => action.checked = this.checked);
          });
      });

      // Action checkbox logic
      document.querySelectorAll('.action-checkbox').forEach(action => {
          action.addEventListener('change', function () {
              const module = this.dataset.module;
              const manage = document.querySelector(`.manage-checkbox[data-module="${module}"]`);
              const actions = document.querySelectorAll(`.action-checkbox[data-module="${module}"]`);
              const anyChecked = Array.from(actions).some(a => a.checked);
              if (manage) {
                  manage.checked = anyChecked;
              }
          });
      });

      @can('role.create')
        // Add Role AJAX
        const addRoleForm = document.getElementById('addRoleForm');
        if (!addRoleForm) return;

        addRoleForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(addRoleForm);

            fetch(addRoleForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'Role added successfully!', 'success');
                    window.location.reload();
                } else {
                    showToast('Error', data.message || 'Failed to add role.', 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'An error occurred.', 'error');
                console.error(error);
            });
        });
      @endcan
  });

  @can('role.edit')
    // Edit Role Modal
    document.addEventListener('DOMContentLoaded', function () {

        // Open Edit Modal and populate fields
        document.querySelectorAll('.role-edit-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const roleId = this.getAttribute('data-role-id');
                const roleName = this.getAttribute('data-role-name');
                const rolePermissions = (this.getAttribute('data-role-permissions') || '').split(',');

                // Set form action
                const editForm = document.getElementById('editRoleForm');
                editForm.setAttribute('action', `/roles/${roleId}`);

                // Set role name
                document.getElementById('editRoleName').value = roleName;

                // Build permissions table
                fetch(`/roles/${roleId}/permissions-json`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';

                        Object.entries(data.permissions).forEach(([module, perms]) => {
                            // Separate 'manage' permission
                            const managePerm = perms.find(p => p.name.toLowerCase() === `${module}.manage`);
                            const hasManage = Boolean(managePerm);
                            const manageChecked = hasManage && rolePermissions.includes(managePerm.id.toString()) ? ' checked' : '';

                            // Build module row
                            html += `<tr>
                                <td class="text-nowrap fw-medium text-heading">
                                    ${hasManage ? `<input class="form-check-input me-2 manage-checkbox" type="checkbox"
                                        id="edit-${module}-manage"
                                        name="permissions[]"
                                        value="${managePerm.name}"
                                        data-module="${module}"
                                        ${manageChecked}>` : ''}
                                    <label class="form-check-label" for="edit-${module}-manage">
                                        <b>${module.charAt(0).toUpperCase() + module.slice(1)}</b>
                                    </label>
                                </td>
                                <td>
                                    <div class="row">`;

                            // Build individual action checkboxes
                            perms.forEach(perm => {
                                if (perm.name.toLowerCase() === `${module}.manage`) return; // skip manage

                                const checked = rolePermissions.includes(perm.id.toString()) ? ' checked' : '';
                                html += `<div class="form-check mb-2 col-3">
                                    <input class="form-check-input action-checkbox" type="checkbox"
                                        id="editPerm${perm.id}"
                                        name="permissions[]"
                                        value="${perm.name}"
                                        data-module="${module}"
                                        ${checked}>
                                    <label class="form-check-label" for="editPerm${perm.id}">
                                        ${perm.name.split('.').slice(1).join('.')}
                                    </label>
                                </div>`;
                            });

                            html += `</div></td></tr>`;
                        });

                        document.getElementById('edit-permissions-table').innerHTML = html;

                        // Manage checkbox behavior
                        document.querySelectorAll('#edit-permissions-table .manage-checkbox').forEach(manage => {
                            manage.addEventListener('change', function () {
                                const module = this.dataset.module;
                                document.querySelectorAll(`#edit-permissions-table .action-checkbox[data-module="${module}"]`)
                                    .forEach(action => action.checked = this.checked);
                            });
                        });

                        document.querySelectorAll('#edit-permissions-table .action-checkbox').forEach(action => {
                            action.addEventListener('change', function () {
                                const module = this.dataset.module;
                                const manage = document.querySelector(`#edit-permissions-table .manage-checkbox[data-module="${module}"]`);
                                const actions = document.querySelectorAll(`#edit-permissions-table .action-checkbox[data-module="${module}"]`);
                                const anyChecked = Array.from(actions).some(a => a.checked);
                                if (manage) {
                                    manage.checked = anyChecked;
                                }
                            });
                        });
                    });
            });
        });

        // Handle Edit Form Submit
        const editRoleForm = document.getElementById('editRoleForm');
        if (!editRoleForm) return;

        editRoleForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editRoleForm);

            fetch(editRoleForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'Role updated successfully!', 'success');
                    window.location.reload();
                } else {
                    showToast('Error', data.message || 'Failed to update role.', 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'An error occurred.', 'error');
                console.error(error);
            });
        });

    });
  @endcan

  @can('role.delete')
    // Delete Role
    document.addEventListener('DOMContentLoaded', function () {
        let deleteRoleId = null;

        // Open delete modal and store role ID
        document.querySelectorAll('.delete-role').forEach(btn => {
            btn.addEventListener('click', function () {
                // Find the role card and get the role ID (assume data-role-id is set on the edit link in the same card)
                const card = btn.closest('.card');
                const editLink = card.querySelector('.role-edit-modal');
                if (editLink) {
                    deleteRoleId = editLink.getAttribute('data-role-id');
                    // Show modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                    deleteModal.show();
                }
            });
        });

        // Handle confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (!deleteRoleId) return;
            fetch(`/roles/${deleteRoleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'Role deleted successfully!', 'success');
                    window.location.reload();
                } else {
                    showToast('Error', data.message || 'Failed to delete role.', 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'An error occurred.', 'error');
                console.error(error);
            });
        });
    });
  @endcan

  @can('user.create')
    //add user
    document.addEventListener('DOMContentLoaded', function () {
        const addNewUserForm = document.getElementById('addNewUserForm');
        if (!addNewUserForm) return;

        addNewUserForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(addNewUserForm);

            fetch(addNewUserForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'User added successfully!', 'success');
                    window.location.reload();
                } else {
                    showToast('Error', data.message || 'Failed to add user.', 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'An error occurred.', 'error');
                console.error(error);
            });
        });
    });
  @endcan

  @can('role.edit')
    // Edit Role
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-user');
        if (!editBtn) return;

        const userId = editBtn.dataset.id;
        if (!userId) return;

        // Fetch user data
        fetch(`/users/${userId}`)
            .then(res => res.json())
            .then(data => {
                // Adjust depending on your controller response
                const user = data.user || data.data; // fallback if using ['data'] key
                if (!user) {
                    showToast('Error', 'User data not found.', 'error');
                    return;
                }

                const editUserForm = document.getElementById('editUserForm');
                const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));

                // Fill form fields
                editUserForm.action = `/users/${user.id}`;
                editUserForm.querySelector('#editUserName').value = user.name;
                editUserForm.querySelector('#editUserEmail').value = user.email;
                editUserForm.querySelector('#editUserStatus').value = user.status_id;
                editUserForm.querySelector('#editUserRole').value = user.role_name;

                // Show modal
                editUserModal.show();

                // Handle form submission
                editUserForm.onsubmit = function(e) {
                    e.preventDefault();
                    const formData = new FormData(editUserForm);

                    fetch(editUserForm.action, {
                        method: 'POST', // Laravel PUT/PATCH can be handled via _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(resp => {
                        if (resp.success) {
                            showToast('Success', 'User updated successfully!', 'success');
                            editUserModal.hide();
                            window.location.reload(); // or re-fetch your DataTable
                        } else {
                            // Show Laravel validation errors
                            if (resp.errors) {
                                for (let key in resp.errors) {
                                    showToast('Error', resp.errors[key][0], 'error');
                                }
                            } else {
                                showToast('Error', resp.message || 'Failed to update user.', 'error');
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Error', 'An error occurred while updating.', 'error');
                    });
                };
            })
            .catch(err => {
                console.error(err);
                showToast('Error', 'An error occurred while fetching user data.', 'error');
            });
    });
  @endcan

  @can('user.delete')
    // Delete Role
    document.addEventListener('DOMContentLoaded', function () {
        let deleteUserId = null;

        // Event delegation for delete button clicks
        document.addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.delete-user');
            if (deleteBtn) {
                deleteUserId = deleteBtn.getAttribute('data-user-id');
                if (deleteUserId) {
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                    deleteModal.show();
                }
            }
        });

        // Handle confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (!deleteUserId) return;

            fetch(`/users/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'User deleted successfully!', 'success');
                    // Reload table if using DataTables
                    if ($.fn.DataTable.isDataTable('#yourTableId')) {
                        $('#yourTableId').DataTable().ajax.reload();
                    } else {
                        window.location.reload();
                    }
                } else {
                    showToast('Error', data.message || 'Failed to delete user.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error', 'An error occurred.', 'error');
            });
        });
    });
  @endcan

</script>
<style>
  .card-subtitle {
    color: var(--bs-secondary);
  }

  .card-header {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    border-bottom: 1px solid rgba(var(--bs-primary-rgb), 0.1);
  }

  .card-header .card-title {
    color: rgb(var(--bs-primary-rgb));
    font-weight: 600;
  }
</style>
@endsection
