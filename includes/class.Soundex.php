<?php
/**
 * @author: Ercan K.
 */

include 'includes/function.colgone_phon.php';

class Soundex {

    private $o_db;
    private $s_language;

    /**
     * 
     * @param string $s_language
     * @param mixed $m_param1
     * @param string $s_param2
     * @param string $s_param3
     * @param string $s_param4
     */
    public function __construct($s_language, $m_param1, $s_param2 = null, $s_param3 = null, $s_param4 = null) {

        if ($this->checkLanguage($s_language)) {
            $this->s_language = $s_language;
        } else {
            throw new Exception('Missing/wrong language code. Try "de" for German or "en" for English.');
        }

        # Object of mysqli?
        if (is_object($m_param1) && $m_param1 instanceof mysqli) {

            $this->o_db = $m_param1;
        } else {

            if (empty($m_param1)) {
                throw new Exception('Missing parameter for mysqli object. You can also use the database server name instead.');
            } else {

                if (empty($s_param2) || empty($s_param3) || empty($s_param4)) {
                    throw new Exception('Missing/wrong database login information.');
                }

                if (!$this->o_db = new mysqli($m_param1, $s_param2, $s_param3, $s_param4)) {
                    throw new Exception('Could not connect to database server.');
                }
            }
        }
    }
    
    /**
     * 
     * @param string $s_language
     * @return boolean
     */
    private function checkLanguage($s_language) {

        if ('de' === $s_language || 'en' === $s_language) {

            return true;
        }

        return false;
    }

    /**
     * 
     * @param string $s_word
     * @param string $s_field
     * @param string $s_table
     * @param string $s_return_type
     * @return mixed
     * @throws Exception
     */
    public function fetchSuggestions($s_word, $s_field, $s_table, $s_return_type = 'array') {

        $a_results = array();

        $s_sql = 'SELECT ' . $this->o_db->escape_string($s_field) .
                ' FROM ' . $this->o_db->escape_string($s_table);

        $query = $this->o_db->query($s_sql);

        if (!$query = $this->o_db->query($s_sql)) {
            throw new Exception('SQL statement could not be executed. ' . $this->o_db->error);
        }

        while ($row = $query->fetch_row()) {
            if ('de' === $this->s_language) {
                // German
                if (@cologne_phon($row[0]) === @cologne_phon($this->o_db->escape_string($s_word))) {
                    array_push($a_results, $row[0]);
                }
            } else {
                // English
                if (soundex($row[0]) === soundex($this->o_db->escape_string($s_word))) {
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
