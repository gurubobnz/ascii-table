Simple ASCII Table Generator
============================

Features
--------

* Create tables suitable for CLI output quickly
* No external dependencies

View/run example.php for examples. This file is the output of that script:

```
Create table via constructor:
┌───────────────────┬─────────────┐
│ Name              │ Country     │
├───────────────────┼─────────────┤
│ Bob Brown         │ New Zealand │
│ Wolfgang Puck     │ America     │
│ Winston Churchill │ England     │
└───────────────────┴─────────────┘

Create table via OO interfaces:
┌───────────────────┬───────────────┐
│ Sales Agent       │ Travelling To │
├───────────────────┼───────────────┤
│ Bob Brown         │ New Zealand   │
│ Wolfgang Puck     │ America       │
│ Winston Churchill │ England       │
└───────────────────┴───────────────┘

Output same table with different headers (reusing defined table):
┌───────────────────┬─────────────────────┐
│ Favourite Person  │ County Of Residence │
├───────────────────┼─────────────────────┤
│ Bob Brown         │ New Zealand         │
│ Wolfgang Puck     │ America             │
│ Winston Churchill │ England             │
└───────────────────┴─────────────────────┘

ASCII borders instead of box:
+-------------------+---------------------+
| Favourite Person  | County Of Residence |
+-------------------+---------------------+
| Bob Brown         | New Zealand         |
| Wolfgang Puck     | America             |
| Winston Churchill | England             |
+-------------------+---------------------+
Note: Made a copy() so that the following header would retain box format.

Table with no headers:
┌───────────────────┬─────────────┐
│ Bob Brown         │ New Zealand │
│ Wolfgang Puck     │ America     │
│ Winston Churchill │ England     │
└───────────────────┴─────────────┘

Create by chaining setters and adding individual rows:
╔══════════╦═══════╗
║ Product  ║ Price ║
╠══════════╬═══════╣
║ Apples   ║ $1.29 ║
║ Bananas  ║ $1.69 ║
║ Cherries ║ $2.99 ║
╚══════════╩═══════╝

```
