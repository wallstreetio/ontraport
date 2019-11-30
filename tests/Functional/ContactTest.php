<?php

namespace Wsio\Tests\Ontraport\Functional;

class ContactTest extends TestCase
{
    public function testCreate()
    {
        $this->assertTrue($this->ontraport->contacts->delete());

        $contact = $this->ontraport->contacts->create([
            'firstname' => 'Tamer', 'lastname' => 'Ashkar'
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
            ->where('lastname', 'Ashkar')
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($contact->id);
        $this->assertEquals($id, $contact->id);
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

    /**
     * @depends testCreate
     */
    public function testSaveOrUpdate($id)
    {
        $contact = $this->ontraport->contacts->saveOrUpdate([
            'email' => 'dev@wallstreet.io',
            'firstname' => 'changed'
        ]);

        $contact = $this->ontraport->contacts->where('firstname', 'changed')->first();
        $this->assertNotNull($contact);

        $contacts = $this->ontraport->contacts->where('email', 'dev@wallstreet.io')->get();
        $this->assertEquals(count($contacts), 1);
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
    public function testTagByName($id)
    {
        $tags =$this->ontraport->contacts->tagByName($id, ['developer2', 'sleeper2']);

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

    /**
     * @depends testCreate
     */
    public function testDeleteMany()
    {
        $contact = $this->ontraport->contacts->create([
            'firstname' => 'New', 'lastname' => 'Guy'
        ]);

        $contact2 = $this->ontraport->contacts->create([
            'firstname' => 'New', 'lastname' => 'Girl'
        ]);

        $this->assertTrue($this->ontraport->contacts->delete([$contact->id, $contact2->id]));

        $this->ontraport->contacts->create([
            'firstname' => 'New', 'lastname' => 'Person'
        ]);

        $this->ontraport->contacts->create([
            'firstname' => 'Newest', 'lastname' => 'Person'
        ]);

        $this->assertEquals(count($this->ontraport->contacts->where('lastname', 'Person')->get()), 2);
        $this->assertTrue($this->ontraport->contacts->where('firstname', 'New')->where('lastname', 'Person')->delete());
        $this->assertEquals(count($this->ontraport->contacts->where('lastname', 'Person')->get()), 1);

        $this->assertTrue($this->ontraport->contacts->delete());
        $this->assertEquals(count($this->ontraport->contacts->get()), 0);
    }

    public function testInfo()
    {
        $this->assertInternalType('array', $this->ontraport->contacts->info());
    }

    public function testMeta()
    {
        $this->assertInternalType('array', $this->ontraport->contacts->meta());
    }

    public function testFluentSaveUpdateAndDelete()
    {
        $this->assertTrue($this->ontraport->contacts->delete());
        $this->assertEquals(0, count($this->ontraport->contacts->get()));

        $this->ontraport->contacts->create(['firstname' => 'New', 'lastname' => 'Person']);
        $this->ontraport->contacts->create(['firstname' => 'Newest', 'lastname' => 'Person']);

        $contacts = $this->ontraport->contacts->where('lastname', 'Person')->get();

        $firstPersonName = $contacts[0]->firstname;
        $contacts[1]->firstname = 'Coolest';
        $contacts[1]->save();

        $contacts = $this->ontraport->contacts->where('lastname', 'Person')->get();
        $this->assertEquals(2, count($contacts));
        $this->assertEquals($firstPersonName, $contacts[0]->firstname);
        $this->assertEquals('Coolest', $contacts[1]->firstname);

        $contacts = $this->ontraport->contacts->where('lastname', 'Person')->get();
        $this->assertEquals(2, count($contacts));
        $remainingContactName = $contacts[1]->firstname;
        $this->assertTrue($contacts[0]->delete());

        $contacts = $this->ontraport->contacts()->where('lastname', 'Person')->get();
        $this->assertEquals(1, count($contacts));
        $this->assertEquals($remainingContactName, $contacts[0]->firstname);
    }
}
