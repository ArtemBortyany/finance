<?php

namespace App\Notifications\Banking;

use App\Abstracts\Notification;
use App\Models\Banking\Transaction as Model;
use App\Models\Setting\EmailTemplate;
use App\Traits\Transactions;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Transaction extends Notification
{
    use Transactions;

    /**
     * The transaction model.
     *
     * @var object
     */
    public $transaction;

    /**
     * The email template.
     *
     * @var EmailTemplate
     */
    public $template;

    /**
     * Should attach pdf or not.
     *
     * @var bool
     */
    public $attach_pdf;

    /**
     * Create a notification instance.
     */
    public function __construct(Model $transaction = null, string $template_alias = null, bool $attach_pdf = false)
    {
        parent::__construct();

        $this->transaction = $transaction;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $message = $this->initMailMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $message->attach($this->storeTransactionPdfAndGetPath($this->transaction), [
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $this->initArrayMessage();

        return [
            'template_alias' => $this->template->alias,
            'title' => trans('notifications.menu.' . $this->template->alias . '.title'),
            'description' => trans('notifications.menu.' . $this->template->alias . '.description', $this->getTagsBinding()),
            'transaction_id' => $this->transaction->id,
            'contact_name' => $this->transaction->contact->name,
            'amount' => $this->transaction->amount,
            'transaction_date' => company_date($this->transaction->paid_at),
        ];
    }

    public function getTags(): array
    {
        return [
            '{payment_amount}',
            '{payment_date}',
            '{payment_guest_link}',
            '{payment_admin_link}',
            '{payment_portal_link}',
            '{contact_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement(): array
    {
        return [
            money($this->transaction->amount, $this->transaction->currency_code, true),
            company_date($this->transaction->paid_at),
            URL::signedRoute('signed.payments.show', [$this->transaction->id]),
            route('transactions.show', $this->transaction->id),
            route('portal.payments.show', $this->transaction->id),
            $this->transaction->contact->name,
            $this->transaction->company->name,
            $this->transaction->company->email,
            $this->transaction->company->tax_number,
            $this->transaction->company->phone,
            nl2br(trim($this->transaction->company->address)),
        ];
    }
}
