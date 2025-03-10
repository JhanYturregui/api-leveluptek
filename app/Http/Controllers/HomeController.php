<?php

namespace App\Http\Controllers;

use App\Models\CashSession;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageTitle = 'Inicio';
        $hideSecondHeader = false;

        $cashSession = CashSession::getOpenCashSessionByRole();
        $cashSessionId = $cashSession ? $cashSession->id : 0;

        $cashSessionData = [];
        $cashSessionInRegisterData = [];
        $cashSessionUser = null;
        if ($cashSessionId > 0) {
            $cashSessionUser = $cashSession->user;
            $cashSession = CashSession::withCount([
                'sales',
                'purchases',
                'cash_transactions as cash_transactions_incomes_count' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_INCOME'));
                },
                'cash_transactions as cash_transactions_expenses_count' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_EXPENSE'));
                },
                'cash_transactions as cash_transactions_pays_count' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_PAY'));
                },
            ])
                ->withSum('sales', 'total_amount')
                ->withSum('sales', 'partial_payment')
                ->withSum('purchases', 'total_amount')
                ->withSum(['cash_transactions as cash_transactions_incomes_sum_amount' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_INCOME'));
                }], 'amount')
                ->withSum(['cash_transactions as cash_transactions_expenses_sum_amount' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_EXPENSE'));
                }], 'amount')
                ->withSum(['cash_transactions as cash_transactions_pays_sum_amount' => function ($query) {
                    $query->where('type', config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_PAY'));
                }], 'amount')
                ->find($cashSessionId);

            $openingAmount = CashSession::where('id', $cashSessionId)->value('opening_amount');
            $totalCashTransactions = $cashSession->cash_transactions_incomes_sum_amount - $cashSession->cash_transactions_expenses_sum_amount + $cashSession->cash_transactions_pays_sum_amount;
            $partialBalance =  $totalCashTransactions - $cashSession->purchases_sum_total_amount;

            $totalBalance = $cashSession->sales_sum_total_amount + $partialBalance;
            $iconBalance = $totalBalance >= 0 ? 'fas fa-arrow-up text-success mr-3' : 'fas fa-arrow-down text-warning mr-3';

            $totalBalanceInRegister = $cashSession->sales_sum_partial_payment + $partialBalance + $openingAmount;
            $iconBalanceInRegister = $totalBalanceInRegister >= 0 ? 'fas fa-arrow-up text-success mr-3' : 'fas fa-arrow-down text-warning mr-3';

            /* $totalBalance = $cashSession->sales_sum_total_amount - $cashSession->purchases_sum_total_amount + $cashSession->cash_transactions_incomes_sum_amount - $cashSession->cash_transactions_expenses_sum_amount + $cashSession->cash_transactions_pays_sum_amount;
            $iconBalance = $totalBalance >= 0 ? 'fas fa-arrow-up text-success mr-3' : 'fas fa-arrow-down text-warning mr-3'; */

            $cashSessionData = [
                'sales' => [
                    'title' => 'Ventas',
                    'count' => $cashSession->sales_count,
                    'total' => $cashSession->sales_sum_total_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'purchases' => [
                    'title' => 'Compras',
                    'count' => $cashSession->purchases_count,
                    'total' => $cashSession->purchases_sum_total_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-down text-warning mr-3',
                ],
                'incomes' => [
                    'title' => 'Movimientos de caja: Ingresos',
                    'count' => $cashSession->cash_transactions_incomes_count,
                    'total' => $cashSession->cash_transactions_incomes_sum_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'expenses' => [
                    'title' => 'Movimientos de caja: Egresos',
                    'count' => $cashSession->cash_transactions_expenses_count,
                    'total' => $cashSession->cash_transactions_expenses_sum_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-down text-warning mr-3',
                ],
                'pays' => [
                    'title' => 'Movimientos de caja: Pagos',
                    'count' => $cashSession->cash_transactions_pays_count,
                    'total' => $cashSession->cash_transactions_pays_sum_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'balance' => [
                    'title' => 'TOTAL',
                    'total' => number_format($totalBalance, 2),
                    'icon' => $iconBalance,
                ],
            ];

            $cashSessionInRegisterData = [
                'opening_amount' => [
                    'title' => 'Monto de Apertura',
                    'total' => $openingAmount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'sales' => [
                    'title' => 'Ventas',
                    'total' => $cashSession->sales_sum_partial_payment ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'purchases' => [
                    'title' => 'Compras',
                    'total' => $cashSession->purchases_sum_total_amount ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-down text-warning mr-3',
                ],
                'transactions' => [
                    'title' => 'Movimientos de caja',
                    'total' => number_format($totalCashTransactions, 2) ?? number_format(0, 2),
                    'icon' => 'fas fa-arrow-up text-success mr-3',
                ],
                'balance' => [
                    'title' => 'TOTAL',
                    'total' => number_format($totalBalanceInRegister, 2),
                    'icon' => $iconBalanceInRegister,
                ],
            ];
        }

        return view('dashboard', compact('pageTitle', 'hideSecondHeader', 'cashSessionId', 'cashSessionData', 'cashSessionUser', 'cashSessionInRegisterData'));
    }
}
