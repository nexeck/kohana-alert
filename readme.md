# [Alert Module](https://github.com/nexeck/kohana-alert) for the Kohana framework

## Features
* Supports [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
* Supports Twig template engine [Twig Kohana module](https://github.com/nexeck/kohana-twig)

---
## Installation

Add the submodule:

    git submodule add git://github.com/nexeck/kohana-alert.git modules/alert

**Note:** [Guide for Kohana modules](http://kohanaframework.org/3.2/guide/kohana/modules)

### Kohana Modules
    Kohana::modules(array(
        ...
        'alert' => MODPATH . 'alert',

### Twig configuration

    'extensions' => array
    (
        ...
        'Nexeck_Twig_Extensions_alert',
    ),

---
## Alert types (constants)

    Alert::Alert
    Alert::ERROR
    Alert::SUCCESS
    Alert::INFO

---
## Usage


### Set alerts

    // Set a text
    Alert::set(Alert::INFO, 'INFO Text');

    // Set a text with an optional subject
    Alert::set(Alert::INFO, 'INFO Text', array(), 'INFO Subject');

    // Set a Text with an optional subject as block style
    Alert::set(Alert::INFO, 'INFO Text Block', array(), 'INFO Subject Block', true);

    // Set a Text as block style
    Alert::set(Alert::INFO, 'INFO Text Block', array(), null, true);

    // Embed some values with sprintf
    Alert::set(Alert::INFO, 'INFO Text: %s', array('Embed values with sprintf in text'), 'INFO Subejct: %s');

    // Embed some values with strtr
    Alert::set(Alert::INFO, 'INFO Text: :string', array(':string' => 'Embed values with strtr'), 'INFO Subject: :string');

### Show them within a twig template

    {% include "alert/bootstrap.twig" %}

**Note:** This uses Alert::get_once()

### get()

If no arguments are passed, `get()` will return all alerts.

    // Get all alerts
    $alerts = Alert::get();

If you only want an alert of a certain type (e.g. INFO) then simply pass in the constant holding the type.

    // Get error alerts
    $alerts = Alert::get(Alert::INFO);

You can also pass an array of types.

    // Get alerts and errors
    $alerts = Alert::get(array(Alert::ALERT, Alert::ERROR));

If no messages are found, `get()` will return `array()`.

### get_once()

`get_once()` behaves exactly like `get()`, but the only difference is, `get_once()` deletes the alerts after retrieval.

    // Get alerts
    $alerts = Alert::get_once();

    Alert::get(); // Returns array()

    // `get_once` also retrieves by type
    $alerts = Alert::get_once(Alert::ALERT);

### delete()

    // Delete alerts
    Alert::delete();

    // Delete alert alerts
    Alert::delete(Alert::ALERT);
