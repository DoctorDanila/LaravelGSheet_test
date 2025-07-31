<?php
namespace App\Jobs;

use App\Models\Record;
use App\Models\Setting;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncToGoogleSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $sheetId = Setting::getValue('sheet_id');
        if (!$sheetId) return;

        $sheet = Sheets::spreadsheet($sheetId)->sheet('Sheet1');

        $existing = collect($sheet->all())->values();
        $headers = $existing->shift();
        $existingIds = $existing->pluck(0)->map(fn($val) => (int)$val)->toArray();

        $allowed = Record::allowed()->get();
        $newData = [];

        foreach ($allowed as $record) {
            $newData[$record->id] = [
                $record->id,
                $record->text,
                $record->status->value,
                $record->created_at,
                $record->updated_at,
                $record->comment ?? '',
            ];
        }

        $final = collect($newData)->sortKeys()->values()->toArray();
        array_unshift($final, ['id', 'text', 'status', 'created_at', 'updated_at', 'comment']);

        $sheet->clear();
        $sheet->append($final);
    }
}
