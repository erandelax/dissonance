<?php

namespace App\Console\Commands;

use App\Repositories\ProjectRepository;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

final class CreateProjectCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'create:project {host} {port?}';

    /**
     * @var string
     */
    protected $description = 'Creates a new project in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param \App\Repositories\ProjectRepository $projectRepository
     *
     * @return int
     */
    public function handle(ProjectRepository $projectRepository): int
    {
        $host = $this->argument('host');
        $port = $this->argument('port');
        if (null !== $port) {
            $port = (int) $port;
        }
        try {
            $projectRepository->createFromHostAndPort($host, $port);
            $this->info('Project created');
        } catch (QueryException $exception) {
            $this->error($exception->getMessage());
        }
        return self::SUCCESS;
    }
}
