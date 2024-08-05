<?php

namespace App\Jobs;

use App\Models\TemplateValidator;
use App\Services\SqlTableCreatorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CreateTablesForTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly TemplateValidator $templateValidator
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SqlTableCreatorService::createForTemplate($this->templateValidator);
    }
}
