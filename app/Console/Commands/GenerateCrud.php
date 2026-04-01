<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate CRUD operations including model, controller, views, and request';

    public function handle()
    {
        $name = $this->argument('name');
        
        // Proper naming conventions
        $modelName = Str::studly($name);
        $tableName = Str::snake(Str::pluralStudly($name));
        $viewFolder = Str::snake($name);

        $this->model($modelName, $tableName);
        $this->controller($modelName, $viewFolder);
        $this->request($modelName, $tableName);
        $this->views($modelName, $tableName, $viewFolder);

        $this->info('CRUD ' . $modelName . ' berhasil dibuat!' . "\n" .
            'PENTING: Selalu letakkan route DATA di ATAS route RESOURCE:' . "\n" .
            'Route::get(\'' . $viewFolder . '/data\', [' . $modelName . 'Controller::class, \'getData\'])->name(\'' . $viewFolder . '.data\');' . "\n" .
            'Route::resource(\'' . $viewFolder . '\', ' . $modelName . 'Controller::class);' . "\n" .
            'Dan tambahkan menu ' .$modelName. ' di side bar');
    }

    protected function model($modelName, $table)
    {
        $columns = Schema::getColumnListing($table);

        if (empty($columns)) {
            $this->error("Tabel '$table' tidak ditemukan. Pastikan sudah migrate!");
            return;
        }

        // Buat daftar fillable, abaikan kolom seperti id, created_at, updated_at
        $fillableColumns = array_filter($columns, function ($column) {
            return !in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at']);
        });

        $fillable = "'" . implode("', '", $fillableColumns) . "'";

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{fillable}}', '{{table}}'],
            [$modelName, $fillable, $table],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$modelName}.php"), $modelTemplate);
    }

    protected function controller($modelName, $viewFolder)
    {
        $controllerTemplate = str_replace(
            ['{{modelName}}', '{{modelNameSinggular}}'],
            [$modelName, $viewFolder],
            $this->getStub('Controller')
        );

        // Fix potential 500 error when formatting null timestamps (e.g. from manual seeders)
        $controllerTemplate = str_replace(
            ['$item->created_at->format(\'Y-m-d\')', '$item->updated_at->format(\'Y-m-d\')'],
            ['$item->created_at ? $item->created_at->format(\'Y-m-d\') : \'-\'', '$item->updated_at ? $item->updated_at->format(\'Y-m-d\') : \'-\''],
            $controllerTemplate
        );

        file_put_contents(app_path("/Http/Controllers/{$modelName}Controller.php"), $controllerTemplate);
    }

    protected function request($modelName, $table)
    {
        $columns = Schema::getColumnListing($table);

        // Aturan validasi default
        $rules = [];
        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                if ($column == 'email') {
                    $rules[$column] = 'required|email|unique:' . $table . ',email,' . (request()->route('id') ?? 'NULL');
                } elseif ($column == 'password') {
                    $rules[$column] = 'nullable|min:8';
                } else {
                    $rules[$column] = 'required';
                }
            }
        }

        // Konversi aturan validasi menjadi string
        $rulesString = [];
        foreach ($rules as $field => $rule) {
            $rulesString[] = "'$field' => '$rule'";
        }
        $rulesText = implode(",\n        ", $rulesString);

        $requestTemplate = str_replace(
            ['{{modelName}}', '{{rules}}'],
            [$modelName, $rulesText],
            $this->getStub('Request')
        );

        file_put_contents(app_path("/Http/Requests/{$modelName}Request.php"), $requestTemplate);
    }

    protected function views($modelName, $table, $viewFolder)
    {
        if (!file_exists(resource_path("views/{$viewFolder}"))) {
            mkdir(resource_path("views/{$viewFolder}"), 0777, true);
        }

        $columns = Schema::getColumnListing($table);
        
        $columnsHeader = '';
        $columnsData = '';

        foreach ($columns as $column) {
            // INCLUDE 'id' but EXCLUDE timestamps
            if (!in_array($column, ['created_at', 'updated_at'])) { 
                $columnsHeader .= "<th>" . ucwords(str_replace('_', ' ', $column)) . "</th>\n";
                $columnsData .= "{ data: '{$column}', name: '{$column}' },\n";
            }
        }

        $indexTemplate = str_replace(
            ['{{modelNameSinggular}}', '{{modelName}}', '{{columnsHeader}}', '{{columnsData}}'],
            [$viewFolder, $modelName,  $columnsHeader, $columnsData],
            $this->getStub('Index')
        );
        file_put_contents(resource_path("views/{$viewFolder}/index.blade.php"), $indexTemplate);

        $fields = '';
        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                $fields .= "
            <div class=\"mb-4\">
                <label for=\"form-{$column}\" class=\"form-label !text-[.875rem] text-black\">
                    " . ucfirst(str_replace('_', ' ', $column)) . "
                </label>
                <input type=\"text\" class=\"form-control @error('{$column}') is-invalid @enderror\" id=\"form-{$column}\" name=\"{$column}\" value=\"{{ old('{$column}') }}\" placeholder=\"Masukkan " . str_replace('_', ' ', $column) . "\">
                @error('{$column}')
                    <span class=\"invalid-feedback\" role=\"alert\">
                        <strong>{{ \$message }}</strong>
                    </span>
                @enderror
            </div>\n";
            }
        }

        $createTemplate = str_replace(
            ['{{modelNameSinggular}}', '{{modelName}}',  '{{modelNamePlural}}', '{{fields}}'],
            [$viewFolder, $modelName, Str::plural($modelName), $fields],
            $this->getStub('Create')
        );
        file_put_contents(resource_path("views/{$viewFolder}/create.blade.php"), $createTemplate);

        $fields = '';
        foreach ($columns as $column) {
            if ($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at') {
                $fields .= "<div class='mb-4'>
                        <label for='form-{$column}' class='form-label !text-[.875rem] text-black'>" . ucfirst(str_replace('_', ' ', $column)) . "</label>
                        <input type='" . ($column == 'email' ? 'email' : 'text') . "' 
                               class='form-control @error('{$column}') is-invalid @enderror' 
                               id='form-{$column}' 
                               name='{$column}' 
                               value='{{ old(\"$column\", \$" . $viewFolder . "->$column) }}'
                               placeholder='Masukkan " . str_replace('_', ' ', $column) . "'>
                        @error('{$column}')
                            <div class='invalid-feedback'>{{ \$message }}</div>
                        @enderror
                    </div>";
            }
        }

        $editTemplate = str_replace(
            ['{{modelNameSinggular}}', '{{modelName}}', '{{modelNamePlural}}', '{{fields}}'],
            [$viewFolder, $modelName, Str::plural($modelName), $fields],
            $this->getStub('Edit')
        );
        file_put_contents(resource_path("views/{$viewFolder}/edit.blade.php"), $editTemplate);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function getTableColumns($table)
    {
        return Schema::getColumnListing($table);
    }
}
