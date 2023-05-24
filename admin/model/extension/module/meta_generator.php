<?php

class ModelExtensionModuleMetaGenerator extends Model {

    public function createTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "meta_generator_categories` (
        `field` varchar(16) NOT NULL,
        `formula` text NOT NULL,
        `category_id` int NOT NULL,
        `language_id` int NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
    }


    public function dropTable()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "meta_generator_categories`");
    }

    public function addFormula($field, $formula, $category_id, $language_id)
    {
        return $this->db->query("INSERT INTO " . DB_PREFIX . "meta_generator_categories (field, formula, category_id, language_id)
            VALUES ('" . $this->db->escape($field) . "', '" . $this->db->escape($formula) . "', '" . $this->db->escape($category_id) . "', '" . $this->db->escape($language_id) . "')");

    }

    public function deleteCategoryFormula($category_id)
    {
        return $this->db->query("DELETE FROM " . DB_PREFIX . "meta_generator_categories WHERE category_id = '" . $this->db->escape($category_id) . "'");
    }

    public function deleteCurrentFormula($field, $category_id, $language_id)
    {
        return $this->db->query("DELETE FROM " . DB_PREFIX . "meta_generator_categories WHERE category_id = '" . $this->db->escape($category_id) . "' AND field = '" . $this->db->escape($field) . "' AND language_id = '" . $this->db->escape($language_id) . "'");
    }

    public function getCategoryFormulaList()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "meta_generator_categories");

        $result = [];
        foreach ($query->rows as $row) {
            $result[$row['category_id']][$row['field']][$row['language_id']] = $row['formula'];
        }

        return $result;
    }

}