<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Abstract class for loading records from the DB.
 *
 * @package    mod_forum
 * @copyright  2019 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_forum\local\vaults;

defined('MOODLE_INTERNAL') || die();

use mod_forum\local\factories\entity as entity_factory;
use moodle_database;

/**
 * Abstract class for loading records from the DB.
 *
 * @copyright  2019 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class db_table_vault {
    /** @var moodle_database $db A moodle database */
    private $db;
    /** @var entity_factory $entityfactory Entity factory */
    private $entityfactory;
    /** @var object $legacyfactory Entity->legacy factory */
    private $legacyfactory;

    /**
     * Constructor.
     *
     * @param moodle_database $db A moodle database
     * @param entity_factory $entityfactory Entity factory
     * @param object $legacyfactory Legacy factory
     */
    public function __construct(
        moodle_database $db,
        entity_factory $entityfactory,
        $legacyfactory
    ) {
        $this->db = $db;
        $this->entityfactory = $entityfactory;
        $this->legacyfactory = $legacyfactory;
    }

    /**
     * Get the table alias.
     *
     * @return string
     */
    abstract protected function get_table_alias(): string;

    /**
     * Build the SQL to be used in get_records_sql.
     *
     * @param string|null $wheresql Where conditions for the SQL
     * @param string|null $sortsql Order by conditions for the SQL
     * @param object|null $user The user object
     * @return string
     */
    abstract protected function generate_get_records_sql(?string $wheresql = null, ?string $sortsql = null,
        ?int $userid = null): string;

    /**
     * Convert the DB records into entities. The list of records will have been
     * passed through any preprocessors that may be defined before being given to
     * this function.
     *
     * Each result will from the list will be an associative array where the key
     * is set to the identifier given to the preprocessor and the result is the value
     * generated by the preprocessor.
     *
     * All results will have a 'record' key who's value is the original DB record.
     *
     * @param array $results The DB records
     */
    abstract protected function from_db_records(array $results);

    /**
     * Get the list of preprocessors to run on the DB record results. The preprocessors
     * should be defined using an associative array. The key used to identify the
     * preprocessor in this list will be used to identify the value of that preprocessor
     * in the list of results when passed to the from_db_records function.
     *
     * @return array
     */
    protected function get_preprocessors(): array {
        return [];
    }

    /**
     * Get the moodle database.
     *
     * @return moodle_database
     */
    protected function get_db(): moodle_database {
        return $this->db;
    }

    /**
     * Get the entity factory.
     *
     * @return entity_factory
     */
    protected function get_entity_factory(): entity_factory {
        return $this->entityfactory;
    }

    /**
     * Get the legacy factory
     *
     * @return object
     */
    protected function get_legacy_factory() {
        return $this->legacyfactory;
    }

    /**
     * Execute the defined preprocessors on the DB record results and then convert
     * them into entities.
     *
     * @param stdClass[] $records List of DB results
     * @return array
     */
    protected function transform_db_records_to_entities(array $records) {
        $preprocessors = $this->get_preprocessors();
        $result = array_map(function($record) {
            return ['record' => $record];
        }, $records);

        $result = array_reduce(array_keys($preprocessors), function($carry, $preprocessor) use ($records, $preprocessors) {
            $step = $preprocessors[$preprocessor];
            $dependencies = $step->execute($records);

            foreach ($dependencies as $index => $dependency) {
                // Add the new dependency to the list.
                $carry[$index] = array_merge($carry[$index], [$preprocessor => $dependency]);
            }

            return $carry;
        }, $result);

        return $this->from_db_records($result);
    }

    /**
     * Get the entity for the given id.
     *
     * @param int $id Identifier for the entity
     * @return object|null
     */
    public function get_from_id(int $id) {
        $records = $this->get_from_ids([$id]);
        return count($records) ? array_shift($records) : null;
    }

    /**
     * Get the list of entities for the given ids.
     *
     * @param int[] $ids Identifiers
     * @return array
     */
    public function get_from_ids(array $ids) {
        $alias = $this->get_table_alias();
        list($insql, $params) = $this->get_db()->get_in_or_equal($ids);
        $wheresql = $alias . '.id ' . $insql;
        $sql = $this->generate_get_records_sql($wheresql);
        $records = $this->get_db()->get_records_sql($sql, $params);

        return $this->transform_db_records_to_entities($records);
    }
}
