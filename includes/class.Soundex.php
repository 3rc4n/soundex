<?php
/**
 * @author: Ercan K.
 */

include 'includes/function.colgone_phon.php';

class Soundex {

    private $o_mysqli;
    private $s_language;

    /**
     * 
     * @param string $s_language Language code, either "de" or "en"
     * @param mixed $o_mysqli The mysqli-object
     * @throws Exception
     */
    public function __construct($s_language, $o_mysqli) {

        if ($this->checkLanguage($s_language)) {
            $this->s_language = $s_language;
        } else {
            throw new Exception('Missing/wrong language code. Try "de" for German or "en" for English.');
        }

        // Is it really an object of mysqli?
        if (is_object($o_mysqli) && $o_mysqli instanceof mysqli) {
            $this->o_mysqli = $o_mysqli;
        } else {
            throw new Exception('Missing/wrong parameter for mysqli object.');
        }

    }
    
    /**
     * 
     * @param string $s_language Language code to check
     * @return boolean True if the language code is supported
     */
    private function checkLanguage($s_language) {

        if ('de' === $s_language || 'en' === $s_language) {

            return true;
        }

        return false;
    }

    /**
     * 
     * @param string $s_word The sound alike word to search for
     * @param string $s_field Name of the table field
     * @param string $s_table Name of the table itself
     * @param string $s_return_type Either "array" or "json" as the return type
     * @return mixed Either an array or a json string
     * @throws Exception
     */
    public function fetchSuggestions($s_word, $s_field, $s_table, $s_return_type = 'array') {

        $a_results = array();

        $s_sql = 'SELECT ' . $this->o_mysqli->escape_string($s_field) .
                ' FROM ' . $this->o_mysqli->escape_string($s_table);

        $query = $this->o_mysqli->query($s_sql);

        if (!$query = $this->o_mysqli->query($s_sql)) {
            throw new Exception('SQL statement could not be executed. ' . $this->o_mysqli->error);
        }

        // todo: Should fetch the results into an array so the $query can be closed before a while-loop can check its contents
        while ($row = $query->fetch_row()) {
            if ('de' === $this->s_language) {
                // German
                if (@cologne_phon($row[0]) === @cologne_phon($this->o_mysqli->escape_string($s_word))) {
                    array_push($a_results, $row[0]);
                }
            } else {
                // English
                if (soundex($row[0]) === soundex($this->o_mysqli->escape_string($s_word))) {
                    array_push($a_results, $row[0]);
                }
            }
        }

        $query->close();

        if ('array' === $s_return_type) {
            return $a_results;
        } elseif ('json' === $s_return_type) {
            return json_encode($a_results);
        }
    }
    
}