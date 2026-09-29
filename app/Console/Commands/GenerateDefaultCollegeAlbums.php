<?php

namespace App\Console\Commands;

use App\Models\College;
use App\Models\Album;
use Illuminate\Console\Command;

class GenerateDefaultCollegeAlbums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'colleges:generate-default-albums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default photo album for any college that does not already have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking colleges for missing default albums...');

        $colleges = College::where('id', '!=', 1)
            ->where('slug', '!=', 'administration')
            ->whereDoesntHave('albums')
            ->get();

        if ($colleges->isEmpty()) {
            $this->info('All colleges already have default albums.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($colleges as $college) {
            Album::create([
                'college_id'  => $college->id,
                'title'       => 'معرض صور ' . $college->name,
                'title_en'    => 'Photo Gallery - ' . ($college->name_en ?: $college->name),
                'description' => 'الألبوم الافتراضي لصور ' . $college->name,
                'keywords'    => $college->name . ', ' . ($college->name_en ?: ''),
                'active'      => 1,
                'user_id'     => $college->user_id ?? 1,
            ]);

            $this->line("Created default album for: <comment>{$college->name}</comment>");
            $count++;
        }

        $this->info("Done! Created {$count} default albums successfully.");

        return Command::SUCCESS;
    }
}
