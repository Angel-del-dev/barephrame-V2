When using Barephrame, certain tasks should/must be
automated. For that purpose, php `src/Cli/exec.php`
can be used as an advantage.

# Flags

When calling `exec.php`, there's two types of flags.  
## Flags without values
```bash
# Used to walk through the /pages directory
# and pre-cache all the routes
*/exec.php --cache-routes
```

```bash
# Used to generate the required structure
# to work correctly, Must use only after fresh
# install
*/exec.php --scaffold
```
## Flags with values
```bash
# Used to generate a full code structures
# including the files with the boilerplate code
# Types of structures:
# Domain
*/exec.php --make {type} {name}
```