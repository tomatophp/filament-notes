<?php

namespace TomatoPHP\FilamentNotes\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use TomatoPHP\FilamentIcons\FilamentIconsServiceProvider;
use TomatoPHP\FilamentNotes\FilamentNotesServiceProvider;
use TomatoPHP\FilamentNotes\Tests\Models\User;
use TomatoPHP\FilamentTranslationComponent\FilamentTranslationComponentServiceProvider;
use TomatoPHP\FilamentTypes\FilamentTypesServiceProvider;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    public ?Panel $panel = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->panel = Filament::getCurrentOrDefaultPanel();
    }

    protected function getPackageProviders($app): array
    {
        $providers = [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            MediaLibraryServiceProvider::class,
            FilamentTranslationComponentServiceProvider::class,
            FilamentIconsServiceProvider::class,
            FilamentTypesServiceProvider::class,
            FilamentNotesServiceProvider::class,
            AdminPanelProvider::class,
        ];

        // Filament's support provider must register before Livewire's (see the upgrade playbook).
        sort($providers);

        return $providers;
    }

    protected function defineDatabaseMigrations(): void
    {
        // The notes and types providers register their own migrations; add Laravel's users table
        // to the migrator so LazilyRefreshDatabase runs everything in one migrate:fresh.
        $this->app['migrator']->path(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/migrations');
    }

    protected function defineEnvironment($app): void
    {
        tap($app['config'], function (Repository $config): void {
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);

            $config->set('auth.providers.users.model', User::class);
            $config->set('filament-notes.models.user', User::class);
            $config->set('filament-icons.cache', false);

            $config->set('view.paths', [
                ...$config->get('view.paths'),
                __DIR__.'/../resources/views',
            ]);
        });
    }
}
