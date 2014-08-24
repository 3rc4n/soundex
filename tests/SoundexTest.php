<?php
/**
 * @author: Ercan K.
 */

include 'includes/class.Soundex.php';

class SoundexTest extends PHPUnit_Framework_TestCase
{
    public function testWrongLanguageCode() {
        try {
            $o_soundex = new Soundex( 'xx', '' );
        } catch (Exception $e) {
            $this->assertEquals( 'Missing/wrong language code. Try "de" for German or "en" for English.', $e->getMessage() );
        }
    }
    
    public function testMissingMysqli() {
        
        try {
            $o_soundex = new Soundex( 'de', '' );
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'Missing parameter for mysqli object. You can also use the database server name instead.', $e->getMessage() );
            }
        }
        
    }
    
    public function testParameters() {
        try {
            $o_soundex = new Soundex( 'de', 'localhost' );
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'Missing/wrong database login information.', $e->getMessage() );
            }
        }
        
        try {
            $o_soundex = new Soundex( 'de', 'localhost', 'a', 'b', 'c' );
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'Missing/wrong database login information.', $e->getMessage() );
            }
        }
    }
    
    public function testGermanWordsearchMysqli() {
        
        $o_db = new mysqli( 'localhost', 'root', 'root', 'soundex' );
        
        $o_soundex = new Soundex( 'de', $o_db );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', 'de_streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', 'de_streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    
    public function testGermanWordsearchParams() {
        
        $o_soundex = new Soundex( 'de', 'localhost', 'root', 'root', 'soundex' );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', 'de_streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', 'de_streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    public function testGermanWordsearchFindNothing() {
        
        $o_soundex = new Soundex( 'de', 'localhost', 'root', 'root', 'soundex' );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'asdfasdf', 'street', 'de_streets', 'array' );
        
        $this->assertEquals( 0, sizeof($a_suggestions) );
        
    }
    
    public function testGermanWordsearchWrongSuggestionsParams() {
        
        $o_db = new mysqli( 'localhost', 'root', 'root', 'soundex' );
        
        $o_soundex = new Soundex( 'de', $o_db );
            
        $s_field = 'notexistingtablefield';
        $s_table = 'notexistingtable';
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', $s_field, 'de_streets', 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Unknown column \'' . $s_field . '\' in \'field list\'', $e->getMessage() );
            }
        }
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', $s_table, 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Table \'soundex.' . $s_table . '\' doesn\'t exist', $e->getMessage() );
            }
        }
        
    }
        
    public function testEnglishWordsearchMysqli() {
        
        $o_db = new mysqli( 'localhost', 'root', 'root', 'soundex' );
        
        $o_soundex = new Soundex( 'en', $o_db );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'Warvock Avenue', 'street', 'en_streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( 'Warvock Avenue', 'street', 'en_streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    
    public function testEnglishWordsearchParams() {
        
        $o_soundex = new Soundex( 'en', 'localhost', 'root', 'root', 'soundex' );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'Warvock Avenue', 'street', 'en_streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( 'Warvock Avenue', 'street', 'en_streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    public function testEnglishWordsearchFindNothing() {
        
        $o_soundex = new Soundex( 'en', 'localhost', 'root', 'root', 'soundex' );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'asdfasdf', 'street', 'en_streets', 'array' );
        
        $this->assertEquals( 0, sizeof($a_suggestions) );
        
    }
    
    public function testEnglishWordsearchWrongSuggestionsParams() {
        
        $o_db = new mysqli( 'localhost', 'root', 'root', 'soundex' );
        
        $o_soundex = new Soundex( 'en', $o_db );
            
        $s_field = 'notexistingtablefield';
        $s_table = 'notexistingtable';
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( 'Warvock Avenue', $s_field, 'en_streets', 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Unknown column \'' . $s_field . '\' in \'field list\'', $e->getMessage() );
            }
        }
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( 'Konrad Adenauer Allee', 'street', $s_table, 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Table \'soundex.' . $s_table . '\' doesn\'t exist', $e->getMessage() );
            }
        }
        
    }
    
}