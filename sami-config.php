<?php

use Sami\Sami;

use Symfony\Component\Finder\Finder;

use Sami\Version\GitVersionCollection;

use Sami\RemoteRepository\GitHubRemoteRepository;

$iterator = Finder::create()->
    files()->
    name('*.php')->
    exclude('Tests')->
    exclude('Resources')->
    in($dir = __DIR__.'/lib');

$versions = GitVersionCollection::create($dir)->
    addFromTags('v1.*')->
    addFromTags('v2.*')->
    addFromTags('v3.*')->
    add('master', 'master branch');

return new Sami($iterator, [
    'title'             => 'Maintenance screen API',

    'versions'          => $versions,
    'build_dir'         => __DIR__.'/docs/%version%',
    'cache_dir'         => __DIR__.'/sami-cache/%version%',

    'remote_repository' => new GitHubRemoteRepository(
        'progminer/maintenance-screen',
        dirname($dir)
    )
]);
