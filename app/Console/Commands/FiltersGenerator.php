<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class FiltersGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filter:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a query filter template';

    private $targetDir;
    private $stubFile;
    private $targetFile;
    private $fileName;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->targetDir = app_path().'/QueryFilter';
        $this->stubFile = $this->targetDir."/QueryFilterStub.stub";

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fileName = ucfirst(strtolower($this->argument('name')));
        $this->targetFile = "{$this->targetDir}/{$this->fileName}.php";

        if(file_exists($this->targetFile)){
            $this->info('query filter already existed');
            return;
        }

        $this->ensureDirExists()->ensureStubExists()->make();
        $this->info('query filter generation is completed');
    }

    private function stub()
    {
        return '<?php

namespace App\QueryFilter;

class Test extends Filter
{
    public function apply($query)
    {
        return $query->where($this->getFilterName(), request($this->getFilterName()) );
    }
}';
    }

    public function ensureDirExists()
    {
        if( !is_dir($this->targetDir) ){
            mkdir($this->targetDir);
        }
        return $this;
    }

    public function ensureStubExists()
    {
        if(!file_exists($this->stubFile)){
            file_put_contents($this->stubFile,$this->stub());
        }
        return $this;
    }

    public function make()
    {
        file_put_contents($this->targetFile,
            str_replace('Test',$this->fileName, file_get_contents($this->stubFile))
        );
    }
}
