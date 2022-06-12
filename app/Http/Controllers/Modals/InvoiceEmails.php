<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;
use App\Jobs\Document\SendDocumentAsCustomMail;
use App\Http\Requests\Common\CustomMail as Request;

class InvoiceEmails extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-sales-invoices')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-sales-invoices')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-sales-invoices')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-sales-invoices')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Document  $invoice
     *
     * @return Response
     */
    public function create(Document $invoice)
    {
        $notification = new Notification($invoice, 'invoice_new_customer', true);

        $store_route = 'modals.invoices.emails.store';

        $html = view('modals.invoices.email', compact('invoice', 'notification', 'store_route'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans_choice('general.email', 1)]),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text' => trans('general.send'),
                        'class' => 'disabled:bg-green-100',
                    ]
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new SendDocumentAsCustomMail($request, 'invoice_new_customer'));

        if ($response['success']) {
            $invoice = Document::find($request->get('document_id'));

            $route = config('type.document.' . $invoice->type . '.route.prefix');

            if ($alias = config('type.document.' . $invoice->type . '.alias')) {
                $route = $alias . '.' . $route;
            }

            $response['redirect'] = route($route . '.show', $invoice->id);

            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
