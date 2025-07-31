<?php
namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    public function index()
    {
        return view('records.index', ['records' => Record::all()]);
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
}
