<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class Event extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Install\UpdateFinished' => [
            'App\Listeners\Update\CreateModuleUpdatedHistory',
            'App\Listeners\Module\UpdateExtraModules',
            'App\Listeners\Update\V30\Version300',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        //'Illuminate\Console\Events\ScheduledTaskStarting' => [
        'Illuminate\Console\Events\CommandStarting' => [
            'App\Listeners\Common\SkipScheduleInReadOnlyMode',
        ],
        'App\Events\Auth\LandingPageShowing' => [
            'App\Listeners\Auth\AddLandingPages',
        ],
        'App\Events\Auth\InvitationCreated' => [
            'App\Listeners\Auth\SendUserInvitation',
        ],
        'App\Events\Auth\UserDeleted' => [
            'App\Listeners\Auth\DeleteUserInvitation',
        ],
        'App\Events\Document\DocumentCreated' => [
            'App\Listeners\Document\CreateDocumentCreatedHistory',
            'App\Listeners\Document\IncreaseNextDocumentNumber',
            'App\Listeners\Document\SettingFieldCreated',
        ],
        'App\Events\Document\DocumentReceived' => [
            'App\Listeners\Document\MarkDocumentReceived',
        ],
        'App\Events\Document\DocumentCancelled' => [
            'App\Listeners\Document\MarkDocumentCancelled',
        ],
        'App\Events\Document\DocumentRecurring' => [
            'App\Listeners\Document\SendDocumentRecurringNotification',
        ],
        'App\Events\Document\DocumentReminded' => [
            'App\Listeners\Document\SendDocumentReminderNotification',
        ],
        'App\Events\Document\PaymentReceived' => [
            'App\Listeners\Document\CreateDocumentTransaction',
            'App\Listeners\Document\SendDocumentPaymentNotification',
        ],
        'App\Events\Document\DocumentSent' => [
            'App\Listeners\Document\MarkDocumentSent',
        ],
        'App\Events\Document\DocumentUpdated' => [
            'App\Listeners\Document\SettingFieldUpdated',
        ],
        'App\Events\Document\DocumentViewed' => [
            'App\Listeners\Document\MarkDocumentViewed',
            'App\Listeners\Document\SendDocumentViewNotification',
        ],
        'App\Events\Install\UpdateFailed' => [
            'App\Listeners\Update\SendNotificationOnFailure',
        ],
        'App\Events\Menu\NotificationsCreated' => [
            'App\Listeners\Menu\ShowInNotifications',
        ],
        'App\Events\Menu\AdminCreated' => [
            'App\Listeners\Menu\ShowInAdmin',
        ],
        'App\Events\Menu\ProfileCreated' => [
            'App\Listeners\Menu\ShowInProfile',
        ],
        'App\Events\Menu\SettingsCreated' => [
            'App\Listeners\Menu\ShowInSettings',
        ],
        'App\Events\Menu\NewwCreated' => [
            'App\Listeners\Menu\ShowInNeww',
        ],
        'App\Events\Menu\PortalCreated' => [
            'App\Listeners\Menu\ShowInPortal',
        ],
        'App\Events\Module\Installed' => [
            'App\Listeners\Module\InstallExtraModules',
            'App\Listeners\Module\FinishInstallation',
        ],
        'App\Events\Module\Uninstalled' => [
            'App\Listeners\Module\FinishUninstallation',
        ],
        'App\Events\Banking\TransactionCreated' => [
            'App\Listeners\Banking\IncreaseNextTransactionNumber',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\Module\ClearCache',
        'App\Listeners\Report\AddDate',
        'App\Listeners\Report\AddAccounts',
        'App\Listeners\Report\AddCustomers',
        'App\Listeners\Report\AddVendors',
        'App\Listeners\Report\AddExpenseCategories',
        'App\Listeners\Report\AddIncomeCategories',
        'App\Listeners\Report\AddIncomeExpenseCategories',
        'App\Listeners\Report\AddSearchString',
        'App\Listeners\Report\AddRowsToTax',
        'App\Listeners\Report\AddBasis',
    ];
}
