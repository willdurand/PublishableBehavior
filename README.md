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


### ActiveQuery API ###

Include unpublished objects in the results set:

    includeUnpublished()

Filter by objects not published exclusively:

    filterUnpublished()

Filter by objects published exclusively:

    filterPublished()

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
</behavior>
```


Todo
----

* More unit tests
* Add time frames support (object published from `9999-99-99` to `9999-99-99`)


Credits
-------

William Durand <william.durand1@gmail.com>
