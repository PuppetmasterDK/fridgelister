# FridgeLister

Know what is in the fridge, and who is doing the shopping.

FridgeLister started as a small idea: older people who live alone often have a
fridge full of things past their best, and a family member or neighbour who
helps with the shopping. FridgeLister lets them share one list.

## What it does

- Keep one or more fridges, each with a list of what is in it.
- Every item has an optional best-before date. Items that expire within the
  next three days are marked **use soon**. Items past their date are marked
  **past best before**.
- Mark items as used, or remove them.
- Invite a helper by email. The helper accepts and promises a date they will
  do the shopping by, no more than two weeks ahead.
- **What to buy** suggests things the household has used at least twice in
  the last month and has run out of.
- **History** shows what has been used.

## Run it in your browser

No install needed. On GitHub, click **Code → Codespaces → Create codespace
on main**. After a couple of minutes you have an editor and a terminal with
PHP, Composer and Claude Code installed and the demo data loaded. Then:

```bash
php artisan serve
```

and click **Open in Browser** when it pops up.

## Run it on your own computer

You need PHP 8.4.1 or newer, Composer, and Git. No Node, no database server:
FridgeLister uses SQLite.

```bash
composer setup
php artisan serve
```

Open http://127.0.0.1:8000 and log in as:

| Who | Email | Password |
|---|---|---|
| Grandma Inge, owns the Kitchen fridge | grandma@example.com | password |
| Mads, her helper | helper@example.com | password |

`composer setup` creates `.env`, the SQLite database and the demo data. Run it
again at any time to start from a clean fridge.

## Tests

```bash
composer test
```

## The public demo

The public demo resets every night with `php artisan fridge:demo`, which
reloads the demo data and adds a few random items.
