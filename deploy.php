<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'dikkevandebbie');
set('repository', 'git@github.com:duncan412/dikkevandebbie.git');

set('branch', 'main');

set('composer_options', 'install --no-dev --no-interaction --prefer-dist --optimize-autoloader');

add('shared_files', ['.env']);
add('shared_dirs', ['content', 'users']);

set('http_user', 'ploi');
set('writable_mode', 'chmod');

set('composer_self_update', false);

host('production')
    ->set('hostname', '46.62.246.246')
    ->set('remote_user', 'ploi')
    ->set('deploy_path', '/home/ploi/dikkevandebbie.nl')
    ->set('forward_agent', true)
    ->set('labels', ['stage' => 'production']);

task('deploy:optimize', [
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
]);

task('build:assets', function () {
    run('cd {{release_path}} && npm ci && npm run build');
});

desc('Deploy your Laravel app to Ploi.io');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'build:assets',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'deploy:optimize',
    // 'artisan:migrate',
    'deploy:cleanup',
]);

after('deploy:failed', 'deploy:unlock');

after('deploy:success', 'artisan:queue:restart');
