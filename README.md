This is my 1-day trial work, developed with Laravel v10 and MySQL as database, Laravel Breeze Starting KIT and Inertia.js

## Task
Create an application with which user can record his hours
worked. For this purpose, time logs should
be created, edited and deleted. In addition,
there should be an overview of how much
was worked per day / week / month.

### What was done
- Created a form to record working hours per project with a "start / stop" button.
- The time logs are displayed in a table,
  and are correctable via an edit button
  and deletable via a delete button.
- Added summary with how much time was spent per day / month.

### Additionaly:
- Authentication using Laravel Breeze
- Added seeder with default user and random time tracking records, created with Faker
- Added Excel export for time tracking records
