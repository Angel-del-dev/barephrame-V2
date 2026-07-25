When using Barephrame, certain tasks can be
automated. For that purpose, `./Barephrame/cli/tools.php`
can be used as an advantage.

Flags can be chained together if necessary. There's no need to execute two
commands when they can be chained in one expression

# Example
```bash
php ./Barephrame/cli/tools.php --first-flag --second flag
```

# Flags

## Project
```bash
--init # Used to scaffold the initial project structure. It does not remove already existing files
```

## Routes
`Barephrame` uses precompiled endpoints to minimize 
response times.

By default, no endpoints are known even if exist in the project.
Every time an endpoint is changed, added or removed, it needs to
recompile. Or use the automated compilation mechanism(`See example`)

```bash
--compile-routes # It compiles routes once

--watch # Every second, it checks if any endpoint has been added and recompiles all the endpoints(Development only usage)
```