PublishableBehavior
===================

The PublishableBehavior is designed to quickly add publish/unpublish features to your model.


Installation
------------

Install the behavior in your project, the recommended way is to use Composer:

``` yaml
{
    "require": {
        "willdurand/propel-publishable-behavior": "dev-master"
    }
}
```

Add the following line to your `propel.ini` or `build.properties` configuration file:

``` ini
propel.behavior.publishable.class = vendor.willdurand.propel-publishable-behavior.src.PublishableBehavior
```

> Note: `vendor.willdurand.propel-publishable-behavior.src` is the path of the behavior in dot-path notation.


Usage
-----

### ActiveRecord API ###

Returns true if the object is published, false otherwise:

    isPublished()

Mark an object as published:

    publish(PropelPDO $con = null)

Mark an object as not published:

    unpublish(PropelPDO $con = null)

Has publication frame started ?

    hasPublicationEnded()

Has publication frame started ?

    hasPublicationStarted()

### ActiveQuery API ###

Include unpublished objects in the results set:

    includeUnpublished()

Filter by objects not published exclusively:

    filterUnpublished()

Filter by objects published exclusively:

    filterPublished()

Filter by publication active

    filterByPublicationActive($date = 'now')

Mark an object as published:

    publish(PropelPDO $con = null)

Mark an object as not published:

    unpublish(PropelPDO $con = null)

Parameters
----------

``` xml
<behavior name="publishable">
    <parameter name="is_published_column" value="is_published" />
    <parameter name="published_by_default" value="false" />
    <!-- timeframe support -->
    <parameter name="with_timeframe" value="false" />
    <parameter name="published_at_column" value="published_at" />
    <parameter name="published_until_column" value="published_until" />
    <!-- start and end value can be null -->
    <parameter name="require_start" value="false" />
    <parameter name="require_end" value="false" />
</behavior>
```

Running tests
-------------

First of all, copy the `phpunit.xml.dist` to `phpunit.xml`.

If you did not install propel with composer, change `PROPEL_DIR` and `PHING_DIR`
values to customize the include path.
Customize autoloader by changing `AUTOLOAD` property.

then simply launch

``` bash
$ phpunit -c phpunit.xml
```

All green?


Credits
-------

* William Durand <william.durand1@gmail.com>
* Julien Muetton <julien_muetton@carpe-hora.com>
