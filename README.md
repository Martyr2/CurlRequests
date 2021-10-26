# CurlRequests Class

This is a simple and easy to use class for making cURL calls in PHP. It attempts to abstract away many of the nuances of using the cURL functions and gives a consistent response in return to all calls (successful or not). Consumers of this class will always get back a `stdClass` object with information on the success or failure of the request. 

## Features

- No extra dependencies
- Works with PHP 5.6+
- Easy to turn off SSL Verification for testing
- Quick setting of extra cURL options and default option overriding
- Easy to add custom headers (like Authentication tokens, different content-types etc.)
- POST data will also work with mbstring extension if enabled for use with UTF-8 encoding

## Usage

This class is used in a static context and only provides two methods, `GET` and `POST`. Below is a quick example of how you can use this. Notice you don't need to look for `false` return values or deal with exceptions. If it is a cURL error, the properties `errorno` and `errormsg` can be used to get the error. Otherwise, you can use `status_code` to determine the response.

```php
// Typical GET request
$result = CurlRequests::get('https://www.example.com');

// If a cURL error happened... easy
if (property_exists($result, 'errorno')) {
    error_log("Yikes! cURL error {$result->errorno} happened with message {$result->errormsg}");
    return;
}

// If successful, $result will have a status_code and content property
if (property_exists($result, 'status_code') && ($result->status_code === 200)) {
    echo "Success! Here is your content {$result->content}";
}
```

It is very configurable. Here are some examples...

```php
// Typical POST request (www-form-urlencoded)
$result = CurlRequests::post('https://www.example.com', 'hello=world');

// Oh need to post JSON?
$result = CurlRequests::post('https://www.example.com', '{"hello": "world"}', ["content-type" => "application/json"]);

// Need to pass an authentication bearer token during a GET request?
$result = CurlRequests::get('https://www.example.com', ['Authentication' => "Bearer 123456789"]);
```

**NOTE:** Based on the code you can easily add additional request methods if you like by setting a custom option `CURLOPT_CUSTOMREQUEST` when making a `POST` request. 

## License

The CurlRequests class is software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Questions or to Report An Issue

If you would like to report an issue with this code, please [email us](mailto:github@coderslexicon.com).
