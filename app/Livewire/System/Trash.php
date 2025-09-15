<?php

namespace App\Livewire\System;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use ReflectionClass;

class Trash extends Component
{
    public string $model = 'Student';

    public function resetFilter(): void
    {

    }
    public function restore(int $id): void
    {
        $this->modelPath($this->model)::onlyTrashed()->where('school_id', school()->id)->findOrFail($id)->restore();
        $this->dispatch('success', __('Item restored successfully.'));
    }


    public function delete(int $id): void
    {
        $this->modelPath($this->model)::onlyTrashed()->where('school_id', school()->id)->findOrFail($id)->forceDelete();
        $this->dispatch('success', __('Item deleted permanently.'));
    }


    public function restoreAll(): void
    {
        $this->modelPath($this->model)::onlyTrashed() ->where('school_id', school()->id)->restore();
        $this->dispatch('success', __('All items restored successfully.'));
    }


    public function deleteAll(): void
    {
        $this->modelPath($this->model)::onlyTrashed() ->where('school_id', school()->id)->forceDelete();
        $this->dispatch('success', __('All items deleted permanently.'));
    }

    /**
     * @throws Exception
     */
    protected function modelPath($name): string
    {
        $modelPath = 'App\Models';
        $fullModelClass = $modelPath . '\\' . $name;
        if (class_exists($fullModelClass)) {
            return $fullModelClass;
        }
        throw new Exception("Model $name does not exist.");
    }

    public function render()
    {

        return view('livewire.system.trash', [
            'trashes' => $this->modelPath($this->model)::onlyTrashed()
                ->where('school_id', school()->id)->paginate(15),
            'models' => $this->getModelsWithSoftDeletes(['School', 'User'])
        ]);
    }

    public function getModelsWithSoftDeletes(array $exclude = []): array
    {
        $modelNames = [];
        $path = app_path('Models');

        if (!File::isDirectory($path)) {
            return [];
        }

        foreach (File::allFiles($path) as $file) {
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if (in_array($filename, $exclude)) {
                continue;
            }

            $class = "App\\Models\\$filename";

            try {
                if (class_exists($class) && !empty(class_uses_recursive($class)[SoftDeletes::class])) {
                    $modelNames[] = $filename;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $modelNames;

    }
}
