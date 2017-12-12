Soundex Class
=============

With the Soundex Class, you can fetch data from a field of a MySQL table, that sounds the same like any given word.

It supports English and German words.

The class uses PHP's soundex() function for English words and also the Cologne phonetic for German ones.

Imagine you have a database (let's call the database "soundex")
and within it there is a table called "streets" that contains street names.

Now imagine you have a website that supports the languages English and German.
Your website visitors enter a street name during - let's say - a registration process.
Of course you could use PHP's soundex function to create auto-complete suggestions.
But that function considers only the English phonetik scheme. The German street
"Kirchstr." can be easily confused with "Kreuzstr." if PHP's soundex is in use.
                
Thanks to the Cologne phonetik, I managed to combine both languages
within a Soundex class! You only need to enter from what table and field you want to look
for the suggestions.

Of course it is experimental, so I do not give any warranties.

###How to use it


First, you will have to include the class file. Then you
can either create a Mysqli-object and use it as the 2nd argument
when you create the object, or you can add 4 Parameters instead containing
the database-server name, database-user name, database-password and finally the database-title.

If you plan to use this class for German words, please use
'de' as the first argument. Otherwise, if the words you want to
have suggested are in English, please use 'en'.

As the 2nd argument, you have to use an object of Mysqli.

```php
/**
* $o_mysqli = new mysqli( 'db-server', 'db-user', 'db-password', 'db-name' );
*
* $o_soundex = new Soundex( 'de', $o_mysqli );
*/
```

After the object (in our example, $o_soundex) is created,
let us imagine following situation: Website visitor types
the German street called "Konrad Adenauer Allee" into a text
field. Yes, there is a street called
like this, but I can tell you that there are no empty
spaces between the words (actually, there supposed to be dashes).

So we use following example:
```php
/**
* Let us imagine $_POST["streetname"] contains
* "Konrad Adenauer Allee".
*/
$m_suggestions = $o_soundex->fetchSuggestions(
    $_POST["streetname"], // Enter word to search for here
    'street', // Enter table FIELD (!) name here
    'de_streets', // Enter Table NAME (!) right here
    'array' ); // Use "array" or "json" here, depending on how you want to have your results
```

The method called fetchSuggestions will either return an array or
a json string, depending on what you have chose as the last
argument.

If there was an entry like "Konrad-Adenauer-Allee" in the
table you have mentioned as the 3rd parameter: YES, it will be in the result.

Did you know that "Worwick Avenue" sounds the same like
"Warwick Avenue"? Just saying...