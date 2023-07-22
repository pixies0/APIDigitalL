<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;

class repo extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:repo {model} {table_name} {--pasta=}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Criar arquivos necessarios para back';


  protected $stubs = [
    'service'   => '',
    'repository' => '',
  ];

  protected $fileManager;

  protected $model;
  protected $table_name;

  protected $modelName;

  protected $pasta = null;
  protected $appNamespace;


  public function __construct()
  {
    parent::__construct();

    $this->fileManager = app('files');
    $this->appNamespace = app()->getNamespace();


    $this->stubs = [
      'service'   => base_path('stubs\defaultService.stub'),
      'repository' => base_path('stubs\defaultRepository.stub'),
      'controller' => base_path('stubs\controller.stub'),
      'model' => base_path('stubs\repo.model.stub'),
    ];
  }

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->pasta = $this->option('pasta');
    $this->table_name = $this->argument('table_name');

    $this->checkModel();

    $this->createService();
    $this->createRepository();
    $this->createController();
  }

  public function isResponsePositive($response)
  {
    return in_array(strtolower($response), ['y', 'yes', 's', 'sim']);
  }

  /**
   * Create a new service
   */
  protected function createService()
  {
    $content = $this->fileManager->get($this->stubs['service']);

    if (!empty($this->pasta)) {
      $pasta =  "\\" .   str_replace('/', '\\', $this->pasta);
      $repository_ns = $pasta . '\\' . $this->modelName . "Repository";
      $services_ns =   $pasta;
    } else {
      $repository_ns =  $this->modelName . "Repository";
      $services_ns =  '';
    }

    $replacements = [
      '%namespaces.services%' => $this->appNamespace . 'Services',
      '%modelName%'           => $this->modelName,
      '%repository.ns%'       => $repository_ns,
      '%services.ns%'         => $services_ns,
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $content);

    if (!empty($this->pasta)) {
      $pasta_salvar =  "/" . str_replace('\\', '/', $this->pasta) . '/';
    } else {
      $pasta_salvar = "/";
    }

    $fileName      = $this->modelName . 'Service';
    $fileDirectory = app()->basePath() . '/app/Services' . $pasta_salvar;
    $filePath      = $fileDirectory .  $fileName . '.php';

    if (!$this->fileManager->exists($fileDirectory)) {
      $this->fileManager->makeDirectory($fileDirectory, 0755, true);
    }

    if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
      $response = $this->ask("O Service [{$fileName}] ja existe. Deseja subsituir?", 'Sim|s');

      if (!$this->isResponsePositive($response)) {
        $this->line("❌ O Service [{$fileName}] nao sera substituido.");
        return;
      }

      $this->fileManager->put($filePath, $content);
    } else {
      $this->fileManager->put($filePath, $content);
    }

    $this->line("✅ O Service [{$fileName}] foi criado com sucesso..");

    return ['Services' . '\\' . $fileName, $fileName];
  }

  /**
   * Create a new repository
   */
  protected function createRepository()
  {
    $content = $this->fileManager->get($this->stubs['repository']);

    if (isset($this->pasta)) {
      $repository_ns = "\\" .  str_replace('/', '\\', $this->pasta);
    } else {
      $repository_ns =  "";
    }

    $replacements = [
      '%model%'         => $this->model,
      '%modelName%'     => $this->modelName,
      '%repository.ns%' => $repository_ns
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $content);

    if (!empty($this->pasta)) {
      $pasta_salvar =  "/" . str_replace('\\', '/', $this->pasta) . '/';
    } else {
      $pasta_salvar = "/";
    }

    $fileName      = $this->modelName . 'Repository';
    $fileDirectory = app()->basePath() . '/app/Repositories' . $pasta_salvar;
    $filePath      = $fileDirectory . $fileName . '.php';

    // Check if the directory exists, if not create...
    if (!$this->fileManager->exists($fileDirectory)) {
      $this->fileManager->makeDirectory($fileDirectory, 0755, true);
    }

    if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
      $response = $this->ask("O repository [{$fileName}] ja existe. Vc deseja substituir?", 'Sim|s');

      if (!$this->isResponsePositive($response)) {
        $this->line("❌ O repository [{$fileName}] nao pode ser substituido.");
        return;
      }
    }

    $this->line("✅ O repository [{$fileName}] foi criado com sucesso.");

    $this->fileManager->put($filePath, $content);
  }

  /**
   * Create a new repository
   */
  protected function createController()
  {
    $content = $this->fileManager->get($this->stubs['controller']);

    if (isset($this->pasta)) {
      $controller_ns = "\\" .  str_replace('/', '\\', $this->pasta);
      $services_ns =  "\\" .  str_replace('/', '\\', $this->pasta);
    } else {
      $controller_ns =  "";
      $services_ns =   "";
    }

    $replacements = [
      '%model%'         => $this->model,
      '%modelName%'     => $this->modelName,
      '%controller.ns%' => $controller_ns,
      '%services.ns%'   => $services_ns
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $content);

    if (!empty($this->pasta)) {
      $pasta_salvar =  "/" . str_replace('\\', '/', $this->pasta) . '/';
    } else {
      $pasta_salvar = "/";
    }

    $fileName      = $this->modelName . 'Controller';
    $fileDirectory = app()->basePath() . '/app/Http/Controllers' . $pasta_salvar;
    $filePath      = $fileDirectory . $fileName . '.php';

    // Check if the directory exists, if not create...
    if (!$this->fileManager->exists($fileDirectory)) {
      $this->fileManager->makeDirectory($fileDirectory, 0755, true);
    }

    if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
      $response = $this->ask("O controller [{$fileName}] ja existe. Vc deseja substituir?", 'Sim|s');

      if (!$this->isResponsePositive($response)) {
        $this->line("❌ O controller [{$fileName}] nao pode ser substituido.");
        return;
      }
    }

    $this->line("✅ O controller [{$fileName}] foi criado com sucesso.");

    $this->fileManager->put($filePath, $content);
  }

  /**
   * Check the models existance, create if wanted.
   */
  protected function checkModel()
  {

    $model = $this->appNamespace . 'Models/' . $this->argument('model');
    $this->modelName = $this->argument('model');

    $this->model = str_replace('/', '\\', $model);

    if (!$this->isLumen() && $this->laravel->runningInConsole()) {

      $content = $this->fileManager->get($this->stubs['model']);
      $replacements = [
        '%model%'         => $this->model,
        '%modelName%'     => $this->modelName,
        '%table_name%'    => $this->table_name,
      ];

      $content = str_replace(array_keys($replacements), array_values($replacements), $content);

      $fileName      = $this->modelName;
      $fileDirectory = app()->basePath() . '/app/Models/';
      $filePath      = $fileDirectory . $fileName . '.php';

      // Check if the directory exists, if not create...
      if (!$this->fileManager->exists($fileDirectory)) {
        $this->fileManager->makeDirectory($fileDirectory, 0755, true);
      }

      if ($this->laravel->runningInConsole() && $this->fileManager->exists($filePath)) {
        $response = $this->ask("O controller [{$fileName}] ja existe. Vc deseja substituir?", 'Sim|s');

        if (!$this->isResponsePositive($response)) {
          $this->line("❌ O controller [{$fileName}] nao pode ser substituido.");
          return;
        }
      }


      $this->fileManager->put($filePath, $content);

      $this->line("✅ A Model [{$this->model}] foi criada com sucesso.");
    }
  }

  protected function isLumen()
  {
    return str_contains(app()->version(), 'Lumen');
  }
}
