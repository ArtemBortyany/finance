<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionSent;
use App\Models\Banking\Transaction;
use App\Notifications\Banking\Transaction as Notification;

class SendTransactionAsCustomMail extends Job
{
    public function __construct($request, $template_alias)
    {
        $this->request = $request;
        $this->template_alias = $template_alias;
    }

    public function handle(): void
    {
        $transaction = Transaction::find($this->request->get('transaction_id'));

        $custom_mail = $this->request->only(['to', 'subject', 'body']);

        if ($this->request->has('user_email')) {
            $custom_mail['cc'] = user()->email;
        }

        // Notify the contact
        $transaction->contact->notify(new Notification($transaction, $this->template_alias, true, $custom_mail));

        event(new TransactionSent($transaction));
    }
}
