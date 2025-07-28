<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class ImportCSVJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $filename)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $storage_path = storage_path('app/private/uploads');

        $csv = fopen($storage_path . '/' . $this->filename, 'r');

        $data = [];

        while ($row = fgetcsv($csv)) {
            $data[] = $row;
        }

        foreach ($data as $row) {
            [$num, $title, $description, $opt_text] = $row;

            DB::table('csv_data')->insert([
                'unique_num' => $num,
                'title' => $title,
                'description' => $description,
                'opt_text' => $opt_text,
            ]);
        }
    }
}
