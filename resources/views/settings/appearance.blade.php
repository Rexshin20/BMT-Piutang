@extends('layouts.app')

@section('title', 'Pengaturan Tampilan')

@section('content')
    <x-settings-layout>
        <form id="appearance-form" data-demo-form data-message="Preferensi tampilan disimpan." class="max-w-2xl space-y-6">
            <section class="card">
                <div class="card-header">
                    <h2 class="card-title">Tampilan</h2>
                    <p class="card-description">Atur font dan tema yang dipakai di aplikasi.</p>
                </div>
                <div class="card-content space-y-6">
                    <div class="space-y-2">
                        <label class="label" for="appearance-font">Font</label>
                        <select id="appearance-font" name="font" data-font-select class="select sm:w-52">
                            <option value="inter">Inter</option>
                            <option value="manrope">Manrope</option>
                            <option value="system">System</option>
                        </select>
                        <p class="form-description" data-font-desc>Pilih font yang ingin dipakai di aplikasi.</p>
                    </div>

                    <div class="space-y-2">
                        <span class="label">Tema</span>
                        <p class="form-description">Pilih tema terang, gelap, atau ikuti pengaturan perangkat.</p>

                        <div class="grid max-w-md grid-cols-3 gap-4 pt-2">
                            <label class="cursor-pointer">
                                <span class="block rounded-md border-2 border-muted p-1 hover:border-accent has-[:checked]:border-primary">
                                    <input type="radio" name="theme" value="light" class="sr-only" data-theme-option>
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
                                <span class="block w-full p-2 text-center text-sm">Terang</span>
                            </label>

                            <label class="cursor-pointer">
                                <span class="block rounded-md border-2 border-muted bg-popover p-1 hover:bg-accent has-[:checked]:border-primary">
                                    <input type="radio" name="theme" value="dark" class="sr-only" data-theme-option>
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
                                <span class="block w-full p-2 text-center text-sm">Gelap</span>
                            </label>

                            <label class="cursor-pointer">
                                <span class="block rounded-md border-2 border-muted p-1 hover:border-accent has-[:checked]:border-primary">
                                    <input type="radio" name="theme" value="system" class="sr-only" data-theme-option>
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
                                <span class="block w-full p-2 text-center text-sm">Sistem</span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">Simpan preferensi</button>
            </div>
        </form>
    </x-settings-layout>
@endsection
