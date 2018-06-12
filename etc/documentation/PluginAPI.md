## Plugin API

### Endpoints

1. *GET* /api/plugins//check-latest-version[slug]
2. *GET* /api/plugins/get-plugin-information/[slug]
3. *POST* /api/plugins/track-installations

Endpoints 1 and 2 require license data headers to be set, endpoint 3 currently does not.

#### 1. *GET* /api/plugins/check-latest-version/[slug]

Additional request parameters: none  
Returns JSON  
Response body structure: 

```
{
    "slug": "test-plugin",
    "new_version": "1.4.1",
    "package": "http://wpcr.local/download/plugin/test-plugin/latest"
}
```

It contains the slug, the newest version and the download link for the latest version. 
Note that you can download a specific version as well by suppling the version string
instead of the word 'latest', e.g. `http://wpcr.local/download/plugin/test-plugin/1.2.8`.

No functionally relevant values are being returned.

#### 2. *GET* /api/plugins/get-plugin-information/[slug]

Additional request parameters: none  
Similar to 1. it returns plugin information but in a more extensive way.  
Response body structure:

```
{
    "name": "Test Plugin",
    "version": "1.4.1",
    "requires_php": "5.3",
    "slug": "test-plugin",
    "author": "Robin Kaiser",
    "author_profile": "https://kaiserrobin.eu",
    "requires": "3.8",
    "tested": "4.9.6",
    "rating": 87.5,
    "num_ratings": 8,
    "downloaded": 187,
    "active_installations": "122",
    "last_updated": "2018-05-29 12:48:29",
    "added": "2018-06-12 09:24:30",
    "homepage": "http://wpcr.local/info/plugin/test-plugin",
    "ratings": {
        "1": "0",
        "2": "1",
        "3": "0",
        "4": "0",
        "5": "7"
    },
    "sections": {
        "description": "Extensive descriptions",
        "installation": "Install Stuff",
        "faq": "FAQ Stuff",
        "screenshots": "Some Screenshots with text here",
        "changelog": "1.1 XX 1.2.1 YY 1.3 ZZ etc.",
        "other_notes": "A place for information that does not really fit into any other section"
    },
    "banners": [
        "low": "http://wpcr.local/assets/banners/test-plugin/test-plugin_low.png",
        "high": ""
    ],
    "download_link": "http://wpcr.local/download/plugin/test-plugin/1.4.1"
}
```

* The values `rating` and `num_ratings` are a calculation from the individual ratings. `rating` is a percentage of best possible ratings, e.g. 87.5% of all ratings are 5 stars.. 
* Besides the `description` section, all other sections are optional.
* Banners are optional but help making your plugin look greatly.

No functionally relevant values are being returned.

#### 3. *POST* /api/plugins/track-installations

Required additional parameters:
* slug (the slug of the plugin reporting to be (un)installed)
* version (the version if the plugin)
* action (currently possible values: `installed` and `uninstalled`)

No functionally relevant values are being returned.

