<?php
namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    public function index()
    {
        return view('records.index', [
            'records' => Record::all(),
            'sheetUrl' => Setting::getValue('sheet_url')
        ]);
    }

    public function generate()
    {
        $texts = collect(range(1, 1000))->map(function () {
            return [
                'text' => Str::random(20),
                'status' => rand(0, 1) ? 'Allowed' : 'Prohibited',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        Record::insert($texts->toArray());
        return back();
    }

    public function clear()
    {
        Record::truncate();
        return back();
    }

    public function fetch($count = null)
    {
        Artisan::call('records:fetch', $count ? ['--count' => $count] : []);
        return nl2br(Artisan::output());
    }

    public function updateSheet(Request $request)
    {
        $request->validate(['sheet_url' => 'required|url']);
        Setting::setValue('sheet_url', $request->input('sheet_url'));

        // Извлекаем ID таблицы и сохраняем в env-like настройку
        if (preg_match('/\/d\/([\w-]+)/', $request->input('sheet_url'), $matches)) {
            Setting::setValue('sheet_id', $matches[1]);
        }

        return back();
    }

    public function saveComment(Request $request, Record $record)
    {
        $request->validate(['comment' => 'nullable|string|max:1000']);
        $record->comment = $request->input('comment');
        $record->save();
        return back();
    }
}
