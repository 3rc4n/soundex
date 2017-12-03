<?php
/**
 * @author: Ercan K.
 */

include 'includes/class.Soundex.php';

class SoundexTest extends PHPUnit_Framework_TestCase
{
    
    public $s_german_street = 'Konrad Adenauer Allee';
    
    public $s_english_street = 'Warvock Avenue';


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
                $this->assertEquals( 'Missing/wrong parameter for mysqli object.', $e->getMessage() );
            }
        }
        
    }

    public function testGermanWordsearchMysqli() {

        $o_soundex = new Soundex( 'de', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, 'name', 'streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, 'name', 'streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    
    public function testGermanWordsearchParams() {

        $o_soundex = new Soundex( 'de', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, 'name', 'streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, 'name', 'streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    public function testGermanWordsearchFindNothing() {

        $o_db = new mysqli( 'localhost', 'root', 'root', 'soundex' );

        $o_soundex = new Soundex( 'de', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'asdfasdf', 'name', 'streets', 'array' );
        
        $this->assertEquals( 0, sizeof($a_suggestions) );
        
    }
    
    public function testGermanWordsearchWrongSuggestionsParams() {

        $o_soundex = new Soundex( 'de', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
            
        $s_field = 'notexistingtablefield';
        $s_table = 'notexistingtable';
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, $s_field, 'streets', 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Unknown column \'' . $s_field . '\' in \'field list\'', $e->getMessage() );
            }
        }
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( $this->s_german_street, 'street', $s_table, 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Table \'soundex.' . $s_table . '\' doesn\'t exist', $e->getMessage() );
            }
        }
        
    }
        
    public function testEnglishWordsearchMysqli() {

        $o_soundex = new Soundex( 'en', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, 'name', 'streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, 'name', 'streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    
    public function testEnglishWordsearchParams() {
        
        $o_soundex = new Soundex( 'en', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, 'name', 'streets', 'array' );
        
        $this->assertEquals( true, is_array($a_suggestions) );
        
        $s_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, 'name', 'streets', 'json' );
        
        $this->assertEquals( true, is_array( json_decode($s_suggestions, true) ) );
        
    }
    
    public function testEnglishWordsearchFindNothing() {
        
        $o_soundex = new Soundex( 'en', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
        
        $a_suggestions = $o_soundex->fetchSuggestions( 'asdfasdf', 'name', 'streets', 'array' );
        
        $this->assertEquals( 0, sizeof($a_suggestions) );
        
    }
    
    public function testEnglishWordsearchWrongSuggestionsParams() {

        $o_soundex = new Soundex( 'en', new mysqli( 'localhost', 'root', 'root', 'soundex' ) );
            
        $s_field = 'notexistingtablefield';
        $s_table = 'notexistingtable';
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, $s_field, 'streets', 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Unknown column \'' . $s_field . '\' in \'field list\'', $e->getMessage() );
            }
        }
        
        try {
            
            $a_suggestions = $o_soundex->fetchSuggestions( $this->s_english_street, 'street', $s_table, 'array' );
            
        } catch (Exception $e) {
            if ('Exception' === get_class($e)) {
                $this->assertEquals( 'SQL statement could not be executed. Table \'soundex.' . $s_table . '\' doesn\'t exist', $e->getMessage() );
            }
        }
        
    }
    
}