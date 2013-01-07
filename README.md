This is a very simple file-backed persistent key-value store for php.

Usage:

    $store = new JDodds\Storage\KeyValueFile('/path/to/save/in');

    $store->attach('foo', 'bar');
    $store['baz'] = 'quux';

    $store->persist();

    // ... sometime later

    $store = new JDodds\Storage\KeyValueFile('/the/same/path/as/before');
    $foo = $store['foo'];

    $foo2 = $store->detach('foo');
    $store->contains('foo') // false;

