<?php

class ModelExtensionModuleMetaGenerator extends Model {

    public function changeField($category_id, $field, $product_id)
    {
        $language_id = $this->config->get('config_language_id');
        $formula = $this->getFormula($category_id, $field, $language_id);

        if (strpos($formula, '%h1%') !== false) {
            $formula = str_replace('%h1%', $this->getProductField($product_id, 'meta_h1'), $formula);
        }

        if (strpos($formula, '%ch1%') !== false) {
            $formula = str_replace('%ch1%', $this->getCategoryField($category_id, 'meta_h1'), $formula);
        }

        return $formula;
    }

    public function getFormula($category_id, $field, $language_id)
    {
        $query = $this->db->query("SELECT formula FROM " . DB_PREFIX . "meta_generator_categories WHERE language_id = '" . (int)$language_id . "' AND field = '" . $this->db->escape($field) . "' AND category_id = '" . (int)$category_id . "'");
        return $query->row['formula'];
    }

    public function existsFormula($category_id, $field)
    {
        $language_id = $this->config->get('config_language_id');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "meta_generator_categories WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "' AND field = '" . $this->db->escape($field) . "'");
        return count($query->rows) > 0;
    }

    public function getProductMainCategoryId($product_id) {
        $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1' LIMIT 1");

        return ($query->num_rows ? (int)$query->row['category_id'] : 0);
    }

    public function getProductField($product_id, $field)
    {
        $language_id = $this->config->get('config_language_id');
        $query = $this->db->query("SELECT $field FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
        $result = $query->row[$field];

        if ((!$result || $result == '') && $field === 'meta_h1') {
            return $this->getProductField($product_id, 'name');
        }

        return $result;
    }

    public function getCategoryField($category_id, $field)
    {
        $language_id = $this->config->get('config_language_id');
        $query = $this->db->query("SELECT $field FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'");
        $result = $query->row[$field];

        if ((!$result || $result == '') && $field === 'meta_h1') {
            return $this->getCategoryField($category_id, 'name');
        }

        return $result;
    }

}