<?php

namespace App\Jobs;

use App\Models\Data;
use App\Models\Headers;
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
    public function __construct(private $filename)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $headers = Headers::where('filename', $this->filename)->first();

        $headers->status = 'processing';
        $headers->save();

        $storage_path = storage_path('app/private/uploads');

        $csv = fopen($storage_path . '/' . $this->filename, 'r');

        $data = [];

        while ($row = fgetcsv($csv)) {
            $data[] = $row;
        }

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                [$num, $title, $description, $opt_text] = $row;

                $data = new Data();
                $data->unique_num = $num;
                $data->title = $title;
                $data->description = $description;
                $data->opt_text = $opt_text;
            }

            $headers->status = 'completed';
            $headers->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            $headers->status = 'failed';
            $headers->save();
        }
    }
}
