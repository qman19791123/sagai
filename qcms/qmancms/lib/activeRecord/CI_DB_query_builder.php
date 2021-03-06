<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the IT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package CodeIgniter
 * @author  EllisLab Dev Team
 * @copyright   Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright   Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    https://codeigniter.com
 * @since   Version 1.0.0
 * @filesource
 */

/**
 * Query Builder Class
 *
 * This is the platform-independent base Query Builder implementation class.
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/user_guide/database/
 */
class CI_DB_query_builder extends CI_DB_driver {

    /**
     * Return DELETE SQL flag
     *
     * @var bool
     */
    protected $return_delete_sql = FALSE;

    /**
     * Reset DELETE data flag
     *
     * @var bool
     */
    protected $reset_delete_data = FALSE;

    /**
     * QB SELECT data
     *
     * @var array
     */
    protected $qb_select = array();

    /**
     * QB DISTINCT flag
     *
     * @var bool
     */
    protected $qb_distinct = FALSE;

    /**
     * QB FROM data
     *
     * @var array
     */
    protected $qb_from = array();

    /**
     * QB JOIN data
     *
     * @var array
     */
    protected $qb_join = array();

    /**
     * QB WHERE data
     *
     * @var array
     */
    protected $qb_where = array();

    /**
     * QB GROUP BY data
     *
     * @var array
     */
    protected $qb_groupby = array();

    /**
     * QB HAVING data
     *
     * @var array
     */
    protected $qb_having = array();

    /**
     * QB keys
     *
     * @var array
     */
    protected $qb_keys = array();

    /**
     * QB LIMIT data
     *
     * @var int
     */
    protected $qb_limit = FALSE;

    /**
     * QB OFFSET data
     *
     * @var int
     */
    protected $qb_offset = FALSE;

    /**
     * QB ORDER BY data
     *
     * @var array
     */
    protected $qb_orderby = array();

    /**
     * QB data sets
     *
     * @var array
     */
    protected $qb_set = array();

    /**
     * QB aliased tables list
     *
     * @var array
     */
    protected $qb_aliased_tables = array();

    /**
     * QB WHERE group started flag
     *
     * @var bool
     */
    protected $qb_where_group_started = FALSE;

    /**
     * QB WHERE group count
     *
     * @var int
     */
    protected $qb_where_group_count = 0;

    // Query Builder Caching variables

    /**
     * QB Caching flag
     *
     * @var bool
     */
    protected $qb_caching = FALSE;

    /**
     * QB Cache exists list
     *
     * @var array
     */
    protected $qb_cache_exists = array();

    /**
     * QB Cache SELECT data
     *
     * @var array
     */
    protected $qb_cache_select = array();

    /**
     * QB Cache FROM data
     *
     * @var array
     */
    protected $qb_cache_from = array();

    /**
     * QB Cache JOIN data
     *
     * @var array
     */
    protected $qb_cache_join = array();

    /**
     * QB Cache WHERE data
     *
     * @var array
     */
    protected $qb_cache_where = array();

    /**
     * QB Cache GROUP BY data
     *
     * @var array
     */
    protected $qb_cache_groupby = array();

    /**
     * QB Cache HAVING data
     *
     * @var array
     */
    protected $qb_cache_having = array();

    /**
     * QB Cache ORDER BY data
     *
     * @var array
     */
    protected $qb_cache_orderby = array();

    /**
     * QB Cache data sets
     *
     * @var array
     */
    protected $qb_cache_set = array();

    /**
     * QB No Escape data
     *
     * @var array
     */
    protected $qb_no_escape = array();

    /**
     * QB Cache No Escape data
     *
     * @var array
     */
    protected $qb_cache_no_escape = array();

    // --------------------------------------------------------------------

