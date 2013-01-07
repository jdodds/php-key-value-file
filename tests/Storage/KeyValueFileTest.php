<?php

use JDodds\Storage\KeyValueFile;

class KeyValueFileTest extends PHPUnit_Framework_TestCase {
    public function testPersistsValues() {
        $storePath = tempnam(sys_get_temp_dir(), 'kvftest');
        $store = new KeyValueFile($storePath);

        $store->attach('foo', 'bar');
        $store['baz'] = 'quux';

        $store->persist();

        $newStore = new KeyValueFile($storePath);

        $this->assertTrue($newStore->contains('foo'));
        $this->assertEquals('bar', $newStore['foo']);
        $this->assertEquals('quux', $newStore['baz']);
        unlink($storePath);
    }

    public function testRemovesValues() {
        $storePath = tempnam(sys_get_temp_dir(), 'kvftest');
        $store = new KeyValueFile($storePath);

        $store['foo'] = 'bar';
        $store['baz'] = 'quux';

        $store->persist();

        $newStore = new KeyValueFile($storePath);

        $foo = $newStore->detach('foo');
        $newStore->persist();

        $this->assertEquals('bar', $foo);

        $store->load();
        $this->assertFalse($store->contains('foo'));
        unlink($storePath);
    }
}
