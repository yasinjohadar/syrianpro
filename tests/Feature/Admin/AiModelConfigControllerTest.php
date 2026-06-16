<?php

use App\Models\User;
use App\Services\Ai\AiPanelConfigService;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $path = config_path('ai-panel.php');
    if (File::exists($path)) {
        $this->panelBackup = File::get($path);
    } else {
        $this->panelBackup = null;
    }

    File::put($path, "<?php\n\nreturn ['registry' => [], 'providers' => []];\n");

    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

afterEach(function () {
    $path = config_path('ai-panel.php');
    File::delete($path);

    if (isset($this->panelBackup) && $this->panelBackup !== null) {
        File::put($path, $this->panelBackup);
    }

    if (app()->configurationIsCached()) {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
    }
});

it('renders custom model create page', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get(route('admin.ai.models.create', ['mode' => 'custom']))
        ->assertOk()
        ->assertSee('موديل مخصص')
        ->assertSee('اسم الموديل');
});

it('stores a custom model via the admin form', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->post(route('admin.ai.models.store'), [
            'add_mode' => 'custom',
            'name' => 'موديل تجريبي',
            'provider' => 'openai',
            'model_key' => 'my-custom-model-id',
            'capabilities' => ['text', 'chat'],
            'is_active' => '1',
        ]);

    $response->assertRedirect(route('admin.ai.models.index'));

    $service = app(AiPanelConfigService::class);
    $entry = $service->findRegistryByProviderAndKey('openai', 'my-custom-model-id');

    expect($entry)->not->toBeNull()
        ->and($entry['name'])->toBe('موديل تجريبي');
});
