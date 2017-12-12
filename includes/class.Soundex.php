<?php
/**
 * @author: Ercan K.
 */

include 'includes/function.colgone_phon.php';

class Soundex {

    private $mysqli;
    private $language;

    /**
     * 
     * @param string $language Language code, either "de" or "en"
     * @param mixed $mysqli The mysqli-object
     * @throws Exception
     */
    public function __construct($language, $mysqli) {

        if ($this->checkLanguage($language)) {
            $this->language = $language;
        } else {
            throw new Exception('Missing/wrong language code. Try "de" for German or "en" for English.');
        }

        // Is it really an object of mysqli?
        if (iobject($mysqli) && $mysqli instanceof mysqli) {
            $this->mysqli = $mysqli;
        } else {
            throw new Exception('Missing/wrong parameter for mysqli object.');
        }
        
    }
    
    /**
     * 
     * @param string $language Language code to check
     * @return boolean True if the language code is supported
     */
    private function checkLanguage($language) {

        if ('de' === $language || 'en' === $language) {

            return true;
        }

        return false;
    }

    /**
     * 
     * @param string $word The sound alike word to search for
     * @param string $field Name of the table field
     * @param string $table Name of the table itself
     * @param string $return_type Either "array" or "json" as the return type
     * @return mixed Either an array or a json string
     * @throws Exception
     */
    public function fetchSuggestions($word, $field, $table, $return_type = 'array') {

        $a_results = array();

        $sql = 'SELECT ' . $this->mysqli->escape_string($field) .
                ' FROM ' . $this->mysqli->escape_string($table);

        $query = $this->mysqli->query($sql);

        if (!$query = $this->mysqli->query($sql)) {
            throw new Exception('SQL statement could not be executed. ' . $this->mysqli->error);
        }

        // todo: Should fetch the results into an array so the $query can be closed before a while-loop can check its contents
        while ($row = $query->fetch_row()) {
            if ('de' === $this->language) {
                // German
                if (@cologne_phon($row[0]) === @cologne_phon($this->mysqli->escape_string($word))) {
                    array_push($a_results, $row[0]);
                }
            } else {
                // English
                if (soundex($row[0]) === soundex($this->mysqli->escape_string($word))) {
                    array_push($a_results, $row[0]);
                }
            }
        }

        $query->close();

        if ('array' === $return_type) {
            return $a_results;
        } elseif ('json' === $return_type) {
            return json_encode($a_results);
        }
    }
    
}