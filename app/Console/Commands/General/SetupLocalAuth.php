<?php

declare(strict_types=1);

namespace App\Console\Commands\General;

use App\Repositories\Security\LocalPassportClientRepository;
use Dotenv\Dotenv;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Laravel\Passport\Passport;
use RuntimeException;
use Throwable;

final class SetupLocalAuth extends Command
{
    protected $signature = 'auth:setup-local';

    protected $description = 'Prepare the isolated local Passport client and TOKEN_APP without displaying secrets';

    public function handle(LocalPassportClientRepository $clients, Hasher $hasher, DatabaseManager $database): int
    {
        if ($this->laravel->configurationIsCached()) {
            $this->error('Limpia primero la caché de configuración con php artisan config:clear --no-interaction.');

            return self::FAILURE;
        }

        try {
            $path = $this->laravel->environmentFilePath();
            $contents = file_get_contents($path);
            if ($contents === false) {
                throw new RuntimeException('Environment file unavailable.');
            }
            foreach (['TOKEN_APP', 'LOCAL_AUTH_PASSPORT_CLIENT_ID'] as $key) {
                if (preg_match_all('/^(?:export\s+)?'.preg_quote($key, '/').'\s*=/m', $contents) > 1) {
                    throw new RuntimeException('Duplicate environment settings.');
                }
            }
            $values = Dotenv::parse($contents);
            $secret = $values['TOKEN_APP'] ?? '';
            $secret = is_string($secret) && $secret !== '' ? $secret : bin2hex(random_bytes(32));
            if (strlen($secret) < 32 || strlen($secret) > 512 || str_contains($secret, "\n") || trim($secret) !== $secret) {
                throw new RuntimeException('Invalid application token configuration.');
            }

            $privateExists = (bool) config('passport.private_key') || is_file(Passport::keyPath('oauth-private.key'));
            $publicExists = (bool) config('passport.public_key') || is_file(Passport::keyPath('oauth-public.key'));
            if ($privateExists !== $publicExists) {
                throw new RuntimeException('Partial key pair; no files were overwritten.');
            }
            if (! $privateExists && $this->call('passport:keys', ['--no-interaction' => true]) !== self::SUCCESS) {
                return self::FAILURE;
            }

            $database->connection()->transaction(function () use ($clients, $secret, $hasher, $path, $contents): void {
                $client = $clients->prepare($secret, $hasher);
                $updated = $this->setValue($contents, 'TOKEN_APP', $secret);
                $updated = $this->setValue($updated, 'LOCAL_AUTH_PASSPORT_CLIENT_ID', (string) $client->id);
                if (file_put_contents($path, $updated, LOCK_EX) === false) {
                    throw new RuntimeException('Could not persist environment settings.');
                }
                config()->set('local_auth.token_app', $secret);
                config()->set('local_auth.client_id', (string) $client->id);
            });
        } catch (Throwable) {
            $this->error('No se pudo preparar el login local. Revisa permisos, claves completas, TOKEN_APP y el esquema OAuth sin sobrescribir datos.');

            return self::FAILURE;
        }

        $this->info('Login local configurado. TOKEN_APP permanece en .env; no se modificaron otros clientes ni se rotaron claves existentes.');

        return self::SUCCESS;
    }

    private function setValue(string $contents, string $key, #[\SensitiveParameter] string $value): string
    {
        $line = $key.'="'.str_replace(['\\', '"', '$'], ['\\\\', '\\"', '\\$'], $value).'"';
        $pattern = '/^(?:export\s+)?'.preg_quote($key, '/').'\s*=.*$/m';

        return preg_match($pattern, $contents)
            ? preg_replace_callback($pattern, static fn (): string => $line, $contents)
            : rtrim($contents)."\n".$line."\n";
    }
}
