<?php

class ControllerExtensionModuleMetaGenerator extends Controller {

    private $fields = [
        'meta_title',
        'meta_description',
        'meta_h1',
    ];

    public function install()
    {
        $this->load->model('extension/module/meta_generator');
        $this->model_extension_module_meta_generator->createTable();
    }

    public function uninstall()
    {
        $this->load->model('extension/module/meta_generator');
        $this->model_extension_module_meta_generator->dropTable();
    }

    public function index() {
        $this->load->language('extension/module/meta_generator');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getAllCategories();

        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['fields'] = $this->fields;

        $this->load->model('extension/module/meta_generator');
        $data['formulas'] = $this->model_extension_module_meta_generator->getCategoryFormulaList();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['user_token'] = $this->session->data['user_token'];

        $this->response->setOutput($this->load->view('extension/module/meta_generator', $data));
    }

    public function addFormula()
    {
        $this->load->model('extension/module/meta_generator');

        $this->model_extension_module_meta_generator->deleteCurrentFormula(
            $this->request->post['field'],
            $this->request->post['category_id'],
            $this->request->post['language_id']
        );

        $this->model_extension_module_meta_generator->addFormula(
            $this->request->post['field'],
            $this->request->post['formula'],
            $this->request->post['category_id'],
            $this->request->post['language_id']
        );

        $result = [
            'success' => true,
        ];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));

    }

    public function deleteFormula()
    {
        $this->load->model('extension/module/meta_generator');

        $this->model_extension_module_meta_generator->deleteCategoryFormula($this->request->post['category_id']);

        $result = [
            'success' => true,
        ];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));

    }

}