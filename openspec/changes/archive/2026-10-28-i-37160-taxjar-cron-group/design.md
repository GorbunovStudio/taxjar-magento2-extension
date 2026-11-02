# Design

## Context

See proposal.md - Why. Established facts:

- `vendor/taxjar/module-taxjar/etc/crontab.xml` puts both jobs in group `taxjar_crongroup`. No `cron_groups.xml` in the module, in `app/code`, or in `patches/` defines that group, and `core_config_data`, `app/etc/env.php`, and `app/etc/config.php` have no `system/cron/taxjar_crongroup` values.
- `Magento\Cron\Observer\ProcessCronQueueObserver` reads `system/cron/<group>/schedule_ahead_for`. For an undefined group the value is 0, so `saveSchedule()` creates no records. `Magento\Cron\Model\System\Config\Initial\Converter` adds the values from every `cron_groups.xml` to the initial configuration under `system/cron/<group>/`.
- In production the cron container runs `bin/magento cron:run` every minute without `--group` (`GorbunovStudio/budsies-docker-images`, `store-m2-app/cron/crontab`). This run processes every group that `crontab.xml` declares, so a defined `taxjar_crongroup` runs without infrastructure changes.
- The store installs `taxjar/module-taxjar` with the constraint `dev-budsies as 2.2` from the private Composer repository `https://composer.dev.budsies.com/`, which serves branches of the fork.

## Goals / Non-Goals

**Goals:** define `taxjar_crongroup` in the module that declares it.

**Non-Goals:**

- Upgrading the TaxJar module to upstream `v4.0.2`. On 2026-10-28 the team decided not to install the newer version for now.
- Changes to the TaxJar job handlers, their schedules, or their configuration settings.

## Decisions

### Define the group in the TaxJar fork

Add `etc/cron_groups.xml` to the fork branch `37160-taxjar-cron-group`. The module that declares the group owns its definition (standards guide, "Choosing where to fix"). The user selected this option on 2026-10-28.

Alternatives considered:

- Define the group in a Budsies module: the group configuration would live outside the module that declares the jobs.
- A Composer patch in this repository: the fork is ours, so a patch adds a second place to maintain the same module.
- Move the jobs to the `default` group: this edits the module's `crontab.xml` instead of adding the missing definition.

### Use the values of the Magento `default` group

| Setting | Value |
| --- | --- |
| `schedule_generate_every` | 15 |
| `schedule_ahead_for` | 20 |
| `schedule_lifetime` | 15 |
| `history_cleanup_every` | 10 |
| `history_success_lifetime` | 60 |
| `history_failure_lifetime` | 4320 |
| `use_separate_process` | 0 |

Source: `vendor/magento/module-cron/etc/cron_groups.xml`, group `default`. The TaxJar jobs have no requirement that differs from the default group. A schedule-ahead window (20) longer than the generation interval (15) covers every minute, so each selected time gets a record. The `budsies_general` values (generation every 1 minute, lifetime 2 minutes, separate process) serve frequent Budsies jobs and give no benefit to two monthly jobs.

### Install the fork branch with an alias

`composer.json` sets `"taxjar/module-taxjar": "dev-37160-taxjar-cron-group as 2.2"` until the fork pull request is merged into `budsies` (user decision, 2026-10-28). The alias keeps the version that other constraints see unchanged. Only `taxjar/module-taxjar` is updated in `composer.lock`.

## Risks / Trade-offs

- [`taxjar_backup_rates` changes Magento tax rates every month, and it removes all TaxJar backup rates and sets `tax/taxjar/backup` to 0 when the API key is missing] → Approved behavior for #37160 (2026-10-28). The Kintsugi migration must take it into account when the TaxJar API key is removed.
- [The fork branch is not yet listed by the private Composer repository] → Push the fork branch before the Composer update, and confirm the branch with `composer show -a taxjar/module-taxjar` first.
- [The fork branch reference stays in `composer.json` after the fork merge] → Before the store pull request is merged, set the constraint back to `dev-budsies as 2.2` and update `composer.lock`.

## Migration Plan

1. Merge the fork pull request into `budsies`.
2. In the store branch, set the constraint back to `dev-budsies as 2.2` and update `composer.lock` for `taxjar/module-taxjar` only.
3. Deploy the store release. The Magento cron generates the TaxJar schedules within the generation interval of the group.

Rollback: install the previous `taxjar/module-taxjar` reference. The group then has no definition again, and the jobs stop being scheduled.
