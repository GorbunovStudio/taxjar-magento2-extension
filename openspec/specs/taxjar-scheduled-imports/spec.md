# taxjar-scheduled-imports Specification

## Purpose
Ensure that the Magento cron schedules and runs the TaxJar monthly import jobs for backup tax rates and product tax categories.

## Requirements

### Requirement: TaxJar import jobs are scheduled
The Magento cron SHALL create `cron_schedule` records for `taxjar_backup_rates` and `taxjar_product_categories` at the times that their declared cron expressions select: `0 8 1 * *` and `30 8 1 * *`.

#### Scenario: Schedule generation covers a selected time
- **WHEN** the Magento cron generates schedules for group `taxjar_crongroup` and a time selected by a job's cron expression falls inside the group's schedule-ahead window
- **THEN** `cron_schedule` contains a `pending` record for that job with `scheduled_at` equal to that time

#### Scenario: No selected time in the window
- **WHEN** the Magento cron generates schedules for group `taxjar_crongroup` and no time selected by the job's cron expression falls inside the schedule-ahead window
- **THEN** `cron_schedule` contains no new record for that job

### Requirement: Scheduled TaxJar import jobs run
The Magento cron SHALL execute each pending TaxJar import job record when its scheduled time is reached and record the result in `cron_schedule`.

#### Scenario: Scheduled time is reached
- **WHEN** a `pending` record for `taxjar_backup_rates` or `taxjar_product_categories` reaches its `scheduled_at` time and the Magento cron runs within the group's schedule lifetime
- **THEN** the job handler runs and the record status becomes `success` or `error`

### Requirement: Other cron groups are unchanged
Defining the TaxJar cron group SHALL NOT change the configuration or the schedule generation of any other cron group.

#### Scenario: Other groups keep their configuration
- **WHEN** the merged Magento cron group configuration is read after the change
- **THEN** every group other than `taxjar_crongroup` has the same values as before the change
