<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Quartz;
use App\Models\User;
use App\Models\BankAccount;
use App\Models\ExpenseSplit;
use App\Models\GrnSession;
use App\Models\BankTransaction;
use App\Models\Item;
use App\Models\GrnItem;

class ReportIndex extends Component
{
    public $activeReport = 'category_summary';

    // Global Filters
    public $dateFrom;
    public $dateTo;
    public $selectedQuartz;
    public $selectedShop;
    public $selectedUser;
    public $selectedBank;
    public $selectedItem;
    public $transactionType;

    // Data containers
    public $reportData = [];
    public $chartData = [];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');

        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        if (!$isAdmin && $user->quartz_id) {
            $this->selectedQuartz = $user->quartz_id;
        }

        $this->generateReport();
    }

    public function setReport($reportName)
    {
        $this->activeReport = $reportName;
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        $this->generateReport();
    }

    public function generateReport()
    {
        switch ($this->activeReport) {
            case 'category_summary':
                $this->generateCategorySummary();
                break;
            case 'shop_expenditure':
                $this->generateShopExpenditure();
                break;
            case 'user_statement':
                $this->generateUserStatement();
                break;
            case 'bank_audit':
                $this->generateBankAudit();
                break;
            case 'item_trend':
                $this->generateItemTrend();
                break;
        }
    }

    private function generateCategorySummary()
    {
        $query = ExpenseSplit::whereHas('grnItem.item.category')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        if (!$isAdmin && $user->quartz_id) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('quartz_id', $user->quartz_id);
            });
        } elseif ($this->selectedQuartz) {
            $query->whereHas('user', function ($q) {
                $q->where('quartz_id', $this->selectedQuartz);
            });
        }

        $results = $query->get()
            ->groupBy(fn($item) => $item->grnItem->item->category->name)
            ->map(fn($group) => $group->sum('amount'));

        $this->reportData = $results;
        $this->chartData = [
            'labels' => $results->keys()->toArray(),
            'series' => $results->values()->map(fn($v) => (float)$v)->toArray()
        ];
    }

    private function generateShopExpenditure()
    {
        $query = GrnSession::whereBetween('session_date', [$this->dateFrom, $this->dateTo])
            ->where('status', 'confirmed');

        if ($this->selectedQuartz) {
            $query->where('quartz_id', $this->selectedQuartz);
        }

        if ($this->selectedShop) {
            $query->where('shop_id', $this->selectedShop);
        }

        $results = $query->with('shop')->get()
            ->groupBy('shop.name')
            ->map(fn($group) => $group->sum(fn($s) => $s->grnItems->sum('total_price')));

        $this->reportData = $results;
        $this->chartData = [
            'labels' => $results->keys()->toArray(),
            'series' => $results->values()->map(fn($v) => (float)$v)->toArray()
        ];
    }

    private function generateUserStatement()
    {
        $query = User::query();

        if ($this->selectedQuartz) {
            $query->where('quartz_id', $this->selectedQuartz);
        }

        if ($this->selectedUser) {
            $query->where('id', $this->selectedUser);
        }

        $users = $query->with('userAccount')->get();

        $this->reportData = collect($users->map(function ($user) {
            $totalDeposits = BankTransaction::where('user_id', $user->id)->where('type', 'deposit')->sum('amount');
            $totalApplied = BankTransaction::where('user_id', $user->id)->where('type', 'withdrawal')->whereNotNull('source_transaction_id')->sum('amount');
            $pendingDebt = ExpenseSplit::where('user_id', $user->id)->where('status', 'pending')->sum('amount');

            return [
                'name' => $user->name,
                'email' => $user->email,
                'pending_debt' => (float)$pendingDebt,
                'available_credit' => (float)max(0, $totalDeposits - $totalApplied),
                'total_deposits' => (float)$totalDeposits
            ];
        }));

        $this->chartData = [
            'labels' => $this->reportData->pluck('name')->toArray(),
            'debt' => $this->reportData->pluck('pending_debt')->toArray(),
            'credit' => $this->reportData->pluck('available_credit')->toArray()
        ];
    }

    private function generateBankAudit()
    {
        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        $query = BankTransaction::whereBetween('transaction_date', [$this->dateFrom, $this->dateTo])
            ->with(['bankAccount', 'user']);

        if (!$isAdmin && $user->quartz_id) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('quartz_id', $user->quartz_id);
            });
        } elseif ($this->selectedBank) {
            $query->where('bank_account_id', $this->selectedBank);
        }

        if ($this->transactionType) {
            $query->where('type', $this->transactionType);
        }

        $this->reportData = $query->latest()->get();
        $this->chartData = [];
    }

    private function generateItemTrend()
    {
        if (!$this->selectedItem) {
            $this->reportData = collect();
            $this->chartData = [];
            return;
        }

        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        $query = GrnItem::where('item_id', $this->selectedItem)
            ->whereHas('grnSession', function ($q) use ($user, $isAdmin) {
                $q->where('status', 'confirmed')
                    ->whereBetween('session_date', [$this->dateFrom, $this->dateTo]);

                if (!$isAdmin && $user->quartz_id) {
                    $q->where('quartz_id', $user->quartz_id);
                } elseif ($this->selectedQuartz) {
                    $q->where('quartz_id', $this->selectedQuartz);
                }
            })
            ->with(['grnSession.shop', 'unit']);

        if ($this->selectedShop) {
            $query->whereHas('grnSession', fn($q) => $q->where('shop_id', $this->selectedShop));
        }

        $results = $query->get()->map(function ($gi) {
            return [
                'date' => $gi->grnSession->session_date->format('Y-m-d'),
                'shop' => $gi->grnSession->shop->name,
                'unit' => $gi->unit->name,
                'unit_price' => (float)$gi->unit_price,
                'total_price' => (float)$gi->total_price,
                'quantity' => (float)$gi->quantity
            ];
        })->sortBy('date');

        $this->reportData = $results;
        $this->chartData = [
            'labels' => $results->pluck('date')->toArray(),
            'series' => $results->pluck('unit_price')->toArray()
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        $shopsQuery = Shop::query();
        $usersQuery = User::query();
        $banksQuery = BankAccount::query();

        if (!$isAdmin && $user->quartz_id) {
            // For shops, technically they are global but maybe filter by shops that have sessions in this quartz?
            // For now, keep shops global unless specific requirements say otherwise.

            // Filter users by quartz
            $usersQuery->where('quartz_id', $user->quartz_id);
        }

        return view('livewire.report.report-index', [
            'quartzes' => Quartz::all(),
            'shops' => $shopsQuery->get(),
            'users' => $usersQuery->get(),
            'banks' => $banksQuery->get(),
            'items' => Item::all(),
        ]);
    }
}
