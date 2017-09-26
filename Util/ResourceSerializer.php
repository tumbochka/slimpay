<?php

namespace Payum\Slimpay\Util;

use HapiClient\Hal\Resource;

class ResourceSerializer
{
    /**
     * @param Resource $resource
     *
     * @return string
     */
    public static function serializeResource(Resource $resource)
    {
        $state = $resource->getState();
        $links = $resource->getAllLinks();
        $embeddedResources = $resource->getAllEmbeddedResources();

        $state = array_merge($state, [
            '_links' => $links,
            '_embedded' => $embeddedResources,

        ]);

        return json_encode($state);
    }

    /**
     * @param string $json
     *
     * @return Resource
     */
    public static function unserializeResource($json)
    {
        return Resource::fromJson($json);
    }
}