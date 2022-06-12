<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Utilities\Recurring;
use App\Models\Document\Document;
use App\Models\Banking\Transaction;
use Akaunting\Apexcharts\Charts as Apexcharts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Utilities\Date;

class ProfitLoss extends Widget
{
    use Currencies, DateTime;

    public $default_name = 'widgets.profit_loss';

    public $description = 'widgets.description.profit_loss';

    public $report_class = 'App\Reports\ProfitLoss';

    public $start_date;

    public $end_date;

    public $period;

    public function show()
    {
        $this->setFilter();

        $labels = $this->getLabels();

        $income = $this->getIncome();

        $expense = $this->getExpense();

        $colors = $this->getColors();

        $chart = new Apexcharts();

        $options = [
            'legend' => [
                'position'      => 'top',
                'markers' => [
                    'radius'    => '12',
                ],
            ],
        ];

        $chart->setType('bar')
            ->setOptions($options)
            ->setLabels(array_values($labels))
            ->setColors($colors)
            ->setDataset(trans_choice('general.incomes', 1), 'column', array_values($income))
            ->setDataset(trans_choice('general.expenses', 1), 'column', array_values($expense));

        return $this->view('widgets.bar_chart', [
            'chart' => $chart,
        ]);
    }

    public function setFilter(): void
    {
        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $this->start_date = Date::parse(request('start_date', $year_start));
        $this->end_date = Date::parse(request('end_date', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));
        $this->period = request('period', 'month');
    }

    public function getLabels(): array
    {
        $range = request('range', 'custom');

        $start_month = $this->start_date->month;
        $end_month = $this->end_date->month;

        // Monthly
        $labels = [];

        $s = clone $this->start_date;

        if ($range == 'last_12_months') {
            $end_month   = 12;
            $start_month = 0;
        } elseif ($range == 'custom') {
            $end_month   = $this->end_date->diffInMonths($this->start_date);
            $start_month = 0;
        }

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            if ($this->period == 'month') {
                $s->addMonth();
            } else {
                $s->addMonths(3);
                $j -= 2;
            }
        }

        return $labels;
    }

    public function getIncome(): array
    {
        // Invoices
        $query = Document::invoice()->with('recurring', 'totals', 'transactions')->accrued();
        $invoices = $this->applyFilters($query, ['date_field' => 'issued_at'])->get();
        Recurring::reflect($invoices, 'issued_at');
        $totals = $this->calculateTotals($invoices, 'issued_at');

        // Transactions
        $query = Transaction::with('recurring')->income()->isNotDocument()->isNotTransfer();
        $transactions = $this->applyFilters($query, ['date_field' => 'paid_at'])->get();
        Recurring::reflect($transactions, 'paid_at');
        $totals = $this->calculateTotals($transactions, 'paid_at', $totals);

        return $totals;
    }

    public function getExpense(): array
    {
        // Bills
        $query = Document::bill()->with('recurring', 'totals', 'transactions')->accrued();
        $bills = $this->applyFilters($query, ['date_field' => 'issued_at'])->get();
        Recurring::reflect($bills, 'issued_at');
        $totals = $this->calculateTotals($bills, 'issued_at');

        // Transactions
        $query = Transaction::with('recurring')->expense()->isNotDocument()->isNotTransfer();
        $transactions = $this->applyFilters($query, ['date_field' => 'paid_at'])->get();
        Recurring::reflect($transactions, 'paid_at');
        $totals = $this->calculateTotals($transactions, 'paid_at', $totals);

        return $totals;
    }

    public function getColors(): array
    {
        return [
            '#8bb475',
            '#fb7185',
        ];
    }

    public function calculateTotals($items, $date_field, $totals = []): array
    {
        $date_format = 'Y-m';

        if ($this->period == 'month') {
            $n = 1;
            $start_date = $this->start_date->format($date_format);
            $end_date = $this->end_date->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $this->start_date->quarter;
            $end_date = $this->end_date->quarter;
            $next_date = $start_date;
        }

        $s = clone $this->start_date;

        //$totals[$start_date] = 0;
        while ($next_date <= $end_date) {
            if (! isset($totals[$next_date])) {
                $totals[$next_date] = 0;
            }

            if ($this->period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $this->setTotals($totals, $items, $date_field, $date_format);

        return $totals;
    }

    public function setTotals(&$totals, $items, $date_field, $date_format): void
    {
        foreach ($items as $item) {
            if ($this->period == 'month') {
                $i = Date::parse($item->$date_field)->format($date_format);
            } else {
                $i = Date::parse($item->$date_field)->quarter;
            }

            if (! isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
        }

        $precision = config('money.' . setting('default.currency') . '.precision');

        foreach ($totals as $key => $value) {
            $totals[$key] = round($value, $precision);
        }
    }
}
