# Combination Iterator

[![CI](https://github.com/creatortsv/combination-iterator/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/creatortsv/combination-iterator/actions/workflows/php.yml)

| Requirements |          |
| ------------ | -------- |
| PHP version  | `>= 8.1` |

## Installation
```shell
composer install creatortsv/combination-iterator
```

## Usage

Searching for pin code combination for example

```php
use Creatortsv\CombinationIterator\CombinationLengthIterator;
use Creatortsv\CombinationIterator\CombinationIterator;

$codeIterator = new CombinationIterator(
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
);

// The same as ...

$codeIterator = new CombinationLengthIterator(
    iterator: new CombinationIterator([1, 2, 3, 4, 5, 6, 7, 8, 9, 0]),
    length: 4,
);

print_r($codeIterator->count()); // prints 10000 total combinations
print_r($codeIterator->length); // prints 4

foreach ($codeIterator as $combination) {
    // Search matching of [#, #, #, #] symbols pin code ...
    //        for example [3, 5, 9, 7]
}
```

Executing some actions on each element

```php
// ... use

$functions = [
    static fn (int $n) => print_r('first execution for ' . $n),
    static fn (int $n) => print_r('second execution for ' . $n),
];

foreach (new CombinationIterator([1, 2, 3], $functions) as [$number, $function]) {
    $function($number);
}

// prints ...
// first execution for 1
// second execution for 1
// the same for the 2 and 3 ...
```

It works with any iterable objects

```php
$iterator = new CombinationIterator(
    new RecursiveArrayIterator([1, [2, 3], 4]),
    new MyOwnMagicIterator(),
);
```
