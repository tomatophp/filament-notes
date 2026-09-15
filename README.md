![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-notes/master/arts/3x1io-tomato-notes.jpg)

# Filament Sticky Notes

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-notes/version.svg)](https://packagist.org/packages/tomatophp/filament-notes)
[![License](https://poser.pugx.org/tomatophp/filament-notes/license.svg)](https://packagist.org/packages/tomatophp/filament-notes)
[![Downloads](https://poser.pugx.org/tomatophp/filament-notes/d/total.svg)](https://packagist.org/packages/tomatophp/filament-notes)
[![Tests](https://github.com/tomatophp/filament-notes/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-notes/actions/workflows/tests.yml)

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
- [x] Public/Private Notes
- [x] Notes Groups
- [x] Notes Status
- [x] Notes To Notifications
- [x] Share Notes With Public Link
- [x] Share Notes With Selected User
- [x] Notes CheckLists
- [ ] Notes Reminders
- [ ] Notes Font Family
- [ ] Attach Notes To Models
- [ ] Notes Templates

## Compatibility

| Package version | Filament | Laravel | PHP |
|-----------------|----------|---------|-----|
| 5.x | 5.x | 12.x / 13.x | 8.2+ |
| 1.x ([`v3` branch](https://github.com/tomatophp/filament-notes/tree/v3)) | 3.x | 10.x / 11.x | 8.1+ |

## Installation

```bash
composer require tomatophp/filament-notes
```
after install your package please run this command

```bash
php artisan filament-notes:install
```

Finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make())
```

Notes are linked to your user model, you can change it from the config file `filament-notes.models.user` (default `App\Models\User`).

## Use Widget

you can use it as a resource or you can use it as a widget by just register a widget on your panel provider like this

```php
$panel->widgets([
    \TomatoPHP\FilamentNotes\Filament\Widgets\NotesWidget::class
])
```

the widget shows the pinned notes the current user can see, you can change how many notes it shows

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->widgetLimit(8)
)
```

## Use Livewire Component

you can use selected note anywhere using livewire component

```html
<livewire:note-action :note="$note" />
```

## Use Groups & Status

groups and status are stored with [filament-types](https://www.github.com/tomatophp/filament-types) (installed with this package), you can use this feature by add this methods to the plugin

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->useStatus()
    ->useGroups()
)
```

the default groups (`todo`, `ideas`, `saved`) and statuses (`pending`, `processing`, `done`) are created the first time you open the Notes Groups / Notes Status pages from the notes page header.

## Use Convert Note To Notification

you can send a note as a Filament database notification to selected users by adding this method to the plugin (your panel needs `->databaseNotifications()` and the `notifications` table)

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->useNotification()
)
```

## Use Share Note With Public Link

you can generate a public link and share it with others by allowing this feature on your provider

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->useShareLink()
)
```

## Use User Access Permissions

you can use this feature to allow only selected users to access the notes by allowing this feature on your provider

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->useUserAccess()
)
```

## Use Checklist

you can use this feature to add a checklist to your notes by allowing this feature on your provider

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->useCheckList()
)
```

## Customize Note Resource Icon

you can customize the note resource icon by adding this method to the plugin

```php
$panel->plugin(\TomatoPHP\FilamentNotes\FilamentNotesPlugin::make()
    ->navigationIcon('heroicon-o-presentation-chart-line')
)
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

## Testing

```bash
composer test
```

## Code Style

```bash
composer format
```

## PHPStan

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
