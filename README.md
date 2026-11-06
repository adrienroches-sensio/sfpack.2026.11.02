SFPack - 2026.11.02
===================

# Install

```shell
$ git clone https://github.com/adrienroches-sensio/sfpack.2026.11.02.git
$ cd "./sfpack.2026.11.02"
$ symfony composer install
$ symfony console doctrine:migration:migrate --no-interaction --allow-no-migration
$ symfony console foundry:load-fixtures catalog --no-interaction
$ symfony serve
```

If you want to load 250 totally random books just use :
```shell
$ symfony console foundry:load-fixtures huge_catalog --no-interaction
```

# Fixtures
## Generate Factory & Story

```shell
$ symfony console make:factory <entityName>
$ symfony console make:story <name>
```

# Users

See roles matrix [here](./roles.mmd).

| username  | password  | roles                                                                                      |
|-----------|-----------|--------------------------------------------------------------------------------------------|
| reader    | reader    | `ROLE_USER`                                                                                |
| webmaster | webmaster | `ROLE_WEBMASTER` (inherits: [`ROLE_USER`])                                                 |
| librarian | librarian | `ROLE_LIBRARIAN` (inherits: [`ROLE_USER`])                                                 |
| manager   | manager   | `ROLE_MANAGER` (inherits: [`ROLE_LIBRARIAN`, `ROLE_USER`])                                 |
| admin     | admin     | `ROLE_ADMIN` (inherits: [`ROLE_WEBMASTER`, `ROLE_MANAGER`, `ROLE_LIBRARIAN`, `ROLE_USER`]) |
