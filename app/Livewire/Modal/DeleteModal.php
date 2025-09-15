<?php

namespace App\Livewire\Modal;

use Livewire\Component;

class DeleteModal extends Component
{
    public $model;
    public int $modelId;
    public string $recordName;
    public string $title = '';
    public string $description = '';
    public string $buttonText = '';


    protected $listeners = [
       'open-delete-modal' => 'confirmDeletion'
    ];

    public function confirmDeletion($model, $modelId, $recordName = null, $title = null, $description = null, $buttonText = 'Yes, Delete'): void
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->recordName = $recordName;

        $this->title = $title ?? "Are you sure you want to delete this $recordName ?";
        $this->description = $description ?? " Deleting your $recordName will remove all of your information from our database.";
        $this->buttonText = $buttonText;

    }

    public function delete(): void
    {
        if ($this->model && $this->modelId) {
            $modelPath = "App\\Models\\" . $this->model;
            $modelInstance = app($modelPath)::findOrFail($this->modelId);
            hasPermission("delete.$this->model", true);
            $modelInstance->delete();

            $this->dispatch('recordDeleted', $this->modelId);
           $this->dispatch('success', 'Record deleted.');
           $this->closeModal();
        }
    }

    public function closeModal(): void
    {

        $this->dispatch('close-delete-modal');
        $this->reset(['model', 'modelId', 'recordName']);
    }
    public function render()
    {
        return view('livewire.modal.delete-modal');
    }
}
