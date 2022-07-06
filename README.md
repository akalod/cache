# cache
basic redis cache operator

## install
```bash
composer require akalod/cache
```

## examples
```php
use Akalod/Cache;

//init redis connection
Cache::init(); //host, port, authPass, 

//set Default cacheTime
Cache::cacheTime = 200; 

//set 
Cache::set('key','value',400); // key, value, cacheTime

//get
Cache::get('key');

//set and get with callback example
$result = Cache::getSet('keyName', function () {
            //value
            return DB::table('pages')
                ->where('status', 1)
                ->where('group', 1)
                ->orderBy('short', 'asc')
                ->get(['seo', 'title', 'page_title']);
    }, 700); //key, callback, cacheTime
```
