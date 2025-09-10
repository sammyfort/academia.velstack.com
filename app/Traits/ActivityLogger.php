<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait ActivityLogger
{
    public function logCreated($model, $identifier, $route = null,  $linkText = 'View', $message = null): void
    {
        if (auth()->check()) {
            $logMessage = $message ?? auth()->user()->fullname . ' created a new ' . str_replace('_', '',class_basename($model)) . " '$identifier'" . '.';

            if ($route) {
                $profileLink = route($route, $model->uuid);
                $logMessage .= ' <a href="' . $profileLink . '">' . $linkText . '</a>';
            }

            activity()
                ->event('created')
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $model->getAttributes()])
                ->log($logMessage);
        }

    }

    public function logUpdated($model, $identifier, $route = null, $linkText = 'View', $message = null): void
    {

        if (auth()->check()){
            $profileLink = null;
            if ($route) {
                $profileLink = route($route, $model->uuid);
            }

            $changes = $model->getChanges();
            $original = $model->getOriginal();

            $excludedFields = ['password', 'updated_at'];
            $changes = Arr::except($changes, $excludedFields);
            $original = Arr::except($original, $excludedFields);

            $changes = convertColumns($changes);
            $original = convertColumns($original);

            $changesDescription = collect($changes)
                ->map(function ($newValue, $key) use ($original) {
                    $oldValue = $original[$key] ?? '(no previous value)';
                    return "changed $key from '$oldValue' to '$newValue'";
                })
                ->join("<br>");

            $logMessage = $message ?? auth()->user()->fullname . ' updated ' .str_replace('_', '',class_basename($model)) . " '$identifier'" . '.';

            if ($profileLink) {
                $logMessage .= "<br><br>" . $changesDescription . "<br><a href='" . $profileLink . "'>" . $linkText . "</a>";
            } else {
                $logMessage .= "<br><br>" . $changesDescription;
            }

            activity()
                ->event('updated')
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $changes, 'old' => $original])
                ->log($logMessage);
        }

    }

    public function logDeleted($model, $identifier, $message = null): void
    {
        if (auth()->check()){
            $logMessage = $message ?? auth()->user()->fullname.  " delete ". str_replace('_', '',class_basename($model)) . " '$identifier'";
            activity()
                ->event('deleted')
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $model->getAttributes()])
                ->log($logMessage);
        }

    }

    public function logRestored($model, $identifier, $route = null, $linkText = 'View ', $message = null): void
    {
        if (auth()->check()){
            $profileLink = null;
            if ($route) {
                $profileLink = route($route, $model->uuid);
            }
            $logMessage = $message ?? auth()->user()->fullname .  " restored ". str_replace('_', '',class_basename($model)) . " '$identifier'";

            if ($profileLink) {
                $logMessage .= ' <a href="' . $profileLink . '">' . $linkText . '</a>';
            }
            activity()
                ->event('restored')
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $model->getAttributes()])
                ->log($logMessage);
        }

    }

}
