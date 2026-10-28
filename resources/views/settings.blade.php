<x-app-layout>
    <div class="mb-6 flex items-center justify-between space-y-2 sm:flex-row sm:space-y-0">
        <h1 class="text-2xl font-bold tracking-tight">Settings</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-3">
            <div class="rounded-xl border border-input bg-background p-5">
                <h3 class="text-sm font-medium mb-4">Account Settings</h3>
                <nav class="space-y-2">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium bg-primary/10 text-primary">Profile</a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-muted-foreground hover:bg-accent/50">Account</a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-muted-foreground hover:bg-accent/50">Appearance</a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-muted-foreground hover:bg-accent/50">Notifications</a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-muted-foreground hover:bg-accent/50">Display</a>
                </nav>
            </div>
        </div>
        <div class="lg:col-span-9">
            <div class="rounded-xl border border-input bg-background p-6">
                <h3 class="text-lg font-medium mb-6">Profile</h3>
                <form class="space-y-6 max-w-xl">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-muted-foreground mb-1">Full Name</label>
                            <input type="text" value="Satya N." class="w-full px-3 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-muted-foreground mb-1">Email</label>
                            <input type="email" value="satnaingdev@gmail.com" class="w-full px-3 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Avatar URL</label>
                        <input type="url" value="/avatars/shadcn.jpg" class="w-full px-3 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>