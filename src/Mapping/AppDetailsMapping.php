<?php
declare(strict_types=1);

namespace ScriptFUSION\Steam250\Shared\Mapping;

use ScriptFUSION\Mapper\DataType;
use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Join;
use ScriptFUSION\Mapper\Strategy\Type;
use ScriptFUSION\Steam250\Shared\Platform;

class AppDetailsMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'name' => new Copy('name'),
            'type' => new Copy('type'),
            'developers' => new Copy('developers'),
            'publishers' => new Copy('publishers'),
            'release_date' => new Callback(
                static function (array $data): ?int {
                    return $data['release_date'] ? $data['release_date']->getTimestamp() : null;
                }
            ),
            'tags' => new Copy('tags'),
            'price' => new Copy('price'),
            'discount_price' => new Copy('discount_price'),
            'discount' => new Copy('discount'),
            'vrx' => new Type(DataType::INTEGER(), new Copy('vrx')),
            'free' => new Type(DataType::INTEGER(), new Copy('free')),
            'videos' => new Join(',', new Copy('videos')),
            'ea' => new Callback(
                static function (array $data): int {
                    return (int)from($data['tags'])->any(static function (array $data): bool {
                        return $data['name'] === 'Early Access' && !isset($data['browseable']);
                    });
                }
            ),
            'positive_reviews' => new Copy('positive_reviews'),
            'negative_reviews' => new Copy('negative_reviews'),
            'total_reviews' => new Callback(
                static function (array $data): int {
                    return $data['positive_reviews'] + $data['negative_reviews'];
                }
            ),
            'steam_reviews' => new copy('steam_reviews'),
            'platforms' => new Callback(
                static function (array $data): int {
                    $platforms = 0;
                    $data['windows'] && $platforms |= Platform::WINDOWS;
                    $data['linux'] && $platforms |= Platform::LINUX;
                    $data['mac'] && $platforms |= Platform::MAC;
                    $data['vive'] && $platforms |= Platform::VIVE;
                    $data['occulus'] && $platforms |= Platform::OCULUS;
                    $data['wmr'] && $platforms |= Platform::WMR;
                    $data['valve_index'] && $platforms |= Platform::INDEX;

                    return $platforms;
                }
            ),
        ];
    }
}