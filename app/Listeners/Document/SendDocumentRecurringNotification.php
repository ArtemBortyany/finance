<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentRecurring as Event;
use App\Traits\Documents;

class SendDocumentRecurringNotification
{
    use Documents;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(Event $event)
    {
        $document = $event->document;
        $config = config('type.document.' . $document->type . '.notification');

        if (empty($config) || empty($config['class'])) {
            return;
        }

        if ($document->parent?->recurring?->auto_send == false) {
            return;
        }

        $notification = $config['class'];

        // Notify the customer
        if ($this->canNotifyTheContactOfDocument($document)) {
            $document->contact->notify(new $notification($document, "{$document->type}_recur_customer"));
        }

        // Check if should notify users
        if (! $config['notify_user']) {
            return;
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, "{$document->type}_recur_admin"));
        }
    }
}
