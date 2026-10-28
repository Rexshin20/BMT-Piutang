@extends('layouts.app')

@section('title', 'Appearance Settings')

@section('content')
    <x-settings-layout>
        <div class="flex flex-col">
            <div>
                <h3 class="text-lg font-medium">Appearance</h3>
                <p class="text-sm text-muted-foreground">Customize the appearance of the app. Automatically switch between day and night themes.</p>
            </div>
            <div class="my-4 border-t"></div>

            <form id="appearance-form" data-demo-form data-message="Appearance updated." class="space-y-8 lg:max-w-xl">
                <div class="space-y-2">
                    <label class="label" for="appearance-font">Font</label>
                    <select id="appearance-font" name="font" data-font-select class="select w-full sm:w-52">
                        <option value="inter">Inter</option>
                        <option value="manrope">Manrope</option>
                        <option value="system">System</option>
                    </select>
                    <p class="form-description" data-font-desc>Set the font you want to use in the dashboard.</p>
                </div>

                <div class="space-y-2">
                    <span class="label">Theme</span>
                    <p class="form-description">Select the theme for the dashboard.</p>

                    <div class="grid max-w-md grid-cols-3 gap-4 pt-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="theme" value="light" class="sr-only" data-theme-option>
                            <span class="block rounded-md border-2 border-muted p-1 hover:border-accent has-[:checked]:border-primary">
                                <span class="block space-y-2 rounded-sm bg-[#ecedef] p-2">
                                    <span class="block space-y-2 rounded-md bg-white p-2 shadow-sm">
                                        <span class="block h-2 w-14 rounded-lg bg-[#ecedef]"></span>
                                        <span class="block h-2 w-20 rounded-lg bg-[#ecedef]"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-white p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-[#ecedef]"></span>
                                        <span class="h-2 w-20 rounded-lg bg-[#ecedef]"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-white p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-[#ecedef]"></span>
                                        <span class="h-2 w-20 rounded-lg bg-[#ecedef]"></span>
                                    </span>
                                </span>
                            </span>
                            <span class="block w-full p-2 text-center font-normal">Light</span>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="theme" value="dark" class="sr-only" data-theme-option>
                            <span class="block rounded-md border-2 border-muted bg-popover p-1 hover:bg-accent has-[:checked]:border-primary">
                                <span class="block space-y-2 rounded-sm bg-slate-950 p-2">
                                    <span class="block space-y-2 rounded-md bg-slate-800 p-2 shadow-sm">
                                        <span class="block h-2 w-14 rounded-lg bg-slate-400"></span>
                                        <span class="block h-2 w-20 rounded-lg bg-slate-400"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-slate-800 p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-slate-400"></span>
                                        <span class="h-2 w-20 rounded-lg bg-slate-400"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-slate-800 p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-slate-400"></span>
                                        <span class="h-2 w-20 rounded-lg bg-slate-400"></span>
                                    </span>
                                </span>
                            </span>
                            <span class="block w-full p-2 text-center font-normal">Dark</span>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="theme" value="system" class="sr-only" data-theme-option>
                            <span class="block rounded-md border-2 border-muted p-1 hover:border-accent has-[:checked]:border-primary">
                                <span class="block space-y-2 rounded-sm bg-gradient-to-br from-[#ecedef] to-slate-800 p-2">
                                    <span class="block space-y-2 rounded-md bg-white/80 p-2 shadow-sm">
                                        <span class="block h-2 w-14 rounded-lg bg-[#ecedef]"></span>
                                        <span class="block h-2 w-20 rounded-lg bg-[#ecedef]"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-slate-800/80 p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-slate-500"></span>
                                        <span class="h-2 w-20 rounded-lg bg-slate-500"></span>
                                    </span>
                                    <span class="flex items-center gap-2 rounded-md bg-slate-800/80 p-2 shadow-sm">
                                        <span class="h-4 w-4 rounded-full bg-slate-500"></span>
                                        <span class="h-2 w-20 rounded-lg bg-slate-500"></span>
                                    </span>
                                </span>
                            </span>
                            <span class="block w-full p-2 text-center font-normal">System</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update preferences</button>
            </form>
        </div>
    </x-settings-layout>
@endsection
