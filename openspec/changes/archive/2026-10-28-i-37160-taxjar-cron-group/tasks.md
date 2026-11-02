# Tasks

## 1. TaxJar fork

- [x] 1.1 In `/home/developer/taxjar-magento2-extension`, branch `37160-taxjar-cron-group`, add `etc/cron_groups.xml` with group `taxjar_crongroup` and the values from design.md; verify the file validates against `vendor/magento/module-cron/etc/cron_groups.xsd` of this repository (`php -r` with `DOMDocument::schemaValidate`).
- [x] 1.2 Commit only `etc/cron_groups.xml` in the fork with `Refs #37160: Cron - define taxjar_crongroup cron group`, push the branch, and verify that `composer show -a taxjar/module-taxjar` in this repository lists `dev-37160-taxjar-cron-group`.

## 2. Store dependency

- [x] 2.1 Record the baseline: `XDEBUG_MODE=off bin/magento config:show system/cron` output, saved to the ticket folder; verify that it has no `taxjar_crongroup` entries.
- [x] 2.2 Set `"taxjar/module-taxjar": "dev-37160-taxjar-cron-group as 2.2"` in `composer.json` and run `composer update taxjar/module-taxjar`; verify that `composer.lock` changes only the `taxjar/module-taxjar` entry and the content hash, that the lock reference equals the fork branch HEAD, and that `vendor/taxjar/module-taxjar/etc/cron_groups.xml` exists.
- [x] 2.3 Run `composer validate --no-check-publish`; verify that it reports no errors.

## 3. Verification

- [x] 3.1 Run `XDEBUG_MODE=off bin/magento cache:clean config --quiet`, then `XDEBUG_MODE=off bin/magento config:show system/cron`; verify that `system/cron/taxjar_crongroup/*` has the design.md values and that all other lines equal the baseline from 2.1.
- [x] 3.2 With the operator's approval, run `XDEBUG_MODE=off bin/magento cron:run --group=taxjar_crongroup` once; verify that the command succeeds and that `cron_schedule` has no new `taxjar%` records when no selected time (`0 8 1 * *`, `30 8 1 * *`) is inside the next 20 minutes.
