<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use App\Notifications\Banking\Transaction as Notification;
use App\Jobs\Banking\SendTransactionAsCustomMail;
use App\Http\Requests\Common\CustomMail as Request;

class TransactionEmails extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-banking-transactions')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-banking-transactions')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-banking-transactions')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-banking-transactions')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Transaction  $transaction
     *
     * @return Response
     */
    public function create(Transaction $transaction)
    {
        $email_template = config('type.transaction.' . $transaction->type . '.email_template');

        $notification = new Notification($transaction, $email_template, true);

        $store_route = 'modals.transactions.emails.store';

        $html = view('modals.transactions.email', compact('transaction', 'notification', 'store_route'))->render();

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
        $transaction = Transaction::find($request->get('transaction_id'));

        $email_template = config('type.transaction.' . $transaction->type . '.email_template');

        $response = $this->ajaxDispatch(new SendTransactionAsCustomMail($request, $email_template));

        if ($response['success']) {
            $route = config('type.transaction.' . $transaction->type . '.route.prefix');

            if ($alias = config('type.transaction.' . $transaction->type . '.alias')) {
                $route = $alias . '.' . $route;
            }

            $response['redirect'] = route($route . '.show', $transaction->id);

            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
