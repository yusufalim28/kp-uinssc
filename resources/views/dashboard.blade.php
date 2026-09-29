<x-layouts.admin :title="'Dashboard'">
    <div class="space-y-2">
        <h1 class="text-xl font-bold text-dark">{{ auth()->user()->name }}</h1>
        <p class="text-dark/60 text-sm">
            Role Anda: {{ auth()->user()->getRoleNames()->implode(', ') ?: '(belum ada role)' }}
        </p>
    </div>
</x-layouts.admin>
