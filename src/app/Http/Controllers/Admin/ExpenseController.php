<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', today()->format('Y-m'));
        try {
            $start = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable $e) {
            $start = today()->startOfMonth();
            $month = $start->format('Y-m');
        }
        $expenses = Expense::whereBetween('spent_on', [$start, $start->copy()->endOfMonth()])->orderByDesc('spent_on')->get();
        return view('admin.expenses.index', compact('expenses', 'month'));
    }

    public function store(Request $request)
    {
        Expense::create($request->validate([
            'label'     => 'required|string|max:150',
            'category'  => 'required|in:' . implode(',', Expense::CATEGORIES),
            'amount_fc' => 'required|integer|min:1',
            'spent_on'  => 'required|date',
        ]));
        return back()->with('success', 'Dépense enregistrée.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Dépense supprimée.');
    }
}
