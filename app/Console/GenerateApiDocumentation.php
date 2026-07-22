<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Console;

use App\Http\Controllers\Api\v1\Docs as DocsV1;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Components;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use GoldSpecDigital\ObjectOrientedOAS\Objects\License;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateApiDocumentation extends Command
{
    private array $versions = [
        'v1' => DocsV1::class,
    ];

    protected function configure(): void
    {
        $this->setName('api:doc');
        $this->setDescription('Generate OpenAPI documentation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->versions as $version => $specClass) {
            $openApi = OpenApi::create()
                ->openapi(OpenApi::OPENAPI_3_0_2)
                ->info(
                    Info::create()
                        ->title(config('app.name').' API')
                        ->version($version)
                        ->license(License::create()->name('MIT'))
                )
                ->servers(
                    Server::create()
                        ->url(rtrim(config('app.url'), '/').'/api/'.$version)
                        ->description('API server')
                )
                ->components(
                    Components::create()->securitySchemes(
                        SecurityScheme::create('bearerAuth')
                            ->type(SecurityScheme::TYPE_HTTP)
                            ->scheme('bearer')
                            ->description('Bearer token authentication')
                    )
                )
                ->paths(...$specClass::paths());

            storage(config('storage.public'))->addPath('docs')->writeFile("openapi-{$version}.json", $openApi->toJson());

            $output->writeln("<info>openapi-{$version}.json generated successfully.</info>");
        }

        return Command::SUCCESS;
    }
}
