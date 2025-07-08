<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DDDStructure extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $name = 'make:ddd';

    /**
     * The console command description.
     */
    protected $description = 'Creates DDD folder structure for the given entity within a bounded context';

    /**
     * Configure the console command options.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addArgument('context', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The bounded context (e.g., catalog, user)');
        $this->addArgument('entity', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The entity to create (e.g., product, category)');
        $this->addOption('with-migration', 'm', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Also create migration and base model');
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $context = Str::studly($this->argument('context'));
        $entity = Str::studly($this->argument('entity'));
        $basePath = base_path("src/{$context}/{$entity}");

        $this->info("🔨 Creating DDD structure for {$context}/{$entity}...");

        // Domain
        $this->makeDirectory("$basePath/domain/entities");
        $this->makeDirectory("$basePath/domain/value_objects");
        $this->makeDirectory("$basePath/domain/contracts");
        $this->makeDirectory("$basePath/domain/services");
        $this->makeDirectory("$basePath/domain/events");

        // Application
        $this->makeDirectory("$basePath/application/use_cases");
        $this->makeDirectory("$basePath/application/dtos");
        $this->makeDirectory("$basePath/application/jobs");

        // Infrastructure
        $this->makeDirectory("$basePath/infrastructure/controllers");
        $this->makeDirectory("$basePath/infrastructure/routes");
        $this->makeDirectory("$basePath/infrastructure/repositories");
        $this->makeDirectory("$basePath/infrastructure/validators");
        $this->makeDirectory("$basePath/infrastructure/listeners");
        $this->makeDirectory("$basePath/infrastructure/events");

        // Database
        $this->makeDirectory("$basePath/database/migrations");
        $this->makeDirectory("$basePath/database/seeders");
        $this->makeDirectory("$basePath/database/factories");

        // Policies & Tests
        $this->makeDirectory("$basePath/policies");
        $this->makeDirectory("$basePath/tests/Unit");
        $this->makeDirectory("$basePath/tests/Feature");

        // Example files
        $this->generateExampleController($context, $entity, $basePath);
        $this->generateExampleRequest($context, $entity, $basePath);
        $this->generateExampleRepositoryInterface($context, $entity, $basePath);
        $this->generateExampleEntity($context, $entity, $basePath);
        $this->generateExampleDTO($context, $entity, $basePath);
        $this->generateRoutes($context, $entity, $basePath);

        if ($this->option('with-migration')) {
            $this->info('📦 Generating base model and migration...');
            $this->call('make:model', [
                'name' => "Src/{$context}/{$entity}/infrastructure/models/{$entity}",
                '--migration' => true,
                '--factory' => true,
                '--seed' => true
            ]);
        }

        $this->appendRouteToGlobalApi($context, $entity);

        $this->info("✅ DDD structure for {$context}/{$entity} created successfully.");
        return Command::SUCCESS;
    }

    protected function makeDirectory(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
            $this->info("📂 Created: {$path}");
        } else {
            $this->warn("⚠️ Already exists: {$path}");
        }
    }

    protected function generateExampleController(string $context, string $entity, string $basePath): void
    {
        $namespace = "Src\\{$context}\\{$entity}\\infrastructure\\controllers";
        $className = "{$entity}Controller";
        $content = <<<PHP
        <?php

        namespace {$namespace};

        use App\Http\Controllers\Controller;
        use Illuminate\Http\Request;

        class {$className} extends Controller
        {
            public function index()
            {
                return response()->json(['message' => '{$entity} index']);
            }
        }
        PHP;
        File::put("$basePath/infrastructure/controllers/{$className}.php", $content);
        $this->info("📝 Example Controller created: {$className}.php");
    }

    protected function generateExampleRequest(string $context, string $entity, string $basePath): void
    {
        $namespace = "Src\\{$context}\\{$entity}\\infrastructure\\validators";
        $className = "Create{$entity}Request";
        $content = <<<PHP
        <?php

        namespace {$namespace};

        use Illuminate\Foundation\Http\FormRequest;

        class {$className} extends FormRequest
        {
            public function authorize(): bool
            {
                return true;
            }

            public function rules(): array
            {
                return [
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                ];
            }
        }
        PHP;
        File::put("$basePath/infrastructure/validators/{$className}.php", $content);
        $this->info("📝 Example Request created: {$className}.php");
    }

    protected function generateExampleRepositoryInterface(string $context, string $entity, string $basePath): void
    {
        $namespace = "Src\\{$context}\\{$entity}\\domain\\contracts";
        $interfaceName = "{$entity}RepositoryInterface";
        $content = <<<PHP
        <?php

        namespace {$namespace};

        interface {$interfaceName}
        {
            public function all(): array;
            public function findById(string \$id);
            public function save(\$entity): void;
        }
        PHP;
        File::put("$basePath/domain/contracts/{$interfaceName}.php", $content);
        $this->info("📝 Example Repository Interface created: {$interfaceName}.php");
    }

    protected function generateExampleEntity(string $context, string $entity, string $basePath): void
    {
        $namespace = "Src\\{$context}\\{$entity}\\domain\\entities";
        $className = "{$entity}";
        $content = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            public function __construct(
                public string \$id,
                public string \$name,
                public ?string \$description,
                public float \$price
            ) {}
        }
        PHP;
        File::put("$basePath/domain/entities/{$className}.php", $content);
        $this->info("📝 Example Entity created: {$className}.php");
    }

    protected function generateExampleDTO(string $context, string $entity, string $basePath): void
    {
        $namespace = "Src\\{$context}\\{$entity}\\application\\dtos";
        $className = "{$entity}DTO";
        $content = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            public function __construct(
                public string \$name,
                public ?string \$description,
                public float \$price
            ) {}
        }
        PHP;
        File::put("$basePath/application/dtos/{$className}.php", $content);
        $this->info("📝 Example DTO created: {$className}.php");
    }

    protected function generateRoutes(string $context, string $entity, string $basePath): void
    {
        $routes = <<<PHP
        <?php

        use Illuminate\Support\Facades\Route;
        use Src\\{$context}\\{$entity}\\infrastructure\\controllers\\{$entity}Controller;

        Route::get('/', [{$entity}Controller::class, 'index']);
        PHP;
        File::put("$basePath/infrastructure/routes/api.php", $routes);
        $this->info("📝 Example routes created.");
    }

    protected function appendRouteToGlobalApi(string $context, string $entity): void
    {
        $routeEntry = "\nRoute::prefix('".Str::kebab($context)."_".Str::kebab($entity)."')\n    ->group(base_path('src/{$context}/{$entity}/infrastructure/routes/api.php'));\n";
        File::append(base_path('routes/api.php'), $routeEntry);
        $this->info("🔗 Routes linked to global routes/api.php");
    }
}
