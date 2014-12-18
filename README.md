MOC.Redirects
=============

TYPO3 Neos package that allows for entering an old URL that will be redirected to the page.

Matches relative and absolute URLs regardless of schema (http/https) including query string (hash is ignored).

E.g.:

| Redirect URL             | Request URL              |
| ------------------------ |:------------------------:|
| acme.com/test            | http://acme.com/test     |
| acme.com/test            | https://acme.com/test    |
| acme.com/test/a          | http://acme.com/test/a   |
| http://acme.com/test     | http://acme.com/test     |
| http://acme.com/test     | https://acme.com/test    |
| https://acme.com/test    | https://acme.com/test    |
| https://acme.com/test    | http://acme.com/test     |
| http://acme.com/test?a=b | http://acme.com/test?a=b |
| http://acme.com/test#foo | http://acme.com/test#bar |

Configuration/Routes.yaml
-------------------------

```yaml
-
  name: 'MOC Redirects'
  uriPattern: '<MOCRedirectsSubroutes>'
  subRoutes:
    'MOCRedirectsSubroutes':
      package: 'MOC.Redirects'
```