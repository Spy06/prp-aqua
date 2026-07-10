<x-layouts::app :title="'Detail Temuan #' . $temuan->id">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl py-2">
        <livewire:detail-temuan :temuan="$temuan" />
    </div>
</x-layouts::app>
