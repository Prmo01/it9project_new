<!-- resources/views/components/dropdown.blade.php -->
<div x-data="{ open: false }" class="relative">
    <!-- Trigger -->
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <!-- Dropdown Content -->
    <div 
        x-show="open" 
        @click.away="open = false" 
        class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{ $content }}
    </div>
</div>