    /**
     * Select
     *
     * Generates the SELECT portion of the query
     *
     * @param   string
     * @param   mixed
     * @return  CI_DB_query_builder
     */
    public function select($select = '*', $escape = NULL) {
        if (is_string($select)) {
            $select = explode(',', $select);
        }

        foreach ($select as $val) {
            $val = trim($val);

            if ($val !== '') {
                $this->qb_select[] = $val;
                $this->qb_no_escape[] = $escape;
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Select Max
     *
     * Generates a SELECT MAX(field) portion of a query
     *
     * @param   string  the field
     * @param   string  an alias
     * @return  CI_DB_query_builder
     */
    public function select_max($select = '', $alias = '') {
        return $this->_max_min_avg_sum($select, $alias, 'MAX');
    }

    // --------------------------------------------------------------------

    /**
     * Select Min
     *
     * Generates a SELECT MIN(field) portion of a query
     *
     * @param   string  the field
     * @param   string  an alias
     * @return  CI_DB_query_builder
     */
    public function select_min($select = '', $alias = '') {
        return $this->_max_min_avg_sum($select, $alias, 'MIN');
    }

    // --------------------------------------------------------------------

    /**
     * Select Average
     *
     * Generates a SELECT AVG(field) portion of a query
     *
     * @param   string  the field
     * @param   string  an alias
     * @return  CI_DB_query_builder
     */
    public function select_avg($select = '', $alias = '') {
        return $this->_max_min_avg_sum($select, $alias, 'AVG');
    }

    // --------------------------------------------------------------------

    /**
     * Select Sum
     *
     * Generates a SELECT SUM(field) portion of a query
     *
     * @param   string  the field
     * @param   string  an alias
     * @return  CI_DB_query_builder
     */
    public function select_sum($select = '', $alias = '') {
        return $this->_max_min_avg_sum($select, $alias, 'SUM');
    }

    // --------------------------------------------------------------------

    /**
     * SELECT [MAX|MIN|AVG|SUM]()
     *
     * @used-by select_max()
     * @used-by select_min()
     * @used-by select_avg()
     * @used-by select_sum()
     *
     * @param   string  $select Field name
     * @param   string  $alias
     * @param   string  $type
     * @return  CI_DB_query_builder
     */
    protected function _max_min_avg_sum($select = '', $alias = '', $type = 'MAX') {
        if (!is_string($select) OR $select === '') {
            return;
        }

        $typeUppercase = strtoupper($type);

        if (!in_array($typeUppercase, array('MAX', 'MIN', 'AVG', 'SUM'))) {
            return;
        }

        if ($alias === '') {
            $alias = $this->_create_alias_from_table(trim($select));
        }

        $sql = $typeUppercase . '(' . $this->protect_identifiers(trim($select)) . ') AS ' . $this->escape_identifiers(trim($alias));

        $this->qb_select[] = $sql;
        $this->qb_no_escape[] = NULL;


        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Determines the alias name based on the table
     *
     * @param   string  $item
     * @return  string
     */
    protected function _create_alias_from_table($item) {
        if (strpos($item, '.') !== FALSE) {
            $item = explode('.', $item);
            return end($item);
        }

        return $item;
    }

    /**
     * From
     *
     * Generates the FROM portion of the query
     *
     * @param   mixed   $from   can be a string or array
     * @return  CI_DB_query_builder
     */
    public function from($from) {
        foreach ((array) $from as $val) {
            if (strpos($val, ',') !== FALSE) {
                $valSlitting = explode(',', $val);
                $this->_form($valSlitting);
            } else {
                $val = trim($val);
                $this->_track_aliases($val);
                $this->qb_from[] = $val = $this->protect_identifiers($val, TRUE, NULL, FALSE);
            }
        }
        return $this;
    }

    /**
     * 循环获取多表 
     * @param type $valSlitting
     */
    private function _form($valSlitting) {
        foreach ($valSlitting as $v) {
            $v = trim($v);
            $this->_track_aliases($v);

            $this->qb_from[] = $v = $this->protect_identifiers($v, TRUE, NULL, FALSE);
        }
    }

    // --------------------------------------------------------------------

    /**
     * JOIN
     *
     * Generates the JOIN portion of the query
     *
     * @param   string
     * @param   string  the join condition
     * @param   string  the type of join
     * @param   string  whether not to try to escape identifiers
     * @return  CI_DB_query_builder
     */
    public function join($table, $cond, $type = '', $escape = NULL) {
        if ($type !== '') {
            $type = strtoupper(trim($type));

            if (!in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'), TRUE)) {
                $type = '';
            } else {
                $type .= ' ';
            }
        }

        $this->_track_aliases($table);

        if (!$this->_has_operator($cond)) {
            $cond = ' USING (' . ($escape ? $this->escape_identifiers($cond) : $cond) . ')';
        } elseif ($escape === FALSE) {
            $cond = ' ON ' . $cond;
        } else {
            // Split multiple conditions
            if (preg_match_all('/\sAND\s|\sOR\s/i', $cond, $joints, PREG_OFFSET_CAPTURE)) {
                $conditions = array();
                $joints = $joints[0];
                array_unshift($joints, array('', 0));

                for ($i = count($joints) - 1, $pos = strlen($cond); $i >= 0; $i--) {
                    $joints[$i][1] += strlen($joints[$i][0]); // offset
                    $conditions[$i] = substr($cond, $joints[$i][1], $pos - $joints[$i][1]);
                    $pos = $joints[$i][1] - strlen($joints[$i][0]);
                    $joints[$i] = $joints[$i][0];
                }
            } else {
                $conditions = array($cond);
                $joints = array('');
            }

            $cond = ' ON ';
            for ($i = 0, $c = count($conditions); $i < $c; $i++) {
                $operator = $this->_get_operator($conditions[$i]);
                $cond .= $joints[$i];
                $cond .= preg_match("/(\(*)?([\[\]\w\.'-]+)" . preg_quote($operator) . "(.*)/i", $conditions[$i], $match) ? $match[1] . $this->protect_identifiers($match[2]) . $operator . $this->protect_identifiers($match[3]) : $conditions[$i];
            }
        }

        // Do we want to escape the table name?
        if ($escape === TRUE) {
            $table = $this->protect_identifiers($table, TRUE, NULL, FALSE);
        }
        // Assemble the JOIN statement
        $this->qb_join[] = $join = $type . 'JOIN ' . $table . $cond;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * WHERE
     *
     * Generates the WHERE portion of the query.
     * Separates multiple calls with 'AND'.
     *
     * @param   mixed
     * @param   mixed
     * @param   bool
     * @return  CI_DB_query_builder
     */
    public function where($key, $value = NULL, $escape = NULL) {
        return $this->_wh('qb_where', $key, $value, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR WHERE
     *
     * Generates the WHERE portion of the query.
     * Separates multiple calls with 'OR'.
     *
     * @param   mixed
     * @param   mixed
     * @param   bool
     * @return  CI_DB_query_builder
     */
    public function or_where($key, $value = NULL, $escape = NULL) {
        return $this->_wh('qb_where', $key, $value, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * WHERE, HAVING
     *
     * @used-by where()
     * @used-by or_where()
     * @used-by having()
     * @used-by or_having()
     *
     * @param   string  $qb_key 'qb_where' or 'qb_having'
     * @param   mixed   $key
     * @param   mixed   $value
     * @param   string  $type
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    protected function _wh($qb_key, $key, $value = NULL, $type = 'AND ', $escape = NULL) {
        $qb_cache_key = ($qb_key === 'qb_having') ? 'qb_cache_having' : 'qb_cache_where';

        if (!is_array($key)) {
            $key = array($key => $value);
        }

        // If the escape value was not set will base it on the global setting

        foreach ($key as $k => $v) {
            $prefix = (count($this->$qb_key) === 0 && count($this->$qb_cache_key) === 0) ? $this->_group_get_type('') : $this->_group_get_type($type);

            if ($v !== NULL) {
                if ($escape === TRUE) {
                    $v = ' ' . $this->escape($v);
                }

                if (!$this->_has_operator($k)) {
                    $k .= ' = ';
                }
            } elseif (!$this->_has_operator($k)) {
                // value appears not to have been set, assign the test to IS NULL
                $k .= ' IS NULL';
            } elseif (preg_match('/\s*(!?=|<>|IS(?:\s+NOT)?)\s*$/i', $k, $match, PREG_OFFSET_CAPTURE)) {
                $k = substr($k, 0, $match[0][1]) . ($match[1][0] === '=' ? ' IS NULL' : ' IS NOT NULL');
            }

            $this->{$qb_key}[] = array('condition' => $prefix . $k . $v, 'escape' => $escape);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * WHERE IN
     *
     * Generates a WHERE field IN('item', 'item') SQL query,
     * joined with 'AND' if appropriate.
     *
     * @param   string  $key    The field to search
     * @param   array   $values The values searched on
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function where_in($key = NULL, $values = NULL, $escape = NULL) {
        return $this->_where_in($key, $values, FALSE, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR WHERE IN
     *
     * Generates a WHERE field IN('item', 'item') SQL query,
     * joined with 'OR' if appropriate.
     *
     * @param   string  $key    The field to search
     * @param   array   $values The values searched on
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function or_where_in($key = NULL, $values = NULL, $escape = NULL) {
        return $this->_where_in($key, $values, FALSE, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * WHERE NOT IN
     *
     * Generates a WHERE field NOT IN('item', 'item') SQL query,
     * joined with 'AND' if appropriate.
     *
     * @param   string  $key    The field to search
     * @param   array   $values The values searched on
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function where_not_in($key = NULL, $values = NULL, $escape = NULL) {
        return $this->_where_in($key, $values, TRUE, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR WHERE NOT IN
     *
     * Generates a WHERE field NOT IN('item', 'item') SQL query,
     * joined with 'OR' if appropriate.
     *
     * @param   string  $key    The field to search
     * @param   array   $values The values searched on
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function or_where_not_in($key = NULL, $values = NULL, $escape = NULL) {
        return $this->_where_in($key, $values, TRUE, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * Internal WHERE IN
     *
     * @used-by where_in()
     * @used-by or_where_in()
     * @used-by where_not_in()
     * @used-by or_where_not_in()
     *
     * @param   string  $key    The field to search
     * @param   array   $values The values searched on
     * @param   bool    $not    If the statement would be IN or NOT IN
     * @param   string  $type
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    protected function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ', $escape = NULL) {
        if ($key === NULL OR $values === NULL) {
            return $this;
        }

        if (!is_array($values)) {
            $values = array($values);
        }


        $not = ($not) ? ' NOT' : '';

        if ($escape === TRUE) {
            $where_in = array();
            foreach ($values as $value) {
                $where_in[] = $this->escape($value);
            }
        } else {
            $where_in = array_values($values);
        }

        $prefix = (count($this->qb_where) === 0 && count($this->qb_cache_where) === 0) ? $this->_group_get_type('') : $this->_group_get_type($type);

        $where_in = array(
            'condition' => $prefix . $key . $not . ' IN(' . implode(', ', $where_in) . ')',
            'escape' => $escape
        );

        $this->qb_where[] = $where_in;


        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * LIKE
     *
     * Generates a %LIKE% portion of the query.
     * Separates multiple calls with 'AND'.
     *
     * @param   mixed   $field
     * @param   string  $match
     * @param   string  $side
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function like($field, $match = '', $side = 'both', $escape = NULL) {
        return $this->_like($field, $match, 'AND ', $side, '', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * NOT LIKE
     *
     * Generates a NOT LIKE portion of the query.
     * Separates multiple calls with 'AND'.
     *
     * @param   mixed   $field
     * @param   string  $match
     * @param   string  $side
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function not_like($field, $match = '', $side = 'both', $escape = NULL) {
        return $this->_like($field, $match, 'AND ', $side, 'NOT', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR LIKE
     *
     * Generates a %LIKE% portion of the query.
     * Separates multiple calls with 'OR'.
     *
     * @param   mixed   $field
     * @param   string  $match
     * @param   string  $side
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function or_like($field, $match = '', $side = 'both', $escape = NULL) {
        return $this->_like($field, $match, 'OR ', $side, '', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR NOT LIKE
     *
     * Generates a NOT LIKE portion of the query.
     * Separates multiple calls with 'OR'.
     *
     * @param   mixed   $field
     * @param   string  $match
     * @param   string  $side
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function or_not_like($field, $match = '', $side = 'both', $escape = NULL) {
        return $this->_like($field, $match, 'OR ', $side, 'NOT', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * Internal LIKE
     *
     * @used-by like()
     * @used-by or_like()
     * @used-by not_like()
     * @used-by or_not_like()
     *
     * @param   mixed   $field
     * @param   string  $match
     * @param   string  $type
     * @param   string  $side
     * @param   string  $not
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    protected function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '', $escape = NULL) {
        if (!is_array($field)) {
            $field = array($field => $match);
        }

        // lowercase $side in case somebody writes e.g. 'BEFORE' instead of 'before' (doh)
        $side = strtolower($side);

        foreach ($field as $k => $v) {
            $prefix = (count($this->qb_where) === 0 && count($this->qb_cache_where) === 0) ? $this->_group_get_type('') : $this->_group_get_type($type);

            if ($escape === TRUE) {
                $v = $this->escape_like_str($v);
            }

            if ($side === 'none') {
                $like_statement = "{$prefix} {$k} {$not} LIKE '{$v}'";
            } elseif ($side === 'before') {
                $like_statement = "{$prefix} {$k} {$not} LIKE '%{$v}'";
            } elseif ($side === 'after') {
                $like_statement = "{$prefix} {$k} {$not} LIKE '{$v}%'";
            } else {
                $like_statement = "{$prefix} {$k} {$not} LIKE '%{$v}%'";
            }

            // some platforms require an escape sequence definition for LIKE wildcards
            if ($escape === TRUE && $this->_like_escape_str !== '') {
                $like_statement .= sprintf($this->_like_escape_str, $this->_like_escape_chr);
            }

            $this->qb_where[] = array('condition' => $like_statement, 'escape' => $escape);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Starts a query group.
     *
     * @param   string  $not    (Internal use only)
     * @param   string  $type   (Internal use only)
     * @return  CI_DB_query_builder
     */
    public function group_start($not = '', $type = 'AND ') {
        $type = $this->_group_get_type($type);

        $this->qb_where_group_started = TRUE;
        $prefix = (count($this->qb_where) === 0 && count($this->qb_cache_where) === 0) ? '' : $type;
        $where = array(
            'condition' => $prefix . $not . str_repeat(' ', ++$this->qb_where_group_count) . ' (',
            'escape' => FALSE
        );

        $this->qb_where[] = $where;
        if ($this->qb_caching) {
            $this->qb_cache_where[] = $where;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Starts a query group, but ORs the group
     *
     * @return  CI_DB_query_builder
     */
    public function or_group_start() {
        return $this->group_start('', 'OR ');
    }

    // --------------------------------------------------------------------

    /**
     * Starts a query group, but NOTs the group
     *
     * @return  CI_DB_query_builder
     */
    public function not_group_start() {
        return $this->group_start('NOT ', 'AND ');
    }

    // --------------------------------------------------------------------

    /**
     * Starts a query group, but OR NOTs the group
     *
     * @return  CI_DB_query_builder
     */
    public function or_not_group_start() {
        return $this->group_start('NOT ', 'OR ');
    }

    // --------------------------------------------------------------------

    /**
     * Ends a query group
     *
     * @return  CI_DB_query_builder
     */
    public function group_end() {
        $this->qb_where_group_started = FALSE;
        $where = array(
            'condition' => str_repeat(' ', $this->qb_where_group_count--) . ')',
            'escape' => FALSE
        );

        $this->qb_where[] = $where;
        if ($this->qb_caching) {
            $this->qb_cache_where[] = $where;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Group_get_type
     *
     * @used-by group_start()
     * @used-by _like()
     * @used-by _wh()
     * @used-by _where_in()
     *
     * @param   string  $type
     * @return  string
     */
    protected function _group_get_type($type) {
        if ($this->qb_where_group_started) {
            $type = '';
            $this->qb_where_group_started = FALSE;
        }

        return $type;
    }

    // --------------------------------------------------------------------

    /**
     * GROUP BY
     *
     * @param   string  $by
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function group_by($by, $escape = NULL) {

        if (is_string($by)) {
            $by = ($escape === TRUE) ? explode(',', $by) : array($by);
        }

        foreach ($by as $val) {
            $val = trim($val);

            if ($val !== '') {
                $val = array('field' => $val, 'escape' => $escape);

                $this->qb_groupby[] = $val;
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * HAVING
     *
     * Separates multiple calls with 'AND'.
     *
     * @param   string  $key
     * @param   string  $value
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function having($key, $value = NULL, $escape = NULL) {
        return $this->_wh('qb_having', $key, $value, 'AND ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * OR HAVING
     *
     * Separates multiple calls with 'OR'.
     *
     * @param   string  $key
     * @param   string  $value
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function or_having($key, $value = NULL, $escape = NULL) {
        return $this->_wh('qb_having', $key, $value, 'OR ', $escape);
    }

    // --------------------------------------------------------------------

    /**
     * ORDER BY
     *
     * @param   string  $orderby
     * @param   string  $direction  ASC, DESC or RANDOM
     * @param   bool    $escape
     * @return  CI_DB_query_builder
     */
    public function order_by($orderby, $direction = '', $escape = NULL) {
        $direction = strtoupper(trim($direction));

        if ($direction === 'RANDOM') {
            $direction = '';

            // Do we have a seed value?
            $orderby = ctype_digit((string) $orderby) ? sprintf($this->_random_keyword[1], $orderby) : $this->_random_keyword[0];
        } elseif (empty($orderby)) {
            return $this;
        } elseif ($direction !== '') {
            $direction = in_array($direction, array('ASC', 'DESC'), TRUE) ? ' ' . $direction : '';
        }


        if ($escape === FALSE) {
            $qb_orderby[] = array('field' => $orderby, 'direction' => $direction, 'escape' => FALSE);
        } else {
            $qb_orderby = array();
            foreach (explode(',', $orderby) as $field) {
                $qb_orderby[] = ($direction === '' && preg_match('/\s+(ASC|DESC)$/i', rtrim($field), $match, PREG_OFFSET_CAPTURE)) ? array('field' => ltrim(substr($field, 0, $match[0][1])), 'direction' => ' ' . $match[1][0], 'escape' => TRUE) : array('field' => trim($field), 'direction' => $direction, 'escape' => TRUE);
            }
        }

        $this->qb_orderby = array_merge($this->qb_orderby, $qb_orderby);


        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * LIMIT
     *
     * @param   int $value  LIMIT value
     * @param   int $offset OFFSET value
     * @return  CI_DB_query_builder
     */
    public function limit($value, $offset = 0) {
        is_null($value) OR $this->qb_limit = (int) $value;
        empty($offset) OR $this->qb_offset = (int) $offset;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Sets the OFFSET value
     *
     * @param   int $offset OFFSET value
     * @return  CI_DB_query_builder
     */
    public function offset($offset) {
        empty($offset) OR $this->qb_offset = (int) $offset;
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * LIMIT string
     *
     * Generates a platform-specific LIMIT clause.
     *
     * @param   string  $sql    SQL Query
     * @return  string
     */
    protected function _limit($sql) {
        return $sql . ' LIMIT ' . ($this->qb_offset ? $this->qb_offset . ', ' : '') . $this->qb_limit;
    }

    // --------------------------------------------------------------------

    /**
     * The "set" function.
     *
     * Allows key/value pairs to be set for inserting or updating
     *
     * @param   mixed
     * @param   string
     * @param   bool
     * @return  CI_DB_query_builder
     */
    public function set($key, $value = '', $escape = NULL) {
        $key = $this->_object_to_array($key);

        if (!is_array($key)) {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v) {
            $this->qb_set[$this->protect_identifiers($k, FALSE, $escape)] = ($escape) ? $this->escape($v) : $v;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Get SELECT query string
     *
     * Compiles a SELECT query string and returns the sql.
     *
     * @param   string  the table name to select from (optional)
     * @param   bool    TRUE: resets QB values; FALSE: leave QB values alone
     * @return  string
     */
    public function get_compiled_select($table = '', $reset = TRUE) {
        if ($table !== '') {
            $this->_track_aliases($table);
            $this->from($table);
        }

        $select = $this->_compile_select();

        if ($reset === TRUE) {
            $this->_reset_select();
        }

        return $select;
    }

    // --------------------------------------------------------------------

    /**
     * Get
     *
     * Compiles the select statement based on the other functions called
     * and runs the query
     *
     * @param   string  the table
     * @param   string  the limit clause
     * @param   string  the offset clause
     * @return  CI_DB_result
     */
    public function get($table = '', $limit = NULL, $offset = NULL) {
        if ($table !== '') {
            $this->_track_aliases($table);
            $this->from($table);
        }

        if (!empty($limit)) {
            $this->limit($limit, $offset);
        }
//        echo $this->_compile_select();
        $result = $this->query($this->_compile_select());
        $this->_reset_select();

        $this->deletekey();
        return $result;
    }

    /**
     * Get_Where
     *
     * Allows the where clause, limit and offset to be added directly
     *
     * @param   string  $table
     * @param   string  $where
     * @param   int $limit
     * @param   int $offset
     * @return  CI_DB_result
     */
    public function get_where($table = '', $where = NULL, $limit = NULL, $offset = NULL) {
        if ($table !== '') {
            $this->from($table);
        }

        if ($where !== NULL) {
            $this->where($where);
        }

        if (!empty($limit)) {
            $this->limit($limit, $offset);
        }

        $result = $this->query($this->_compile_select());
        $this->_reset_select();
        return $result;
    }

    /**
     * Insert batch statement
     *
     * Generates a platform-specific insert string from the supplied data.
     *
     * @param   string  $table  Table name
     * @param   array   $keys   INSERT keys
     * @param   array   $values INSERT values
     * @return  string
     */
    protected function _insert_batch($table, $keys, $values) {
        return 'INSERT INTO ' . $table . ' (' . implode(', ', $keys) . ') VALUES ' . implode(', ', $values);
    }

    // --------------------------------------------------------------------

    /**
     * The "set_insert_batch" function.  Allows key/value pairs to be set for batch inserts
     *
     * @param   mixed
     * @param   string
     * @param   bool
     * @return  CI_DB_query_builder
     */
    public function set_insert_batch($key, $value = '', $escape = NULL) {
        $key = $this->_object_to_array_batch($key);

        if (!is_array($key)) {
            $key = array($key => $value);
        }


        $keys = array_keys($this->_object_to_array(current($key)));
        sort($keys);

        foreach ($key as $row) {
            $row = $this->_object_to_array($row);
            if (count(array_diff($keys, array_keys($row))) > 0 OR count(array_diff(array_keys($row), $keys)) > 0) {
                // batch function above returns an error on an empty array
                $this->qb_set[] = array();
                return;
            }

            ksort($row); // puts $row in the same order as our keys

            if ($escape !== FALSE) {
                $clean = array();
                foreach ($row as $value) {
                    $clean[] = $this->escape($value);
                }

                $row = $clean;
            }

            $this->qb_set[] = '(' . implode(',', $row) . ')';
        }

        foreach ($keys as $k) {
            $this->qb_keys[] = $this->protect_identifiers($k, FALSE, $escape);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Get INSERT query string
     *
     * Compiles an insert query and returns the sql
     *
     * @param   string  the table to insert into
     * @param   bool    TRUE: reset QB values; FALSE: leave QB values alone
     * @return  string
     */
    public function get_compiled_insert($table = '', $reset = TRUE) {


        $sql = $this->_insert(
                $this->protect_identifiers(
                        $this->qb_from[0], TRUE, NULL, FALSE
                ), array_keys($this->qb_set), array_values($this->qb_set)
        );

        if ($reset === TRUE) {
            $this->_reset_write();
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Insert
     *
     * Compiles an insert string and runs the query
     *
     * @param   string  the table to insert data into
     * @param   array   an associative array of insert values
     * @param   bool    $escape Whether to escape values and identifiers
     * @return  bool    TRUE on success, FALSE on failure
     */
    public function insert($table = '', $set = NULL, $escape = NULL) {
        if ($set !== NULL) {
            $this->set($set, '', $escape);
        }


        $sql = $this->_insert(
                $this->protect_identifiers(
                        $table, TRUE, $escape, FALSE
                ), array_keys($this->qb_set), array_values($this->qb_set)
        );
        $this->deletekey();
        return $this->query($sql, 'AUD');
    }

    // --------------------------------------------------------------------

    /**
     * FROM tables
     *
     * Groups tables in FROM clauses if needed, so there is no confusion
     * about operator precedence.
     *
     * Note: This is only used (and overridden) by MySQL and CUBRID.
     *
     * @return  string
     */
    protected function _from_tables() {
        return implode(', ', $this->qb_from);
    }

    // --------------------------------------------------------------------

    /**
     * Get UPDATE query string
     *
     * Compiles an update query and returns the sql
     *
     * @param   string  the table to update
     * @param   bool    TRUE: reset QB values; FALSE: leave QB values alone
     * @return  string
     */
    public function get_compiled_update($table = '', $reset = TRUE) {

        if ($this->_validate_update($table) === FALSE) {
            return FALSE;
        }

        $sql = $this->_update($this->qb_from[0], $this->qb_set);

        if ($reset === TRUE) {
            $this->_reset_write();
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * UPDATE
     *
     * Compiles an update string and runs the query.
     *
     * @param   string  $table
     * @param   array   $set    An associative array of update values
     * @param   mixed   $where
     * @param   int $limit
     * @return  bool    TRUE on success, FALSE on failure
     */
    public function update($table = '', $set = NULL, $where = NULL, $limit = NULL) {
        // Combine any cached components with the current statements
        if ($set !== NULL) {
            $this->set($set);
        }
        if ($where !== NULL) {
            $this->where($where);
        }
        if (!empty($limit)) {
            $this->limit($limit);
        }
        $sql = $this->_update($table, $this->qb_set);
        $this->deletekey();
        return $this->query($sql, 'AUD');
    }

    // --------------------------------------------------------------------

    /**
     * Truncate
     *
     * Compiles a truncate string and runs the query
     * If the database does not support the truncate() command
     * This function maps to "DELETE FROM table"
     *
     * @param   string  the table to truncate
     * @return  bool    TRUE on success, FALSE on failure
     */
    public function truncate($table = '') {
        if (!$table) {
            $table = $this->protect_identifiers($table, TRUE, NULL, FALSE);
        }

        $sql = $this->_truncate($table);
        $this->_reset_write();
        return $this->query($sql);
    }

    // --------------------------------------------------------------------

    /**
     * Truncate statement
     *
     * Generates a platform-specific truncate string from the supplied data
     *
     * If the database does not support the truncate() command,
     * then this method maps to 'DELETE FROM table'
     *
     * @param   string  the table name
     * @return  string
     */
    protected function _truncate($table) {
        return 'TRUNCATE ' . $table;
    }

    // --------------------------------------------------------------------

    /**
     * Get DELETE query string
     *
     * Compiles a delete query string and returns the sql
     *
     * @param   string  the table to delete from
     * @param   bool    TRUE: reset QB values; FALSE: leave QB values alone
     * @return  string
     */
    public function get_compiled_delete($table = '', $reset = TRUE) {
        $this->return_delete_sql = TRUE;
        $sql = $this->delete($table, '', NULL, $reset);
        $this->return_delete_sql = FALSE;
        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Delete
     *
     * Compiles a delete string and runs the query
     *
     * @param   mixed   the table(s) to delete from. String or array
     * @param   mixed   the where clause
     * @param   mixed   the limit clause
     * @param   bool
     * @return  mixed
     */
    public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE) {


        if ($table === '') {
            return;
        } elseif (is_array($table)) {
            empty($where) && $reset_data = FALSE;

            foreach ($table as $single_table) {
                $this->delete($single_table, $where, $limit, $reset_data);
            }

            return;
        } else {
            $table = $this->protect_identifiers($table, TRUE, NULL, FALSE);
        }

        if ($where !== '') {
            $this->where($where);
        }

        if (!empty($limit)) {
            $this->limit($limit);
        }

        if (count($this->qb_where) === 0) {
            return FALSE;
        }

        $sql = $this->_delete($table);

        $this->deletekey();
        
        return $this->query($sql, 'AUD');
    }

    // --------------------------------------------------------------------

    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @param   string  the table name
     * @return  string
     */
    protected function _delete($table) {
        return 'DELETE FROM ' . $table . $this->_compile_wh('qb_where')
                . ($this->qb_limit ? ' LIMIT ' . $this->qb_limit : '');
    }

    // --------------------------------------------------------------------

    /**
     * Track Aliases
     *
     * Used to track SQL statements written with aliased tables.
     *
     * @param   string  The table to inspect
     * @return  string
     */
    protected function _track_aliases($table) {
        if (is_array($table)) {
            foreach ($table as $t) {
                $this->_track_aliases($t);
            }
            return;
        }

        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== FALSE) {
            return $this->_track_aliases(explode(',', $table));
        }

        // if a table alias is used we can recognize it by a space
        if (strpos($table, ' ') !== FALSE) {
            // if the alias is written with the AS keyword, remove it
            // Grab the alias
            $table = trim(strrchr($table, ' '));

            // Store the alias, if it doesn't already exist
            if (!in_array($table, $this->qb_aliased_tables)) {
                $this->qb_aliased_tables[] = $table;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Compile the SELECT statement
     *
     * Generates a query string based on which functions were used.
     * Should not be called directly.
     *
     * @param   bool    $select_override
     * @return  string
     */
    protected function _compile_select($select_override = FALSE) {
        // Combine any cached components with the current statements
        // Write the "select" portion of the query
        if ($select_override !== FALSE) {
            $sql = $select_override;
        } else {
            $sql = (!$this->qb_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

            if (count($this->qb_select) === 0) {
                $sql .= '*';
            } else {
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather than in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->qb_select as $key => $val) {
                    $no_escape = isset($this->qb_no_escape[$key]) ? $this->qb_no_escape[$key] : NULL;
                    $this->qb_select[$key] = $this->protect_identifiers($val, FALSE, $no_escape);
                }

                $sql .= implode(', ', $this->qb_select);
            }
        }

        // Write the "FROM" portion of the query
        if (count($this->qb_from) > 0) {
            $sql .= "\nFROM " . $this->_from_tables();
        }

        // Write the "JOIN" portion of the query
        if (count($this->qb_join) > 0) {
            $sql .= "\n" . implode("\n", $this->qb_join);
        }

        $sql .= $this->_compile_wh('qb_where')
                . $this->_compile_group_by()
                . $this->_compile_wh('qb_having')
                . $this->_compile_order_by(); // ORDER BY
        // LIMIT
        if ($this->qb_limit) {
            return $this->_limit($sql . "\n");
        }

        return $sql;
    }

    // --------------------------------------------------------------------

    /**
     * Compile WHERE, HAVING statements
     *
     * Escapes identifiers in WHERE and HAVING statements at execution time.
     *
     * Required so that aliases are tracked properly, regardless of whether
     * where(), or_where(), having(), or_having are called prior to from(),
     * join() and dbprefix is added only if needed.
     *
     * @param   string  $qb_key 'qb_where' or 'qb_having'
     * @return  string  SQL statement
     */
    protected function _compile_wh($qb_key) {
        if (count($this->$qb_key) > 0) {
            for ($i = 0, $c = count($this->$qb_key); $i < $c; $i++) {
                // Is this condition already compiled?
                if (is_string($this->{$qb_key}[$i])) {
                    continue;
                } elseif ($this->{$qb_key}[$i]['escape'] === FALSE) {
                    $this->{$qb_key}[$i] = $this->{$qb_key}[$i]['condition'];
                    continue;
                }

                // Split multiple conditions
                $conditions = preg_split(
                        '/((?:^|\s+)AND\s+|(?:^|\s+)OR\s+)/i', $this->{$qb_key}[$i]['condition'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
                );

                for ($ci = 0, $cc = count($conditions); $ci < $cc; $ci++) {
                    if (($op = $this->_get_operator($conditions[$ci])) === FALSE
                            OR ! preg_match('/^(\(?)(.*)(' . preg_quote($op, '/') . ')\s*(.*(?<!\)))?(\)?)$/i', $conditions[$ci], $matches)) {
                        continue;
                    }

                    // $matches = array(
                    //  0 => '(test <= foo)',   /* the whole thing */
                    //  1 => '(',       /* optional */
                    //  2 => 'test',        /* the field name */
                    //  3 => ' <= ',        /* $op */
                    //  4 => 'foo',     /* optional, if $op is e.g. 'IS NULL' */
                    //  5 => ')'        /* optional */
                    // );

                    if (!empty($matches[4])) {
                        $this->_is_literal($matches[4]) OR $matches[4] = $this->protect_identifiers(trim($matches[4]));
                        $matches[4] = ' ' . $matches[4];
                    }

                    $conditions[$ci] = $matches[1] . $this->protect_identifiers(trim($matches[2]))
                            . ' ' . trim($matches[3]) . $matches[4] . $matches[5];
                }

                $this->{$qb_key}[$i] = implode('', $conditions);
            }

            return ($qb_key === 'qb_having' ? "\nHAVING " : "\nWHERE ")
                    . implode("\n", $this->$qb_key);
        }

        return '';
    }

    // --------------------------------------------------------------------

    /**
     * Compile GROUP BY
     *
     * Escapes identifiers in GROUP BY statements at execution time.
     *
     * Required so that aliases are tracked properly, regardless of wether
     * group_by() is called prior to from(), join() and dbprefix is added
     * only if needed.
     *
     * @return  string  SQL statement
     */
    protected function _compile_group_by() {
        if (count($this->qb_groupby) > 0) {
            for ($i = 0, $c = count($this->qb_groupby); $i < $c; $i++) {
                // Is it already compiled?
                if (is_string($this->qb_groupby[$i])) {
                    continue;
                }

                $this->qb_groupby[$i] = ($this->qb_groupby[$i]['escape'] === FALSE OR $this->_is_literal($this->qb_groupby[$i]['field'])) ? $this->qb_groupby[$i]['field'] : $this->protect_identifiers($this->qb_groupby[$i]['field']);
            }

            return "\nGROUP BY " . implode(', ', $this->qb_groupby);
        }

        return '';
    }

    // --------------------------------------------------------------------

    /**
     * Compile ORDER BY
     *
     * Escapes identifiers in ORDER BY statements at execution time.
     *
     * Required so that aliases are tracked properly, regardless of wether
     * order_by() is called prior to from(), join() and dbprefix is added
     * only if needed.
     *
     * @return  string  SQL statement
     */
    protected function _compile_order_by() {
        if (is_array($this->qb_orderby) && count($this->qb_orderby) > 0) {
            for ($i = 0, $c = count($this->qb_orderby); $i < $c; $i++) {
                if ($this->qb_orderby[$i]['escape'] !== FALSE && !$this->_is_literal($this->qb_orderby[$i]['field'])) {
                    $this->qb_orderby[$i]['field'] = $this->protect_identifiers($this->qb_orderby[$i]['field']);
                }

                $this->qb_orderby[$i] = $this->qb_orderby[$i]['field'] . $this->qb_orderby[$i]['direction'];
            }

            return $this->qb_orderby = "\nORDER BY " . implode(', ', $this->qb_orderby);
        } elseif (is_string($this->qb_orderby)) {
            return $this->qb_orderby;
        }

        return '';
    }

    // --------------------------------------------------------------------

    /**
     * Object to Array
     *
     * Takes an object as input and converts the class variables to array key/vals
     *
     * @param   object
     * @return  array
     */
    protected function _object_to_array($object) {
        if (!is_object($object)) {
            return $object;
        }

        $array = array();
        foreach (get_object_vars($object) as $key => $val) {
            // There are some built in keys we need to ignore for this conversion
            if (!is_object($val) && !is_array($val) && $key !== '_parent_name') {
                $array[$key] = $val;
            }
        }

        return $array;
    }

    // --------------------------------------------------------------------

    /**
     * Object to Array
     *
     * Takes an object as input and converts the class variables to array key/vals
     *
     * @param   object
     * @return  array
     */
    protected function _object_to_array_batch($object) {
        if (!is_object($object)) {
            return $object;
        }

        $array = array();
        $out = get_object_vars($object);
        $fields = array_keys($out);

        foreach ($fields as $val) {
            // There are some built in keys we need to ignore for this conversion
            if ($val !== '_parent_name') {
                $i = 0;
                foreach ($out[$val] as $data) {
                    $array[$i++][$val] = $data;
                }
            }
        }

        return $array;
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------

    /**
     * Is literal
     *
     * Determines if a string represents a literal value or a field name
     *
     * @param   string  $str
     * @return  bool
     */
    protected function _is_literal($str) {
        $str = trim($str);

        if (empty($str) OR ctype_digit($str) OR (string) (float) $str === $str OR in_array(strtoupper($str), array('TRUE', 'FALSE'), TRUE)) {
            return TRUE;
        }

        static $_str;

        if (empty($_str)) {
            $_str = ($this->_escape_char !== '"') ? array('"', "'") : array("'");
        }

        return in_array($str[0], $_str, TRUE);
    }

    public function deletekey() {
        $this->return_delete_sql = FALSE;
        $this->reset_delete_data = FALSE;
        $this->qb_select = array();
        $this->qb_distinct = FALSE;
        $this->qb_from = array();
        $this->qb_join = array();
        $this->qb_where = array();
        $this->qb_groupby = array();
        $this->qb_having = array();
        $this->qb_keys = array();
        $this->qb_limit = FALSE;
        $this->qb_offset = FALSE;
        $this->qb_orderby = array();
        $this->qb_set = array();
        $this->qb_aliased_tables = array();
        $this->qb_where_group_started = FALSE;
        $this->qb_where_group_count = 0;

        $this->qb_caching = FALSE;
        $this->qb_cache_exists = array();
        $this->qb_cache_select = array();
        $this->qb_cache_from = array();
        $this->qb_cache_join = array();
        $this->qb_cache_where = array();
        $this->qb_cache_groupby = array();
        $this->qb_cache_having = array();
        $this->qb_cache_orderby = array();
        $this->qb_cache_set = array();
        $this->qb_no_escape = array();
        $this->qb_cache_no_escape = array();
    }

}
