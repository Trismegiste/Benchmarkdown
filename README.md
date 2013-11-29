# Benchmarkdown

Benchmark Markdown parsers

## What

I tiny-tyny quick & dirty script to benchmark markdown parsers

* [michelf/php-markdown](https://github.com/michelf/php-markdown)
* [erusev/parsedown](https://github.com/erusev/parsedown)
* [kzykhys/ciconia](https://github.com/kzykhys/Ciconia)

## Show me the result FFS !

* No APC 
* No X-Debug

On 1000 loops, I've got, in seconds :

```
erusev/parsedown: 1.4 s
michelf/php-markdown: 7.7 s
kzykhys/ciconia: 10.2 s
```

## And the winner is...

**erusev/parsedown**

## Notes

Here I'm only benchmarking the speed. There are others facts to consider :

* erusev/parsedown: works also on PHP 5.2, Github flavored but rely on a singleton anti-pattern
* michelf/php-markdown: strange but easy to extend, rely massively on hidden coupling
* kzykhys/ciconia: fully extendable with a symfony console (über-klass) but early stage

Stars this repository if you like it ! (thanks)
