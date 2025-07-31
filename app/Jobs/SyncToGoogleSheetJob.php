<?php
namespace App\Jobs;

use App\Models\Record;
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
        $sheet = Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('Sheet1');

        $existing = collect($sheet->all())->values();
        $headers = $existing->shift();
        $commentIndex = count($headers); // N+1 комментарий

        $existingIds = $existing->pluck(0)->map(fn($val) => (int)$val)->toArray();
        $allowed = Record::allowed()->get();

        $newData = [];
        foreach ($allowed as $record) {
            $row = [$record->id, $record->text, $record->status->value, $record->created_at, $record->updated_at];
            $newData[$record->id] = $row;
        }

        // Сохраняем комментарии из старых строк
        foreach ($existing as $row) {
            $id = (int)$row[0];
            if (isset($newData[$id]) && isset($row[$commentIndex])) {
                $newData[$id][] = $row[$commentIndex];
            }
        }

        // Формируем новые строки
        $final = collect($newData)->sortKeys()->values()->toArray();
        array_unshift($final, ['id', 'text', 'status', 'created_at', 'updated_at', 'comment']);

        $sheet->clear();
        $sheet->append($final);
    }
}
