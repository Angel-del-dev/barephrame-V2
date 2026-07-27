# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial project scaffolding with PSR-4 autoloader support.
- Application entrypoint to bootstrap the framework.
- CLI tool interpreter with an `init` command to scaffold new projects, including automatic creation of the `public` folder.
- Route attribute system for defining endpoints directly on controller methods.
- CLI tooling to compile and watch route endpoints for changes.
- Main router to dispatch incoming requests to their corresponding endpoints.
- Common HTTP response types and structured error responses.
- API response renderer for consistent output formatting.
- Engine-agnostic database connection layer with support for multiple database engines.
- Full middleware support before calling any controller
- Apache License 2.0.

### Fixed
- Bad request handling on the root endpoint.
- Database connections are now stored and reused after creation instead of being recreated on every call.

### Documentation
- Added initial README with project overview, key features, and philosophy.
- Added contributing guidelines.
- Documented CLI tools usage (`docs/CLI.md`).
- Documented endpoints and routing (`docs/Endpoints.md`).
- Documented database connection usage (`docs/Database.md`).
- Expanded README with additional project information.