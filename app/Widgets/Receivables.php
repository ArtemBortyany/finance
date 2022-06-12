<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Document\Document;
use App\Utilities\Date;

class Receivables extends Widget
{
    public $default_name = 'widgets.receivables';

    public $description = 'widgets.description.receivables';

    public $report_class = 'Modules\AgedReceivablesPayables\Reports\AgedReceivables';

    public function show()
    {
        $open = $overdue = 0;

        $periods = [
            'overdue_1_30' => 0,
            'overdue_30_60' => 0,
            'overdue_60_90' => 0,
            'overdue_90_un' => 0,
        ];

        $query = Document::invoice()->with('transactions')->accrued()->notPaid();

        $this->applyFilters($query, ['date_field' => 'issued_at'])->each(function ($invoice) use (&$open, &$overdue, &$periods) {
            list($open_tmp, $overdue_tmp) = $this->calculateDocumentTotals($invoice);

            $open += $open_tmp;
            $overdue += $overdue_tmp;

            foreach ($periods as $period_name => $period_amount) {
                $arr = explode('_', $period_name);

                if ($arr[2] == 'un') {
                    $arr[2] = '9999';
                }

                $start = Date::today()->subDays($arr[2])->toDateString() . ' 00:00:00';
                $end = Date::today()->subDays($arr[1])->toDateString() . ' 23:59:59';

                if (! Date::parse($invoice->due_at)->isBetween($start, $end)) {
                    continue;
                }

                $periods[$period_name] += $overdue_tmp;
            }
        });

        foreach ($periods as $period_name => $period_amount) {
            $periods[$period_name] = money($period_amount, setting('default.currency'), true);
        }

        $has_progress = !empty($open) || !empty($overdue);
        $progress = !empty($open) ? (int) ($open * 100) / ($open + $overdue) : 0;

        $grand = $open + $overdue;

        $totals = [
            'grand'     => money($grand, setting('default.currency'), true),
            'open'      => money($open, setting('default.currency'), true),
            'overdue'   => money($overdue, setting('default.currency'), true),
        ];

        $grand_total_text = trans('widgets.total_unpaid_invoices');

        return $this->view('widgets.receivables_payables', [
            'totals'            => $totals,
            'has_progress'      => $has_progress,
            'progress'          => $progress,
            'periods'           => $periods,
            'grand_total_text'  => $grand_total_text,
        ]);
    }
}
