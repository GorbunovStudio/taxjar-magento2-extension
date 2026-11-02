# Proposal

## Why

Redmine #37160: the TaxJar module declares the monthly cron jobs `taxjar_backup_rates` and `taxjar_product_categories` in the cron group `taxjar_crongroup`, but no configuration defines that group. Magento therefore schedules zero runs for the group, and the jobs never run, without any error.

## What Changes

- The TaxJar fork (`GorbunovStudio/taxjar-magento2-extension`) defines the cron group `taxjar_crongroup`, so Magento schedules and runs both TaxJar jobs on the schedules that the module declares.
- The store installs the TaxJar package from the fork branch of this issue until the fork pull request is merged into `budsies`.
- The job schedules, the job handlers, and the other cron groups do not change.

## Capabilities

### New Capabilities

- `taxjar-scheduled-imports`: Scheduling and execution of the TaxJar monthly import jobs (backup tax rates and product tax categories) by the Magento cron.

### Modified Capabilities

## Impact

- Repository `GorbunovStudio/taxjar-magento2-extension`, branch `37160-taxjar-cron-group` (pull request into `budsies`): new `etc/cron_groups.xml`.
- This repository: `composer.json` and `composer.lock` for `taxjar/module-taxjar`.
- Runtime: on the 1st of each month, `taxjar_backup_rates` calls the TaxJar API and updates or removes the "TaxJar Backup Rates" tax rates in Magento, depending on `tax/taxjar/backup` and the API key. `taxjar_product_categories` imports the TaxJar product tax categories when the API key is set. This behavior was approved for #37160 on 2026-10-28.
