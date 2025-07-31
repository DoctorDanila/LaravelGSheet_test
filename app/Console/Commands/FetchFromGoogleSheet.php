<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Revolution\Google\Sheets\Facades\Sheets;

class FetchFromGoogleSheet extends Command
{
    protected $signature = 'records:fetch {--count= : Limit rows}';
    protected $description = 'Fetch records with comments from Google Sheet';

    public function handle()
    {
        $sheet = Sheets::spreadsheet(config('google.spreadsheet_id'))
            ->sheet('Sheet1')
            ->get();

        $rows = $sheet->toArray();
        $headers = array_shift($rows);

        $bar = $this->output->createProgressBar(count($rows));

        foreach ($rows as $i => $row) {
            if ($this->option('count') && $i >= (int)$this->option('count')) break;

            $id = $row[0] ?? null;
            $comment = $row[count($row)-1] ?? '';

            $this->line("ID: $id | Comment: $comment");
            $bar->advance();
        }

        $bar->finish();
        $this->line("\nDone.");
    }
}
