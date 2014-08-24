<?php
/**
 * @author: Ercan K.
 */
?>

<html>
    <head>
        <title>Soundex Class HowTo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/style.css"></style>
    </head>
    <body>
        <h1>Soundex Class HowTo</h1>
        <article>
            <p>
                Imagine you have a database called "soundex" and there is a table
                called "streets" that contains street names.
            </p>

            <p>
                Now imagine you have a website that supports English and German.
                A website visitors enters a street name during a registration process.
                Of course you could use PHP's soundex function. But this functionality
                considers only supports the English phonetik scheme. The German streets
                "Kirchstr." can be easily confused with "Kreuzstr." if PHP's soundex
                is in use.
            </p>

            <p>Thanks to the Cologne phonetik, I managed to combine both languages
                within a Soundex class! You just need to enter what field in
                what table to search the suggestions from.
            </p>
            <p>
                First, you will have to include the class file. Then you
                can either create a Mysqli-object and use it as the 2nd argument
                when you create the object, or add 4 Parameters instead containing
                the db-server name, db-user name, db-password and the database title.
            </p>
            <p>
                If you plan to use this class for German words, please use
                'de' as the first argument. Otherwise, if the words you want to
                have suggested are in English, please use 'en'.
            </p>
                <?php
                highlight_string('
            <?php

                include(\'includes/class.Soundex.php\');
                
                /**
                * Instead of \'de\', you can use \'en\' for English support.
                */
                $o_soundex = new Soundex( \'de\',  \'db-server\', \'db-user\', \'db-password\', \'db-name\' );
                
                /**
                * Or, if you already created an object of mysqli somewhere in
                * your code, you use it as a 2nd argument:
                *
                * $mysqli_object = new mysqli( \'db-server\', \'db-user\', \'db-password\', \'db-name\' );
                *
                * $o_soundex = new Soundex( \'de\', $mysqli_object );
                */


            ?>');
                
                  
                ?>
            <p>
                After the object (in our example, $o_soundex) is created,
                let us imagine following situation: Website visitor types
                the German street called "Konrad Adenauer Allee" into a text
                field and submits the form. Yes, there is a street called
                like this, but I can tell you that there are no empty
                spaces between the words.
            </p>

            <p>So we use following example code:</p>

                <?php
                highlight_string('
                <?php
                    /**
                    * Let us imagine $_POST["streetname"] contains
                    * "Konrad Adenauer Allee".
                    */
                    $m_suggestions = $o_soundex->fetchSuggestions(
                            $_POST["streetname"], // Enter word to search for here
                            \'street\', // Enter table FIELD (!) name here
                            \'de_streets\', // Enter Table NAME (!) right here
                            \'array\' ); // Use "array" or "json" here
                ?>');
                ?>

            <p>Once the function is done, it will either return an array or
                a json string, depending on what you have chose as the last
                argument.</p>

            <p>
                If you have "Konrad-Adenauer-Allee" written  in the mentioned
                table: YES, it will be in the array, repectively in the
                json string.
            </p>

            <p>
                Did you know that "Worwick Avenue" sounds the same like
                "Warwick Avenue"? Just saying...
            </p>

            <p>
                Of course this class is experimental. It is distributed in
                the hope that it will be useful, but without any warranty.
            </p>

        </article>
    </body>
</html>