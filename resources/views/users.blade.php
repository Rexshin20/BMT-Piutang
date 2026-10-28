@extends('layouts.app')

@section('title', 'Users')

@php
    $statusMeta = [
        'active' => ['label' => 'Active', 'class' => 'border-teal-200 bg-teal-100/30 text-teal-900 dark:text-teal-200'],
        'inactive' => ['label' => 'Inactive', 'class' => 'border-neutral-300 bg-neutral-300/40'],
        'invited' => ['label' => 'Invited', 'class' => 'border-sky-300 bg-sky-200/40 text-sky-900 dark:text-sky-100'],
        'suspended' => ['label' => 'Suspended', 'class' => 'border-destructive/10 bg-destructive/10 text-destructive dark:bg-destructive/50 dark:text-primary'],
    ];

    $roleMeta = [
        'superadmin' => ['label' => 'Superadmin', 'icon' => 'shield-check'],
        'admin' => ['label' => 'Admin', 'icon' => 'badge-check'],
        'manager' => ['label' => 'Manager', 'icon' => 'users'],
        'cashier' => ['label' => 'Cashier', 'icon' => 'credit-card'],
    ];
@endphp

@section('content')
    <div class="flex flex-1 flex-col gap-4 sm:gap-6">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">User List</h1>
                <p class="text-muted-foreground">Manage your users and their roles here.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="btn btn-outline" data-dialog-open="user-invite-dialog">
                    <span>Invite User</span>
                    <svg class="icon"><use href="#icon-mail-plus"></use></svg>
                </button>
                <button type="button" class="btn btn-primary" data-dialog-open="user-create-dialog">
                    <span>Add User</span>
                    <svg class="icon"><use href="#icon-user-plus"></use></svg>
                </button>
            </div>
        </div>

        <div class="js-data-table flex flex-1 flex-col gap-4" data-page-size="10">
            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <svg class="icon icon-sm absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground"><use href="#icon-search"></use></svg>
                    <input type="text" data-table-search class="input h-8 ps-8" placeholder="Filter users...">
                </div>

                <div class="dropdown">
                    <button type="button" class="btn btn-outline btn-sm border-dashed" data-dropdown-trigger aria-expanded="false">
                        <svg class="icon icon-sm"><use href="#icon-plus"></use></svg>
                        Status
                    </button>
                    <div data-dropdown class="dropdown-menu left hidden w-44" data-filter-group data-filter="status">
                        <div class="dropdown-label">Status</div>
                        @foreach ($statusMeta as $value => $meta)
                            <label class="dropdown-item cursor-pointer">
                                <input type="checkbox" value="{{ $value }}" class="checkbox">
                                <span class="capitalize">{{ $meta['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="dropdown">
                    <button type="button" class="btn btn-outline btn-sm border-dashed" data-dropdown-trigger aria-expanded="false">
                        <svg class="icon icon-sm"><use href="#icon-plus"></use></svg>
                        Role
                    </button>
                    <div data-dropdown class="dropdown-menu left hidden w-44" data-filter-group data-filter="role">
                        <div class="dropdown-label">Role</div>
                        @foreach ($roleMeta as $value => $meta)
                            <label class="dropdown-item cursor-pointer">
                                <input type="checkbox" value="{{ $value }}" class="checkbox">
                                <svg class="icon icon-sm text-muted-foreground"><use href="#icon-{{ $meta['icon'] }}"></use></svg>
                                {{ $meta['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-ghost btn-sm text-muted-foreground" data-table-reset>
                    <svg class="icon icon-sm"><use href="#icon-rotate-ccw"></use></svg>
                    Reset
                </button>

                <div class="dropdown ms-auto">
                    <button type="button" class="btn btn-outline btn-sm" data-dropdown-trigger aria-expanded="false">
                        <svg class="icon icon-sm"><use href="#icon-sliders-horizontal"></use></svg>
                        View
                    </button>
                    <div data-dropdown class="dropdown-menu hidden w-44">
                        <div class="dropdown-label">Toggle columns</div>
                        <label class="dropdown-item cursor-pointer">
                            <input type="checkbox" class="checkbox" data-column-toggle="name" checked>
                            Name
                        </label>
                        <label class="dropdown-item cursor-pointer">
                            <input type="checkbox" class="checkbox" data-column-toggle="email" checked>
                            Email
                        </label>
                        <label class="dropdown-item cursor-pointer">
                            <input type="checkbox" class="checkbox" data-column-toggle="phone" checked>
                            Phone Number
                        </label>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <table class="data-table min-w-[56rem]">
                    <thead>
                        <tr>
                            <th class="w-10">
                                <input type="checkbox" class="checkbox" data-select-all aria-label="Select all">
                            </th>
                            <th>
                                <button type="button" class="inline-flex items-center gap-1" data-sort="username">
                                    Username
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th data-col="name">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="name">
                                    Name
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th data-col="email">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="email">
                                    Email
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th data-col="phone">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="phone">
                                    Phone Number
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $status = $statusMeta[$user['status']];
                                $role = $roleMeta[$user['role']];
                                $fullName = $user['first_name'] . ' ' . $user['last_name'];
                            @endphp
                            <tr data-row
                                data-search="{{ strtolower($user['username'] . ' ' . $fullName . ' ' . $user['email']) }}"
                                data-status="{{ $user['status'] }}"
                                data-role="{{ $user['role'] }}"
                                data-sort-username="{{ $user['username'] }}"
                                data-sort-name="{{ $fullName }}"
                                data-sort-email="{{ $user['email'] }}"
                                data-sort-phone="{{ $user['phone_number'] }}"
                                data-edit-username="{{ $user['username'] }}"
                                data-edit-first-name="{{ $user['first_name'] }}"
                                data-edit-last-name="{{ $user['last_name'] }}"
                                data-edit-email="{{ $user['email'] }}"
                                data-edit-phone="{{ $user['phone_number'] }}"
                                data-edit-status="{{ $user['status'] }}"
                                data-edit-role="{{ $user['role'] }}">
                                <td>
                                    <input type="checkbox" class="checkbox row-select" aria-label="Select row">
                                </td>
                                <td class="ps-3"><span class="block max-w-36 truncate font-medium">{{ $user['username'] }}</span></td>
                                <td data-col="name"><span class="block max-w-36 truncate">{{ $fullName }}</span></td>
                                <td data-col="email"><span class="whitespace-nowrap">{{ $user['email'] }}</span></td>
                                <td data-col="phone"><span class="whitespace-nowrap">{{ $user['phone_number'] }}</span></td>
                                <td>
                                    <span class="badge badge-outline capitalize {{ $status['class'] }}">{{ $user['status'] }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <svg class="icon text-muted-foreground"><use href="#icon-{{ $role['icon'] }}"></use></svg>
                                        <span class="text-sm capitalize">{{ $user['role'] }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-ghost btn-icon" data-dropdown-trigger aria-expanded="false" aria-label="Open row actions">
                                            <svg class="icon"><use href="#icon-ellipsis"></use></svg>
                                        </button>
                                        <div data-dropdown class="dropdown-menu hidden w-36">
                                            <button type="button" class="dropdown-item" data-row-edit="user-edit-dialog">
                                                <svg class="icon"><use href="#icon-pencil"></use></svg> Edit
                                            </button>
                                            <button type="button" class="dropdown-item text-destructive" data-delete-row>
                                                <svg class="icon"><use href="#icon-trash-2"></use></svg> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr data-empty-row class="hidden">
                            <td colspan="8" class="h-24 text-center">No results.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-auto flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <span>Rows per page</span>
                    <select data-page-size class="select h-8 w-[4.5rem]">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span data-table-info class="ms-2">Showing 1-10 of {{ count($users) }} row(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span data-page-label class="text-sm font-medium">Page 1 of 1</span>
                    <button type="button" class="page-btn" data-page-action="first" aria-label="First page">
                        <svg class="icon"><use href="#icon-chevrons-left"></use></svg>
                    </button>
                    <button type="button" class="page-btn" data-page-action="prev" aria-label="Previous page">
                        <svg class="icon"><use href="#icon-chevron-left"></use></svg>
                    </button>
                    <button type="button" class="page-btn" data-page-action="next" aria-label="Next page">
                        <svg class="icon"><use href="#icon-chevron-right"></use></svg>
                    </button>
                    <button type="button" class="page-btn" data-page-action="last" aria-label="Last page">
                        <svg class="icon rotate-180"><use href="#icon-chevrons-left"></use></svg>
                    </button>
                </div>
            </div>

            {{-- Bulk actions --}}
            <div class="fixed bottom-4 left-1/2 z-30 hidden -translate-x-1/2" data-bulk-actions>
                <div class="flex items-center gap-3 rounded-lg border bg-background px-4 py-2 shadow-lg">
                    <span class="text-sm font-medium"><span data-selected-count>0</span> of <span data-selected-total>0</span> selected</span>
                    <div class="h-5 w-px bg-border"></div>
                    <button type="button" class="btn btn-destructive btn-sm" data-dialog-open="user-bulk-delete-dialog" data-bulk-delete>
                        <svg class="icon icon-sm"><use href="#icon-trash-2"></use></svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Create user dialog --}}
    <div class="dialog" id="user-create-dialog" role="dialog" aria-modal="true" aria-labelledby="user-create-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-xl">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="user-create-title" class="dialog-title">Add User</h2>
                <p class="dialog-description">Create a new user and assign a role.</p>
            </div>
            <form data-demo-form data-message="User created." data-close-dialog="user-create-dialog" data-reset>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="label" for="user-first-name">First name</label>
                        <input id="user-first-name" name="first_name" class="input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-last-name">Last name</label>
                        <input id="user-last-name" name="last_name" class="input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-username">Username</label>
                        <input id="user-username" name="username" class="input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-email">Email</label>
                        <input id="user-email" name="email" type="email" class="input" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-phone">Phone number</label>
                        <input id="user-phone" name="phone" class="input">
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-status">Status</label>
                        <select id="user-status" name="status" class="select">
                            @foreach ($statusMeta as $value => $meta)
                                <option value="{{ $value }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <label class="label" for="user-role">Role</label>
                        <select id="user-role" name="role" class="select">
                            @foreach ($roleMeta as $value => $meta)
                                <option value="{{ $value }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Add user</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit user dialog --}}
    <div class="dialog" id="user-edit-dialog" role="dialog" aria-modal="true" aria-labelledby="user-edit-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-xl">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="user-edit-title" class="dialog-title">Edit User</h2>
                <p class="dialog-description">Update this user's information and role.</p>
            </div>
            <form data-demo-form data-message="User updated." data-close-dialog="user-edit-dialog">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="label" for="user-edit-first">First name</label>
                        <input id="user-edit-first" name="first_name" class="input" data-edit-field="first-name" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-edit-last">Last name</label>
                        <input id="user-edit-last" name="last_name" class="input" data-edit-field="last-name" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-edit-username">Username</label>
                        <input id="user-edit-username" name="username" class="input" data-edit-field="username" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-edit-email">Email</label>
                        <input id="user-edit-email" name="email" type="email" class="input" data-edit-field="email" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-edit-phone">Phone number</label>
                        <input id="user-edit-phone" name="phone" class="input" data-edit-field="phone">
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="user-edit-status">Status</label>
                        <select id="user-edit-status" name="status" class="select" data-edit-field="status">
                            @foreach ($statusMeta as $value => $meta)
                                <option value="{{ $value }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2 sm:col-span-2">
                        <label class="label" for="user-edit-role">Role</label>
                        <select id="user-edit-role" name="role" class="select" data-edit-field="role">
                            @foreach ($roleMeta as $value => $meta)
                                <option value="{{ $value }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Invite user dialog --}}
    <div class="dialog" id="user-invite-dialog" role="dialog" aria-modal="true" aria-labelledby="user-invite-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="user-invite-title" class="dialog-title">Invite User</h2>
                <p class="dialog-description">Send an invitation email to join your team.</p>
            </div>
            <form data-demo-form data-message="Invitation sent." data-close-dialog="user-invite-dialog" data-reset>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="label" for="invite-email">Email</label>
                        <input id="invite-email" name="email" type="email" class="input" placeholder="name@example.com" required>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="invite-role">Role</label>
                        <select id="invite-role" name="role" class="select">
                            @foreach ($roleMeta as $value => $meta)
                                <option value="{{ $value }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Send invitation</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk delete dialog --}}
    <div class="dialog" id="user-bulk-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="user-bulk-delete-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="user-bulk-delete-title" class="dialog-title">Delete users</h2>
                <p class="dialog-description">Are you sure you want to delete the selected users? This action cannot be undone.</p>
            </div>
            <div class="dialog-footer">
                <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                <button type="button" class="btn btn-destructive" data-confirm-bulk-delete>Delete</button>
            </div>
        </div>
    </div>
@endsection
