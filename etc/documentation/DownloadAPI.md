## Download API

### Endpoints

1. *GET* /download/plugin/[:slug]/[:version]
2. *GET* /download/theme/[:slug]/[:version]

Both endpoints are almost identical. The only relevant difference lies
in the `downloaded` property which is missing in themes and is therefore not incremented.

Version can be `latest` or a version string like `1.2.3`.


#### 1. + 2. /download/{item}/[:slug]/[:version]

Required additional parameters: none

Response body structure: *binary*

Returns the binary data of the requested file. In a browser, the download will start
immediately.
In a script you can fetch the response body and write it to a file.