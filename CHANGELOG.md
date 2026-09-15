# Changelog

## v5.0.0

- Support Filament v5, Livewire 4 and Laravel 12 / 13 (PHP 8.2+).
- Require `tomatophp/filament-types` ^5.0 and `tomatophp/filament-icons` ^5.0.
- Forms, tables and actions moved to the Filament v5 schema / action APIs; the `NoteAction` Livewire component now implements `HasSchemas`.
- Group and status selects show the translated type names; the status filter now follows `useStatus()` instead of `useGroups()`.
- Fixed the notes visibility query (public, own and shared notes) so it no longer leaks through other table filters, and shared-user access now matches JSON cast meta values.
- `useNotification()` now sends Filament database notifications to the selected users (the old `filament-alerts` v1 API is gone).
- The public share page no longer needs a Vite build and returns 404 for unknown share links.
- Checklist toggles are limited to the note owner.
- Added a Pest test suite (Testbench) and GitHub Actions workflow.
