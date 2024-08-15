<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component {
    #[Validate('required|string|min:5')]
    public $noteTitle = '';

    #[Validate('required|string|min:20')]
    public $noteBody;

    #[Validate('required|email')]
    public $noteRecipient;

    #[Validate('required|date')]
    public $noteSendDate;

    public function submit()
    {
        $this->validate();

        auth()
            ->user()
            ->notes()
            ->create([
                'title' => $this->noteTitle,
                'body' => $this->noteBody,
                'recipient' => $this->noteRecipient,
                'send_date' => $this->noteSendDate,
                'is_published' => true,
            ]);

        redirect(route('notes.index'));
    }
}; ?>

<div>
    <form wire:submit='submit' class="space-y-4">
        <x-input wire:model="noteTitle" label="Note Title" placeholder="It's been a great day." />
        <x-textarea wire:model="noteBody" label="Your Note" placeholder="Share all your thoughts with your friend." />
        <x-input icon="user" wire:model="noteRecipient" label="Recipient" placeholder="yourfriend@email.com"
            type="email" />
        <x-input icon="calendar" wire:model="noteSendDate" type="date" label="Send Date" />
        <div class="pt-4">
            <x-button type="submit" primary right-icon="calendar" spinner>Schedule Note</x-button>
        </div>
        <x-errors />
    </form>
</div>
