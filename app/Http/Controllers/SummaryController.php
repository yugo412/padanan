<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\Word;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $expires = CarbonImmutable::now()->addHour();

        $start = Carbon::now()->locale('id_ID')->subDay(now()->format('w'));
        $end = $start->copy()->addWeek();

        if (!empty($request->sub)) {
            $start->subWeek($request->sub);
            $end->subWeek($request->sub);
        }

        $count['new_word'] = Cache::remember('word.count_new', $expires, function () use ($start, $end) {
            return Word::whereDate('created_at', '>=', $start->format('Y-m-d'))
                ->whereDate('created_at', '<=', $end->format('Y-m-d'))
                ->count();
        });

        $count['total_word'] = Cache::remember('word.count', $expires, function () use ($start, $end) {
            return Word::count();
        });

        $count['new_user'] = Cache::remember('user.count_new', $expires, function () use ($start, $end) {
            return User::whereDate('created_at', '>=', $start->format('Y-m-d'))
                ->whereDate('created_at', '<=', $end->format('Y-m-d'))
                ->count();
        });

        $count['total_user'] = Cache::remember('user.count', $expires, function () use ($start, $end) {
            return User::count();
        });

        $count['new_category'] = Cache::remember('category.count_new', $expires, function () use ($start, $end) {
            return Category::whereDate('created_at', '>=', $start->format('Y-m-d'))
                ->whereDate('created_at', '<=', $end->format('Y-m-d'))
                ->count();

        });

        $count['total_category'] = Cache::remember('category.count', $expires, function () use ($start, $end) {
            return Category::count();
        });

        $count['new_report'] = Cache::remember('report.count_new', $expires, function () use ($start, $end) {
            return Report::whereDate('created_at', '>=', $start->format('Y-m-d'))
                ->where('created_at', '<=', $end->format('Y-m-d'))
                ->count();
        });

        $count['total_report'] = Cache::remember('report.count', $expires, function () use ($start, $end) {
            return Report::count();
        });

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
            ]))
            ->with('description', __('Lihat ringkasan kegiatan di :app dalam seminggu', ['app' => config('app.name')]));
    }
}
