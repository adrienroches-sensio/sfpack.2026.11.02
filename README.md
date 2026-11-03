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

# Fixtures
## Generate Factory & Story

```shell
$ symfony console make:factory <entityName>
$ symfony console make:story <name>
```
