@extends('layouts.app')

@section('title', 'Tasks')

@php
    $statusMeta = [
        'backlog' => ['label' => 'Backlog', 'icon' => 'circle-help'],
        'todo' => ['label' => 'Todo', 'icon' => 'circle'],
        'in progress' => ['label' => 'In Progress', 'icon' => 'timer'],
        'done' => ['label' => 'Done', 'icon' => 'circle-check'],
        'canceled' => ['label' => 'Canceled', 'icon' => 'circle-off'],
    ];

    $priorityMeta = [
        'low' => ['label' => 'Low', 'icon' => 'arrow-down'],
        'medium' => ['label' => 'Medium', 'icon' => 'arrow-right'],
        'high' => ['label' => 'High', 'icon' => 'arrow-up'],
        'critical' => ['label' => 'Critical', 'icon' => 'circle-alert'],
    ];
@endphp

@section('content')
    <div class="flex flex-1 flex-col gap-4 sm:gap-6">
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Tasks</h1>
                <p class="text-muted-foreground">Here's a list of your tasks for this month!</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="btn btn-outline" data-dialog-open="task-import-dialog">
                    <span>Import</span>
                    <svg class="icon"><use href="#icon-download"></use></svg>
                </button>
                <button type="button" class="btn btn-primary" data-dialog-open="task-create-dialog">
                    <span>Create</span>
                    <svg class="icon"><use href="#icon-plus"></use></svg>
                </button>
            </div>
        </div>

        <div class="js-data-table flex flex-1 flex-col gap-4" data-page-size="10">
            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <svg class="icon icon-sm absolute left-2.5 top-1/2 -translate-y-1/2 text-muted-foreground"><use href="#icon-search"></use></svg>
                    <input type="text" data-table-search class="input h-8 ps-8" placeholder="Filter by title or ID...">
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
                                <svg class="icon icon-sm text-muted-foreground"><use href="#icon-{{ $meta['icon'] }}"></use></svg>
                                {{ $meta['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="dropdown">
                    <button type="button" class="btn btn-outline btn-sm border-dashed" data-dropdown-trigger aria-expanded="false">
                        <svg class="icon icon-sm"><use href="#icon-plus"></use></svg>
                        Priority
                    </button>
                    <div data-dropdown class="dropdown-menu left hidden w-44" data-filter-group data-filter="priority">
                        <div class="dropdown-label">Priority</div>
                        @foreach ($priorityMeta as $value => $meta)
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
                            <input type="checkbox" class="checkbox" data-column-toggle="title" checked>
                            Title
                        </label>
                        <label class="dropdown-item cursor-pointer">
                            <input type="checkbox" class="checkbox" data-column-toggle="status" checked>
                            Status
                        </label>
                        <label class="dropdown-item cursor-pointer">
                            <input type="checkbox" class="checkbox" data-column-toggle="priority" checked>
                            Priority
                        </label>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <table class="data-table min-w-[48rem]">
                    <thead>
                        <tr>
                            <th class="w-10">
                                <input type="checkbox" class="checkbox" data-select-all aria-label="Select all">
                            </th>
                            <th class="w-28">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="id">
                                    Task
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th class="w-2/3" data-col="title">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="title">
                                    Title
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th data-col="status">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="status">
                                    Status
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th data-col="priority">
                                <button type="button" class="inline-flex items-center gap-1" data-sort="priority">
                                    Priority
                                    <svg class="icon icon-sm"><use href="#icon-arrow-up-down"></use></svg>
                                </button>
                            </th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            @php
                                $status = $statusMeta[$task['status']];
                                $priority = $priorityMeta[$task['priority']];
                            @endphp
                            <tr data-row
                                data-search="{{ strtolower($task['id'] . ' ' . $task['title']) }}"
                                data-status="{{ $task['status'] }}"
                                data-priority="{{ $task['priority'] }}"
                                data-sort-id="{{ $task['id'] }}"
                                data-sort-title="{{ $task['title'] }}"
                                data-sort-status="{{ $task['status'] }}"
                                data-sort-priority="{{ $task['priority'] }}"
                                data-edit-title="{{ $task['title'] }}"
                                data-edit-label="{{ $task['label'] }}"
                                data-edit-status="{{ $task['status'] }}"
                                data-edit-priority="{{ $task['priority'] }}">
                                <td>
                                    <input type="checkbox" class="checkbox row-select" aria-label="Select row">
                                </td>
                                <td>
                                    <div class="w-20 font-medium">{{ $task['id'] }}</div>
                                </td>
                                <td class="ps-4" data-col="title">
                                    <div class="flex items-center gap-2">
                                        <span class="badge badge-outline shrink-0">{{ ucfirst($task['label']) }}</span>
                                        <span class="truncate font-medium">{{ $task['title'] }}</span>
                                    </div>
                                </td>
                                <td class="ps-4" data-col="status">
                                    <div class="flex w-28 items-center gap-2">
                                        <svg class="icon text-muted-foreground"><use href="#icon-{{ $status['icon'] }}"></use></svg>
                                        <span>{{ $status['label'] }}</span>
                                    </div>
                                </td>
                                <td class="ps-4" data-col="priority">
                                    <div class="flex items-center gap-2">
                                        <svg class="icon text-muted-foreground"><use href="#icon-{{ $priority['icon'] }}"></use></svg>
                                        <span>{{ $priority['label'] }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-ghost btn-icon" data-dropdown-trigger aria-expanded="false" aria-label="Open row actions">
                                            <svg class="icon"><use href="#icon-ellipsis"></use></svg>
                                        </button>
                                        <div data-dropdown class="dropdown-menu hidden w-36">
                                            <button type="button" class="dropdown-item" data-row-edit="task-edit-dialog">
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
                            <td colspan="6" class="h-24 text-center">No results.</td>
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
                    <span data-table-info class="ms-2">Showing 1-10 of 100 row(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span data-page-label class="text-sm font-medium">Page 1 of 10</span>
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
                    <button type="button" class="btn btn-destructive btn-sm" data-dialog-open="task-bulk-delete-dialog" data-bulk-delete>
                        <svg class="icon icon-sm"><use href="#icon-trash-2"></use></svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Create dialog --}}
    <div class="dialog" id="task-create-dialog" role="dialog" aria-modal="true" aria-labelledby="task-create-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="task-create-title" class="dialog-title">Create Task</h2>
                <p class="dialog-description">Fill in the details to add a new task.</p>
            </div>
            <form data-demo-form data-message="Task created." data-close-dialog="task-create-dialog" data-reset>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="label" for="task-title">Title</label>
                        <input id="task-title" name="title" class="input" placeholder="What needs to be done?" required>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="label" for="task-status">Status</label>
                            <select id="task-status" name="status" class="select">
                                @foreach ($statusMeta as $value => $meta)
                                    <option value="{{ $value }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="label" for="task-priority">Priority</label>
                            <select id="task-priority" name="priority" class="select">
                                @foreach ($priorityMeta as $value => $meta)
                                    <option value="{{ $value }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="task-label">Label</label>
                        <select id="task-label" name="label" class="select">
                            <option value="bug">Bug</option>
                            <option value="feature">Feature</option>
                            <option value="documentation">Documentation</option>
                        </select>
                    </div>
                </div>
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit dialog --}}
    <div class="dialog" id="task-edit-dialog" role="dialog" aria-modal="true" aria-labelledby="task-edit-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="task-edit-title" class="dialog-title">Edit Task</h2>
                <p class="dialog-description">Update the details of this task.</p>
            </div>
            <form data-demo-form data-message="Task updated." data-close-dialog="task-edit-dialog">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="label" for="task-edit-name">Title</label>
                        <input id="task-edit-name" name="title" class="input" data-edit-field="title" required>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="label" for="task-edit-status">Status</label>
                            <select id="task-edit-status" name="status" class="select" data-edit-field="status">
                                @foreach ($statusMeta as $value => $meta)
                                    <option value="{{ $value }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="label" for="task-edit-priority">Priority</label>
                            <select id="task-edit-priority" name="priority" class="select" data-edit-field="priority">
                                @foreach ($priorityMeta as $value => $meta)
                                    <option value="{{ $value }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="task-edit-label">Label</label>
                        <select id="task-edit-label" name="label" class="select" data-edit-field="label">
                            <option value="bug">Bug</option>
                            <option value="feature">Feature</option>
                            <option value="documentation">Documentation</option>
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

    {{-- Import dialog --}}
    <div class="dialog" id="task-import-dialog" role="dialog" aria-modal="true" aria-labelledby="task-import-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="task-import-title" class="dialog-title">Import Tasks</h2>
                <p class="dialog-description">Upload a CSV or JSON file containing your tasks.</p>
            </div>
            <form data-demo-form data-message="Tasks imported." data-close-dialog="task-import-dialog" data-reset>
                <label for="task-import-file" class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-dashed p-8 text-center transition-colors hover:bg-muted/50">
                    <svg class="icon icon-xl text-muted-foreground"><use href="#icon-download"></use></svg>
                    <span class="text-sm font-medium">Click to upload a file</span>
                    <span class="text-xs text-muted-foreground">CSV, JSON up to 5MB</span>
                </label>
                <input id="task-import-file" type="file" class="sr-only" accept=".csv,.json">
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk delete dialog --}}
    <div class="dialog" id="task-bulk-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="task-bulk-delete-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="task-bulk-delete-title" class="dialog-title">Delete tasks</h2>
                <p class="dialog-description">Are you sure you want to delete the selected tasks? This action cannot be undone.</p>
            </div>
            <div class="dialog-footer">
                <button type="button" class="btn btn-outline" data-dialog-close>Cancel</button>
                <button type="button" class="btn btn-destructive" data-confirm-bulk-delete>Delete</button>
            </div>
        </div>
    </div>
@endsection
