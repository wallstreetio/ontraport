<?php

namespace Wsio\Ontraport\Resources;

use Wsio\Ontraport\Ontraport;

class Object extends Resource
{
    /**
     * The id of the Ontraport custom object.
     *
     * @var int
     */
    protected $id;

    /**
     * The namespace of the Ontraport resource.
     *
     * @var string
     */
    protected $namespace = 'objects';

    /**
     * Create a new Ontraport Object instance.
     *
     * @param  \Wsio\Ontraport\Ontraport  $ontraport
     * @param  int  $id
     * @return void
     */
    public function __construct(Ontraport $ontraport, $id)
    {
        parent::__construct($ontraport);

        $this->id = $id;
    }

    /**
     * Retreive the Ontraport Object id.
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->id;
    }

    /**
     * Tag an Ontraport Object.
     *
     * @param  mixed  $ids
     * @param  mixed $tags
     * @return array
     */
    public function tag($ids, $tags)
    {
        $namespace = $this->getNamespace() . '/tag';

        return $this->ontraport->put($namespace, $this->toArray([
            'ids' => implode(',', (array) $ids),
            'add_list' => implode(',', (array) $tags),
        ]));
    }

    /**
     * Detach a tag(s) from an Ontraport Object(s).
     *
     * @param  mixed  $ids
     * @param  mixed $tags
     * @return array
     */
    public function untag($ids, $tags)
    {
        $namespace = $this->getNamespace() . '/tag';

        return $this->ontraport->delete($namespace, $this->toArray([
            'ids' => implode(',', (array) $ids),
            'remove_list' => implode(',', (array) $tags),
        ]));
    }

    /**
     * Retrieve the default request parameters.
     *
     * @param  array  $data
     * @return array
     */
    public function toArray(array $data = [])
    {
        return parent::toArray(array_merge($data, [
            'objectID' => $this->id
        ]));
    }
}
