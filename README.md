# Combination Iterator

### Installation
```shell
composer install creatortsv/combination-iterator
```

### Usage
```php
use Creatortsv\CombinationIterator\CombinationLengthIterator;
use Creatortsv\CombinationIterator\CombinationIterator;

$combinations = new CombinationIterator(
    [1, 2, 3],
    [1, 2, 3],
    [1, 2, 3],
    [1, 2, 3],
);

// The same as ...

$combinations = new CombinationLengthIterator(
    iterator: new CombinationIterator([1, 2, 3]),
    length: 4,
);

print_r($combinations->count()); // prints 81 total combinations
print_r($combinations->length); // prints 4

foreach ($combinations as $combination) {
    // Search matching of [#, #, #, #] symbols ...
}
```
