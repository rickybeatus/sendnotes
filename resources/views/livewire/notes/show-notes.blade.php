<?php

use Livewire\Volt\Component;
use App\Models\Note;

new class extends Component {
    public function delete($noteId)
    {
        $note = Note::where('id', $noteId)->first();
        $this->authorize('delete', $note);
        $note->delete();
    }

    public function with(): array
    {
        return [
            'notes' => Auth::user()->notes()->orderBy('send_date', 'asc')->get(),
        ];
    }
}; ?>

<div>
    <div class="space-y-2">
        @if ($notes->isEmpty())
            <div class="text-center">
                <p class="text-xl font-bold">No notes yet</p>
                <p class="text-sm">Let's create your first note to send.</p>
                <x-button primary icon-right="plus" class="mt-6" href="{{ route('notes.create') }}" wire:navigate>Create
                    note</x-button>
            </div>
        @else
            <x-button class="mb-12" primary icon-right="plus" href="{{ route('notes.create') }}" wire:navigate>Create
                note</x-button>
            <div class="grid grid-cols-2 gap-6">
                @foreach ($notes as $note)
                    <x-card wire:key='{{ $note->id }}'>
                        <div class="flex justify-between">
                            <div>
                                @can('update', $note)
                                    <a href="{{ route('notes.edit', $note) }}" wire:navigate
                                        class="text-xl font-bold hover:underline hover:text-blue-500">{{ $note->title }}</a>
                                @else
                                    <p class="text-xl font-bold text-gray-500">{{ $note->title }}</p>
                                @endcan
                                <p class="mt-2 text-xs">{{ Str::limit($note->body, 50) }}</p>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($note->send_date)->format('M-d-Y') }}
                            </div>
                        </div>
                        <div class="flex items-end justify-between mt-4 space-x-1">
                            <p class="text-xs">Recipient: <span class="font-semibold">{{ $note->recipient }}</span></p>
                            <div>
                                <x-mini-button rounded icon="eye" href="{{ route('notes.view', $note) }}" />
                                <x-mini-button rounded icon="trash" wire:click="delete('{{ $note->id }}')"
                                    wire:confirm="Are you sure you want to delete this note?" />
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</div>
