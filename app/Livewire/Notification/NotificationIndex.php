<?php

namespace App\Livewire\Notification;

use App\Enum\SMSReceivers;
use App\Jobs\SMSSenderJob;
use Illuminate\Validation\Rule;
use Livewire\Component;

class NotificationIndex extends Component
{
    public array $sms = [
        'send_to' => '',
        'recipients' => '',
        'message' => ''
    ];

    public function send(): void
    {
        $this->validate([
            'sms.send_to' => ['required', Rule::in(SMSReceivers::cases())],
            'sms.recipients' => [Rule::requiredIf($this->sms['send_to'] == SMSReceivers::INDIVIDUAL->value), 'nullable', 'string'],
            'sms.message' => ['required']
        ]);
        if (is_null(school()->communication->sender_id ||school()->communication->api_key)){
            $this->dispatch('error', "SMS cannot be sent because your school sms configuration is not set.");
            return;
        }
        dispatch(new SMSSenderJob(school(), $this->sms['message'], $this->getRecipients()));
        $this->dispatch('success', 'Message sent successfully');
    }

    public function getRecipients(): string
    {
        return match ($this->sms['send_to']) {
            SMSReceivers::STAFF->value => school()->staff()->pluck('phone')->implode(','),
            SMSReceivers::PARENTS->value => school()->parents()->pluck('phone')->implode(','),
            SMSReceivers::STUDENTS->value => school()->students()->pluck('phone')->implode(','),
            SMSReceivers::INDIVIDUAL->value => $this->sms['recipients'],
            default => '',
        };
    }

    public function render()
    {
        return view('livewire.notification.notification-index');
    }
}
