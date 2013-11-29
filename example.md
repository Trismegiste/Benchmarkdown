# Yuurei (幽霊) [![Build Status](https://travis-ci.org/Trismegiste/Yuurei.png?branch=master)](https://travis-ci.org/Trismegiste/Yuurei)

![Yo dawg Xzibit](./doc/img/atomicity.jpg)

Atomicity in [MongoDB][*2] explained by Xzibit

## What

It's a micro database layer with automatic mapping.
It is intended for **advanced users** of [MongoDB][*2]
who know and understand the growth of a model on a schemaless database.

When I mean "micro", I mean the sum of NCLOC is less than the infamous
method [UnitOfWork::createEntity][*1] of Doctrine 2

Of course, features are also "micro". Don't expect the impossible. Nevertheless
there are some functionalities you don't find anywhere else.

Plus, this DBAL is fully extendable. My other repo [DokudokiBundle][*4] for symfony2
adds 3 more mapping systems with minimum coding.

## How

Use Composer like any other PHP package :

```
    "require": {
        "trismegiste/yuurei": "dev-master"
    },
```

## Why

Because, like the cake, "ODM is a lie". Turning MongoDB into an ORM-like
abstraction is the worst thing you can do against a NoSQL database.

With an ODM, you loose both NoSQL and RDBMS features, here are some :

 * No rich document of MongoDB because the query generator sux and the mapping is complex
 * No schemaless capability because you freeze the model in classes
 * No JOIN, you must rely on slow lazy loading
 * No constraint of RDBMS (references and types) because there is none
 * No atomicity : the only atomicity in MongoDB is on one document

In fact ODM is a slow [ORM][*5] without [ACID][8] : what is the point of using MongoDB ?

That's why I stop chasing the ["Mythical Object Database"][*3] and start hacking.

## Guidances

 * **Rich documents** by Hell ! You have atomicity.
 * Stop to think 1 entity <=> 1 table
 * Only a few root entities : 2, 3 or 4, 10 max for a full e-commerce app, not 200 !
 * 1 app <=> 1 collection
 * Forget 1NF, 2NF and 3NF. It's easier to deal with denormalized data in
   MongoDB than to too many LEFT JOIN in MySQL
 * Think like serialize/unserialize
 * Don't try to reproduce a search engine with your database : use [Elastic Search][*7]
 * Don't try to store everything in collections : use XML files

So, you make a model divided in few parts without circular reference,
and you store it. It's like serialization but in MongoDB.

All non static properties are stored in a way you can query easily with the
powerfull (but very strange I admit) language of MongoDB.

See the [PHPUnit tests][*11] for examples of the serialization process.

## It's like serialization

You know PHP can save and restore any object in session with the serialization
and it doesn't need any mapping information, annotations nor repositories. 
This was my paradigm when I started this library. 

Why can't an ORM/ODM
do the same ? Well, of course I needed a NoSQL database. But this dbal/odm-like
does not need any mappping information and objects stored in the database
are keeping their original structures (without too much noise). So you can
make complex queries with MongoDb.

See full example in [unit test][*12]

```php
// simple object
$doc = new \Some\Sample\Product('EF-85 L', 2000);
// persisting
$this->invocation->persist($doc);
// restoring with invocation repository
$restore = $this->invocation->findByPk((string) $doc->getId());
$this->assertInstanceOf('Some\Sample\Product', $restore);
// retrieving the content in the MongoDB
$dump = $this->collection->findOne(array('_id' => $doc->getId()));
$this->assertEquals('Some\Sample\Product', $dump['-fqcn']);  // we store the FQCN
$this->assertEquals('EF-85 L', $dump['title']);
$this->assertEquals(2000, $dump['price']);
```

## About MDE

With recent concepts of NoSQL, SOA and HMVC, I believe MDE is somewhat
past-history, not always but very often. In fact it's not the MDE itself
but a very common anti-pattern : the "Database Driven Development" where all
source code come from the database schema. Combined with CRUD generators, it
leads to [anemic model][*10], dumb constructors and useless setters/getters without
business meaning.

## About performance

todo

## FAQ

### What are the requirements ?
 * PHP >= 5.4
 * PECL Mongo extension >= 1.3

### How to map properties ?
All *object's* properties are stored. You have only one thing to do :
The root classes must implement the Persistable interface
(there is a trait for implementing this interface). You don't need to extend
any particuliar class, therefore you can follow the DDD without constraint.

### What is a "root class" ?
It is a class stored in the collection, which contains the MongoId in the key '_id'.
All other agregated objects in this class don't need to implement Persistable, they are
recursively stored.

### How can I remove some transient properties ?
You can't. But you can have a transient class with the interface Skippable.
Use Decorator pattern or a State Pattern. Your model can do that.

### Can I make some cleaning before persistence ?
Like serialization, you can implement Cleanable with 2 methods : wakeup and sleep

### How can I query for listing ?
Use the MongoCollection, you can't be more efficient than this low level layer

### How can I store pictures or PDF ?
Use a MongoBinData in your model, it is stored as is

### Can I use something else than MongoId for primary key ?
No

### What about MongoDate ?
Any DateTime are converted into MongoDate and vice versa.

### Is there any other constraints you have used ?
* No mandatory inheritance for model except one interface (for DDD concern)
* Minimum number of switch because it is hidden inheritance
* No more than 5 methods per class
* No method longer than 20 NCLOC
* No static because it is global
* [SRP, OCP, LSP, ISP, DIP][9] at maximum level
* coupling at minimum level (checked with [Mondrian][*16] )

### Is there any lazy loading or proxy classes for DBRef ?
Fly, you fools

## What is the meaning of Yuurei ?
In fact this lib was part of [DokudokiBundle][*4] and I wanted to keep the best 
from this Bundle in a standalone library. So I kept
a japanese name for this. 幽霊 means ghost or spirit because in the original
bundle, this mapping system was named "Invocation".

[*1]: https://github.com/doctrine/doctrine2/blob/master/lib/Doctrine/ORM/UnitOfWork.php#L2446
[*2]: http://www.mongodb.org/
[*3]: http://en.wikipedia.org/wiki/Object_database
[*4]: https://github.com/Trismegiste/DokudokiBundle
[6]: http://en.wikipedia.org/wiki/ACID
[*5]: http://en.wikipedia.org/wiki/Object-relational_mapping
[*7]: http://www.elasticsearch.org/
[8]: http://en.wikipedia.org/wiki/ACID
[9]: http://en.wikipedia.org/wiki/SOLID_(object-oriented_design)
[*10]: http://en.wikipedia.org/wiki/Anemic_domain_model
[*11]: https://github.com/Trismegiste/Yuurei/tree/master/tests/Yuurei/DumperExampleTest.php#L28
[*12]: https://github.com/Trismegiste/Yuurei/tree/master/tests/Yuurei/ReadmeExampleTest.php#L28
[*16]: https://github.com/Trismegiste/Mondrian
