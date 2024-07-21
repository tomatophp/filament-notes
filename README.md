![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/3x1io-tomato-notes.jpg)

# Filament Sticky Notes

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-notes/version.svg)](https://packagist.org/packages/tomatophp/filament-notes)
[![License](https://poser.pugx.org/tomatophp/filament-notes/license.svg)](https://packagist.org/packages/tomatophp/filament-notes)
[![Downloads](https://poser.pugx.org/tomatophp/filament-notes/d/total.svg)](https://packagist.org/packages/tomatophp/filament-notes)

Add Sticky Notes to your FilamentPHP dashboard with tons of options and style

## Screenshots

![List](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/list.png)
![Create General](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/create-general.png)
![Create Style](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/create-style.png)
![View](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/view.png)
![Widget](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/widget.png)


## Features

- [x] Notes Resource
- [x] Notes Style
- [x] Pined Notes
- [x] Notes Background
- [x] Notes Font Color
- [x] Notes Border Color
- [x] Notes Font Widget
- [x] Notes Widget
- [x] Notes Widget Limit
- [ ] Notes Font Family
- [ ] Share Notes With Public Link
- [ ] Share Notes With Selected User
- [ ] Attach Notes To Models

## Installation

```bash
composer require tomatophp/filament-notes
```
after install your package please run this command

```bash
php artisan filament-notes:install
```

Finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make())
```

## Using

you can use it as a resource or you can use it as a widget by just register a widget on your panel provider like this

```php
$panel->widgets([
    \TomatoPHP\FilamentNotes\Filament\Widgets\NotesWidget::class
])
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-notes-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-notes-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-notes-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-notes-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/filament/filament-notes)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](https://wa.me/+201207860084)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
