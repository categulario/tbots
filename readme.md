# Telegram Bots

## Install

* Clone the repo
* run `composer install`
* copy `.env.example` to `.env` and tune

## @mntnwttrbot

### Dependencies

* compute mountain ranges using `resources/datamining/get_mountains.py`
* compute mountain list using `resources/datamining/make_mountain_list.py`

### Inline Mode

Start writing a peak name and it will suggest mountains. Choose one to get the weather forecast at the summit.

## @eqxbot

### Dependencies

* latex installed, we need the `pdflatex` command
* `imagemagick` because we need the `convert` command

### Inline Mode

Write latex code and use it in your conversations.
