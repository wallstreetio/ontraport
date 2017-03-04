<?php

namespace Wsio\Tests\Ontraport\Functional;

class ContactTest extends TestCase
{
    public function testCreate()
    {
        $contact = $this->ontraport->contacts->create([
            'firstname' => 'Tamer',
            'lastname' => 'Ashkar'
        ]);

        $this->assertNotNull($contact->id);

        return $contact->id;
    }

    /**
     * @depends testCreate
     */
    public function testGet($id)
    {
        $contact = $this->ontraport->contacts
            ->where('firstname', 'Tamer')
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($contact->id);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate($id)
    {
        $this->ontraport->contacts->update($id, [
            'email' => 'dev@wallstreet.io'
        ]);

        $object = $this->ontraport->contacts->find($id);

        $this->assertEquals($object->email, 'dev@wallstreet.io');
    }

    public function testSaveOrUpdate()
    {
        $contact = $this->ontraport->contacts->saveOrUpdate([
            'email' => 'dev@wallstreet.io',
            'firstname' => 'changed'
        ]);

        $this->assertNull($contact->id);
    }

    /**
     * @depends testCreate
     */
    public function testTag($id)
    {
        $tags =$this->ontraport->contacts->tag($id, ['developer', 'sleeper']);

        $this->assertInternalType('array', $tags);
    }

    /**
     * @depends testCreate
     */
    public function testUntag($id)
    {
        $tags = $this->ontraport->contacts->untag($id, ['developer', 'sleeper']);

        $this->assertInternalType('array', $tags);
    }

    /**
     * @depends testCreate
     * @expectedException \Wsio\Ontraport\Exceptions\InvalidRequest
     */
    public function testDelete($id)
    {
        $this->assertTrue($this->ontraport->contacts->delete($id));

        $this->ontraport->contacts->find($id);
    }

    public function testInfo()
    {
        $this->assertInternalType('array', $this->ontraport->contacts->info());
    }

    public function testMeta()
    {
        $this->assertInternalType('array', $this->ontraport->contacts->meta());
    }
}
