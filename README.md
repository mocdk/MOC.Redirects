MOC.Redirects
=============

TYPO3 Neos package that allows for entering an old URL that will be redirected to the page.

Depends on TYPO3 Neos 1.2

> !!! Not compatible with language dimensions.

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
The package can be easily installed by using composer - just add the following line to your `composer.json` file:

```composer require "moc/redirects" "1.0.*"```

Put this in your global Configuration/Routes.yaml
```yaml
-
  name: 'MOC Redirects'
  uriPattern: '<MOCRedirectsSubroutes>'
  subRoutes:
    'MOCRedirectsSubroutes':
      package: 'MOC.Redirects'
```
