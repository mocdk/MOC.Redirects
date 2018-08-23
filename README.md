MOC.Redirects
=============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mocdk/MOC.Redirects/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mocdk/MOC.Redirects/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/moc/redirects/v/stable)](https://packagist.org/packages/moc/redirects)
[![Total Downloads](https://poser.pugx.org/moc/redirects/downloads)](https://packagist.org/packages/moc/redirects)
[![License](https://poser.pugx.org/moc/redirects/license)](https://packagist.org/packages/moc/redirects)

Introduction
------------

Neos CMS package that allows for entering an old URL that will be redirected to the page.

Compatible with Neos 4.x, 3.x

**!!! Not compatible with language dimensions**

Matches relative and absolute URLs regardless of schema (http/https) including query string (hash is ignored).

Examples:

| Redirect URL             | Request URL               |
| ------------------------ |:-------------------------:|
| /test                    | http://acme.com/test      |
| /test/                   | http://acme.com/test/     |
| /test/bar                | https://acme.com/test/bar |
| acme.com/test            | http://acme.com/test      |
| acme.com/test            | https://acme.com/test     |
| acme.com/test/a          | http://acme.com/test/a    |
| http://acme.com/test     | http://acme.com/test      |
| http://acme.com/test     | https://acme.com/test     |
| https://acme.com/test    | https://acme.com/test     |
| https://acme.com/test    | http://acme.com/test      |
| http://acme.com/test?a=b | http://acme.com/test?a=b  |
| http://acme.com/test#foo | http://acme.com/test#bar  |

Installation
------------
composer require "moc/redirects:~3.0"
