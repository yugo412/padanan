<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Word;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SummaryController extends Controller
{
    /**
     * @param Request $request
     * @return View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function weekly(Request $request): View
    {
        $this->validate($request, [
            'sub' => ['nullable', 'integer'],
        ]);

        $start = Carbon::now()->locale('id_ID')->subDay(now()->format('w'));
        $end = $start->copy()->addWeek();

        if (!empty($request->sub)) {
            $start->subWeek($request->sub);
            $end->subWeek($request->sub);
        }

        $count['new_word'] = Word::whereDate('created_at', '>=', $start->format('Y-m-d'))
            ->whereDate('created_at', '<=', $end->format('Y-m-d'))
            ->count();

        $count['total_word'] = Word::count();

        $count['new_user'] = User::whereDate('created_at', '>=', $start->format('Y-m-d'))
            ->whereDate('created_at', '<=', $end->format('Y-m-d'))
            ->count();

        $count['total_user'] = User::count();

        $count['new_category'] = Category::whereDate('created_at', '>=', $start->format('Y-m-d'))
            ->whereDate('created_at', '<=', $end->format('Y-m-d'))
            ->count();

        $count['total_category'] = Category::count();

        $words = Word::orderByDesc('created_at')
            ->with('user')
            ->where('created_at', '>=', $start->format('Y-m-d'))
            ->where('created_at', '<=', $end->format('Y-m-d'))
            ->get();

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);

        return view('summary.weekly', compact('count', 'number', 'start', 'end'))
            ->with('words', $words)
            ->with('title', __('Ringkasan Mingguan (:day_start :week_start - :day_end :week_end)', [
                'day_start' => $start->format('d'),
                'week_start' => $start->monthName,
                'day_end' => $end->format('d'),
                'week_end' => $end->monthName,
            ]));
    }
}
