Wikimap plugin 0.7.4
====================
Wikimap for your website.

[See demo](https://jar.io/wikimap/).   
Wikimap: https://jar.io/wikimap/page:wikimap.xml

## How to install plugin

1. [Download and install Datenstrom Yellow](https://github.com/datenstrom/yellow/).
2. [Download plugin](https://github.com/datenstrom/yellow-plugins/raw/master/zip/wikimap.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy `wikimap.zip` into your `system/plugins` folder.

To uninstall delete the [plugin files](update.ini).

## How to use a wikimap

The wikimap is available as `http://website/wikimap/` and `http://website/wikimap/page:wikimap.xml`. It's an overview of the entire website, only visible pages are included. You can add a link to the wikimap somewhere on your website. See example below.

## How to configure a wikimap

The following settings can be configured in file `system/config/config.ini`:

`WikimapLocation` = wikimap location  
`WikimapFileXml` = wikimap file name with XML information  
`WikimapPaginationLimit` = number of entries to show per page 

Example:

```
WikimapLocation: /wikimap/   
WikimapFileXml: wikimap.xml   
WikimapFilter: wiki     
WikimapPaginationLimit: 100    
```

## Developer

Datenstrom. [Get support](https://developers.datenstrom.se/help/support).
https://github.com/jariobr
