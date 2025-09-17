# Notes App (PHP + MySQL)

Simple notes app with user registration, login, and personal notes (create/delete).

## Setup

1. Start Apache and MySQL in XAMPP.
2. Create DB and tables:
   - Open phpMyAdmin → Import `schema.sql`
   - Or run it in your MySQL client.
3. Configure DB credentials if needed in `config.php`.
4. Visit `http://localhost/jean-withdatabase/register.php` to create an account.
5. Login at `http://localhost/jean-withdatabase/login.php`.
6. Manage notes at `http://localhost/jean-withdatabase/`.

## Files

- `schema.sql` — database schema
- `config.php` — session and MySQL connection
- `register.php` — user registration
- `login.php` — user login
- `logout.php` — sign out
- `index.php` — list/create/delete notes
- `assets/styles.css` — styles

## Notes

- Passwords are hashed using PHP `password_hash`.
- Deleting a user (in DB) cascades and deletes their notes.
- Adjust `$dbUser/$dbPass` in `config.php` if your MySQL has a password.
